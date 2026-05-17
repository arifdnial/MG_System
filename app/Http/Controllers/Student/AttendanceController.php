<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function scan()
    {
        return view('student.attendance.scan');
    }

    public function showCheckin(Request $request, string $code)
    {
        $student = auth()->user();

        $session = AttendanceSession::where('checkin_code', strtoupper($code))
            ->with(['subject', 'section'])
            ->firstOrFail();

        $enrolled = $student->sections()
            ->where('sections.id', $session->section_id)
            ->exists();

        if (!$enrolled) {
            abort(403, 'You are not enrolled in this attendance section.');
        }

        $record = AttendanceRecord::where('attendance_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        return view('student.attendance.checkin', compact('session', 'record'));
    }

    public function submitCheckin(Request $request)
    {
        $student = auth()->user();

        $data = $request->validate([
            'code' => 'required|string|max:20',
        ]);

        $code = strtoupper(trim($data['code']));

        $session = AttendanceSession::where('checkin_code', $code)
            ->with(['subject', 'section'])
            ->firstOrFail();

        $enrolled = $student->sections()
            ->where('sections.id', $session->section_id)
            ->exists();

        if (!$enrolled) {
            abort(403, 'You are not enrolled in this attendance section.');
        }

        if (!$session->checkin_open) {
            return back()->withErrors(['code' => 'Check-in is closed.']);
        }

        if ($session->checkin_starts_at && now()->lt($session->checkin_starts_at)) {
            return back()->withErrors(['code' => 'Check-in has not started yet.']);
        }

        if ($session->checkin_ends_at && now()->gt($session->checkin_ends_at)) {
            return back()->withErrors(['code' => 'Check-in has ended.']);
        }

        DB::transaction(function () use ($session, $student) {
            $record = AttendanceRecord::firstOrCreate([
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
            ], [
                'status' => 'absent',
            ]);

            $record->status = 'present';
            $record->checked_in_at = now();
            $record->save();
        });

        return redirect()
            ->route('student.attendance.subject', $session->subject)
            ->with('success', 'Checked in successfully. You are marked present.');
    }

    public function subject(Subject $subject)
    {
        $student = auth()->user();

        // Verify student is enrolled in this subject
        $enrolled = $student->sections()->where('subject_id', $subject->id)->exists();
        if (!$enrolled) {
            abort(403, 'You are not enrolled in this subject.');
        }

        $sectionIds = $student->sections()
            ->where('subject_id', $subject->id)
            ->pluck('sections.id')
            ->all();

        $sessions = AttendanceSession::where('subject_id', $subject->id)
            ->whereIn('section_id', $sectionIds)
            ->with(['section'])
            ->orderByDesc('attendance_date')
            ->get();

        $recordsBySession = AttendanceRecord::where('student_id', $student->id)
            ->whereIn('attendance_session_id', $sessions->pluck('id'))
            ->get()
            ->keyBy('attendance_session_id');

        return view('student.attendance.subject', compact('subject', 'sessions', 'recordsBySession'));
    }
}

