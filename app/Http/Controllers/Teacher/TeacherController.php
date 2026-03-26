<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Exam;
use App\Models\Announcement;
use App\Models\CourseMaterial;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $subjects = $user->subjects()->withCount('sections')->get();
        $totalStudents = 0;
        foreach ($subjects as $subject) {
            $totalStudents += User::where('role', 'student')
                ->whereHas('sections', fn($q) => $q->where('subject_id', $subject->id))->count();
        }
        $upcomingExams = Exam::whereIn('subject_id', $subjects->pluck('id'))
            ->where('exam_date', '>=', now())->orderBy('exam_date')->take(5)->get();
        $recentAnnouncements = Announcement::where('teacher_id', $user->id)->latest()->take(5)->get();

        $stats = [
            'total_subjects' => $subjects->count(),
            'total_students' => $totalStudents,
            'total_exams' => Exam::whereIn('subject_id', $subjects->pluck('id'))->count(),
            'total_announcements' => Announcement::where('teacher_id', $user->id)->count(),
        ];

        return view('teacher.dashboard', compact('stats', 'subjects', 'upcomingExams', 'recentAnnouncements'));
    }

    public function profile()
    {
        return view('teacher.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'phone', 'email']));
        return back()->with('success', 'Profile updated successfully.');
    }

    // Subject Management
    public function subjectsIndex()
    {
        $subjects = auth()->user()->subjects()->withCount(['sections', 'exams', 'announcements'])->get();
        return view('teacher.subjects.index', compact('subjects'));
    }

    public function subjectsCreate()
    {
        return view('teacher.subjects.create');
    }

    public function subjectsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        auth()->user()->subjects()->create($request->only(['title', 'description']));
        return redirect()->route('teacher.subjects.index')->with('success', 'Subject created successfully.');
    }

    public function subjectsShow(Subject $subject)
    {
        $this->authorizeTeacher($subject);
        $subject->load(['sections.students', 'exams', 'announcements', 'courseMaterials']);
        $allStudents = User::where('role', 'student')->orderBy('name')->get();
        return view('teacher.subjects.show', compact('subject', 'allStudents'));
    }

    public function subjectsUpdate(Request $request, Subject $subject)
    {
        $this->authorizeTeacher($subject);
        $request->validate(['title' => 'required|string|max:255', 'description' => 'nullable|string']);
        $subject->update($request->only(['title', 'description']));
        return back()->with('success', 'Subject updated.');
    }

    public function subjectsDestroy(Subject $subject)
    {
        $this->authorizeTeacher($subject);
        $subject->delete();
        return redirect()->route('teacher.subjects.index')->with('success', 'Subject deleted.');
    }

    // Section Management
    public function sectionsStore(Request $request, Subject $subject)
    {
        $this->authorizeTeacher($subject);
        $request->validate(['name' => 'required|string|max:255']);
        $subject->sections()->create(['name' => $request->name]);
        return back()->with('success', 'Section added.');
    }

    public function sectionsDestroy(Subject $subject, Section $section)
    {
        $this->authorizeTeacher($subject);
        $section->delete();
        return back()->with('success', 'Section deleted.');
    }

    public function sectionsAddStudent(Request $request, Subject $subject, Section $section)
    {
        $this->authorizeTeacher($subject);
        $request->validate(['student_id' => 'required|exists:users,id']);
        $section->students()->syncWithoutDetaching([$request->student_id]);
        return back()->with('success', 'Student added to section.');
    }

    public function sectionsRemoveStudent(Subject $subject, Section $section, User $student)
    {
        $this->authorizeTeacher($subject);
        $section->students()->detach($student->id);
        return back()->with('success', 'Student removed from section.');
    }

    private function authorizeTeacher(Subject $subject)
    {
        if ($subject->teacher_id !== auth()->id()) {
            abort(403, 'You do not own this subject.');
        }
    }
}
