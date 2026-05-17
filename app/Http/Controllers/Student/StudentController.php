<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamQuestion;
use App\Models\Announcement;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $sectionIds = $user->sections()->pluck('sections.id');
        $subjectIds = \DB::table('sections')->whereIn('id', $sectionIds)->pluck('subject_id')->unique();

        $subjects = Subject::whereIn('id', $subjectIds)->with('teacher')->get();
        $upcomingExams = Exam::whereIn('subject_id', $subjectIds)
            ->where(function ($q) use ($sectionIds) {
            $q->whereIn('section_id', $sectionIds)->orWhereNull('section_id');
        })
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date')->take(5)->get();
        $recentAnnouncements = Announcement::whereIn('subject_id', $subjectIds)->latest()->take(5)->get();

        $stats = [
            'total_subjects' => $subjects->count(),
            'total_exams_taken' => ExamAnswer::where('student_id', $user->id)->distinct('exam_question_id')->count(),
            'upcoming_exams' => $upcomingExams->count(),
        ];

        return view('student.dashboard', compact('stats', 'subjects', 'upcomingExams', 'recentAnnouncements'));
    }

    public function profile()
    {
        return view('teacher.profile', ['user' => auth()->user()]); // reuse teacher profile view
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'form_class' => 'nullable|string|max:50',
        ]);

        $user->update($request->only(['name', 'phone', 'email', 'form_class']));
        return back()->with('success', 'Profile updated successfully.');
    }

    public function subjectsIndex()
    {
        $user = auth()->user();
        $sectionIds = $user->sections()->pluck('sections.id');
        $subjectIds = \DB::table('sections')->whereIn('id', $sectionIds)->pluck('subject_id')->unique();
        $subjects = Subject::whereIn('id', $subjectIds)->with(['teacher', 'announcements', 'courseMaterials'])->get();

        return view('student.subjects.index', compact('subjects'));
    }

    public function subjectsShow(Subject $subject)
    {
        // Verify student is enrolled
        $user = auth()->user();
        $enrolled = $user->sections()->where('subject_id', $subject->id)->exists();
        if (!$enrolled)
            abort(403, 'You are not enrolled in this subject.');

        $subject->load(['teacher', 'announcements' => fn($q) => $q->latest(), 'courseMaterials' => fn($q) => $q->latest()]);
        return view('student.subjects.show', compact('subject'));
    }

    public function examsIndex()
    {
        $user = auth()->user();
        $sectionIds = $user->sections()->pluck('sections.id');
        $subjectIds = \DB::table('sections')->whereIn('id', $sectionIds)->pluck('subject_id')->unique();

        $exams = Exam::whereIn('subject_id', $subjectIds)
            ->where(function ($q) use ($sectionIds) {
            $q->whereIn('section_id', $sectionIds)->orWhereNull('section_id');
        })
            ->with('subject')->latest()->get();

        // Check which exams the student has already taken
        $takenExamQuestionIds = ExamAnswer::where('student_id', $user->id)->pluck('exam_question_id');
        $takenExamIds = ExamQuestion::whereIn('id', $takenExamQuestionIds)->pluck('exam_id')->unique();

        return view('student.exams.index', compact('exams', 'takenExamIds'));
    }

    public function examsTake(Exam $exam)
    {
        $this->authorizeStudentExam($exam);
        $exam->load(['questions.options', 'subject']);

        $existingAnswers = ExamAnswer::where('student_id', auth()->id())
            ->whereIn('exam_question_id', $exam->questions->pluck('id'))
            ->get()->keyBy('exam_question_id');

        return view('student.exams.take', compact('exam', 'existingAnswers'));
    }

    public function examsSave(Request $request, Exam $exam)
    {
        $this->authorizeStudentExam($exam);
        $exam->load('questions.options');

        foreach ($exam->questions as $question) {
            $answerData = [
                'exam_question_id' => $question->id,
                'student_id' => auth()->id(),
            ];

            if ($question->type === 'mcq') {
                $selected = $request->input("answers.{$question->id}.option");
                if ($selected) {
                    $answerData['selected_option_id'] = $selected;
                }
            }
            else {
                $answerData['answer_text'] = $request->input("answers.{$question->id}.text", '');
            }

            ExamAnswer::updateOrCreate(
                ['exam_question_id' => $question->id, 'student_id' => auth()->id()],
                $answerData
            );
        }

        return back()->with('success', 'Answers saved successfully!');
    }

    public function examsResult(Exam $exam)
    {
        $this->authorizeStudentExam($exam);
        $exam->load(['questions.options', 'subject']);

        $answers = ExamAnswer::where('student_id', auth()->id())
            ->whereIn('exam_question_id', $exam->questions->pluck('id'))
            ->get()->keyBy('exam_question_id');

        $totalPoints = $exam->questions->sum('points');
        $earnedPoints = $answers->sum('points_earned');
        $percentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;

        return view('student.exams.result', compact('exam', 'answers', 'totalPoints', 'earnedPoints', 'percentage'));
    }

    public function marks()
    {
        $user = auth()->user();
        $sectionIds = $user->sections()->pluck('sections.id');
        $subjectIds = \DB::table('sections')->whereIn('id', $sectionIds)->pluck('subject_id')->unique();

        // Only show exams where marks are released
        $exams = Exam::whereIn('subject_id', $subjectIds)
            ->where('marks_released', true)
            ->with('subject')
            ->get();

        $results = [];
        foreach ($exams as $exam) {
            $answers = ExamAnswer::where('student_id', $user->id)
                ->whereIn('exam_question_id', $exam->questions->pluck('id'))
                ->get();

            if ($answers->isNotEmpty()) {
                $total = $exam->questions->sum('points');
                $earned = $answers->sum('points_earned');
                $results[] = [
                    'exam' => $exam,
                    'earned' => $earned,
                    'total' => $total,
                    'percentage' => $total > 0 ? round(($earned / $total) * 100) : 0,
                ];
            }
        }

        return view('student.marks', compact('results'));
    }

    private function authorizeStudentExam(Exam $exam)
    {
        $user = auth()->user();
        $sectionIds = $user->sections()->pluck('sections.id');
        $subjectIds = \DB::table('sections')->whereIn('id', $sectionIds)->pluck('subject_id')->unique();

        if (!$subjectIds->contains($exam->subject_id)) {
            abort(403, 'You are not enrolled in this exam\'s subject.');
        }
    }
}
