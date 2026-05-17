<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function index(Subject $subject)
    {
        $this->authorizeTeacher($subject);

        $sessions = AttendanceSession::where('subject_id', $subject->id)
            ->with(['section'])
            ->withCount([
                'records as present_count' => fn ($q) => $q->where('status', 'present'),
                'records as absent_count' => fn ($q) => $q->where('status', 'absent'),
            ])
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->get();

        $sections = $subject->sections()->orderBy('name')->get();

        return view('teacher.attendance.index', compact('subject', 'sessions', 'sections'));
    }

    public function create(Subject $subject)
    {
        $this->authorizeTeacher($subject);

        $sections = $subject->sections()->withCount('students')->orderBy('name')->get();
        return view('teacher.attendance.create', compact('subject', 'sections'));
    }

    public function store(Request $request, Subject $subject)
    {
        $this->authorizeTeacher($subject);

        $data = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'attendance_date' => 'required|date',
        ]);

        /** @var Section $section */
        $section = Section::where('id', $data['section_id'])
            ->where('subject_id', $subject->id)
            ->firstOrFail();

        $teacherId = auth()->id();

        $session = AttendanceSession::firstOrCreate(
            [
                'subject_id' => $subject->id,
                'section_id' => $section->id,
                'attendance_date' => $data['attendance_date'],
            ],
            [
                'teacher_id' => $teacherId,
            ]
        );

        // Ensure ownership even if the session existed.
        if ($session->teacher_id !== $teacherId) {
            abort(403);
        }

        // Seed records for all students in the section (default absent).
        $studentIds = $section->students()->pluck('users.id')->all();

        DB::transaction(function () use ($session, $studentIds) {
            foreach ($studentIds as $sid) {
                AttendanceRecord::firstOrCreate([
                    'attendance_session_id' => $session->id,
                    'student_id' => $sid,
                ], [
                    'status' => 'absent',
                ]);
            }
        });

        return redirect()
            ->route('teacher.attendance.edit', $session)
            ->with('success', 'Attendance sheet is ready. Mark students and save.');
    }

    public function edit(AttendanceSession $session)
    {
        $this->authorizeSession($session);

        $session->load(['subject', 'section']);

        $students = $session->section
            ->students()
            ->orderBy('name')
            ->get();

        $records = $session->records()
            ->get()
            ->keyBy('student_id');

        return view('teacher.attendance.edit', compact('session', 'students', 'records'));
    }

    public function update(Request $request, AttendanceSession $session)
    {
        $this->authorizeSession($session);

        $data = $request->validate([
            'status' => 'array',
            'status.*' => 'in:present,absent',
            'note' => 'array',
            'note.*' => 'nullable|string|max:255',
        ]);

        $statusByStudent = $data['status'] ?? [];
        $noteByStudent = $data['note'] ?? [];

        DB::transaction(function () use ($session, $statusByStudent, $noteByStudent) {
            foreach ($session->section->students()->pluck('users.id')->all() as $studentId) {
                $record = AttendanceRecord::firstOrCreate([
                    'attendance_session_id' => $session->id,
                    'student_id' => $studentId,
                ], [
                    'status' => 'absent',
                ]);

                $record->status = $statusByStudent[$studentId] ?? 'absent';
                $record->note = $noteByStudent[$studentId] ?? null;
                $record->save();
            }
        });

        return back()->with('success', 'Attendance saved.');
    }

    public function openCheckin(Request $request, AttendanceSession $session)
    {
        $this->authorizeSession($session);

        $data = $request->validate([
            'minutes' => 'nullable|integer|min:1|max:480',
        ]);

        $minutes = (int) ($data['minutes'] ?? 30);

        $code = $this->generateUniqueCode();

        $session->update([
            'checkin_code' => $code,
            'checkin_open' => true,
            'checkin_starts_at' => now(),
            'checkin_ends_at' => now()->addMinutes($minutes),
        ]);

        return back()->with('success', "Check-in opened for {$minutes} minutes.");
    }

    public function closeCheckin(AttendanceSession $session)
    {
        $this->authorizeSession($session);

        $session->update([
            'checkin_open' => false,
            'checkin_ends_at' => now(),
        ]);

        return back()->with('success', 'Check-in closed.');
    }

    private function authorizeTeacher(Subject $subject): void
    {
        if ($subject->teacher_id !== auth()->id()) {
            abort(403);
        }
    }

    private function authorizeSession(AttendanceSession $session): void
    {
        if ($session->teacher_id !== auth()->id()) {
            abort(403);
        }
    }

    private function generateUniqueCode(): string
    {
        for ($i = 0; $i < 10; $i++) {
            $code = strtoupper(Str::random(6));
            $exists = AttendanceSession::where('checkin_code', $code)->exists();
            if (!$exists) {
                return $code;
            }
        }

        // Extremely unlikely fallback
        return strtoupper(Str::random(10));
    }
}

