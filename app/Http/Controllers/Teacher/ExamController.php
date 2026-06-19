<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamOption;
use App\Models\ExamAnswer;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $subjectIds = auth()->user()->subjects()->pluck('id');
        $exams = Exam::whereIn('subject_id', $subjectIds)
            ->with(['subject', 'section'])->withCount('questions')->latest()->get();
        return view('teacher.exams.index', compact('exams'));
    }

    public function create()
    {
        $subjects = auth()->user()->subjects()->with('sections')->get();
        return view('teacher.exams.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'nullable|exists:sections,id',
            'description' => 'nullable|string',
            'exam_date' => 'nullable|date',
        ]);

        // Verify teacher owns the subject
        $subject = Subject::findOrFail($request->subject_id);
        if ($subject->teacher_id !== auth()->id())
            abort(403);

        $exam = Exam::create([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'section_id' => $request->section_id,
            'description' => $request->description,
            'exam_date' => $request->exam_date,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('teacher.exams.show', $exam)->with('success', 'Exam created. Now add questions.');
    }

    public function show(Exam $exam)
    {
        $this->authorizeExam($exam);
        $exam->load(['questions.options', 'subject', 'section']);

        // Get submissions
        $studentIds = ExamAnswer::whereIn('exam_question_id', $exam->questions->pluck('id'))
            ->pluck('student_id')->unique();
        $submissions = [];
        foreach ($studentIds as $sid) {
            $student = User::find($sid);
            $answers = ExamAnswer::where('student_id', $sid)
                ->whereIn('exam_question_id', $exam->questions->pluck('id'))->get();
            $earned = $answers->sum('points_earned');
            $total = $exam->questions->sum('points');
            $submissions[] = [
                'student' => $student,
                'earned' => $earned,
                'total' => $total,
                'percentage' => $total > 0 ? round(($earned / $total) * 100) : 0,
            ];
        }

        // Subjective answers for manual grading
        $subjectiveQuestionIds = $exam->questions->where('type', 'subjective')->pluck('id');
        $subjectiveAnswers = $subjectiveQuestionIds->isNotEmpty()
            ? ExamAnswer::whereIn('exam_question_id', $subjectiveQuestionIds)
                ->with(['student', 'question'])
                ->get()
            : collect();

        return view('teacher.exams.show', compact('exam', 'submissions', 'subjectiveAnswers'));
    }

    public function addQuestion(Request $request, Exam $exam)
    {
        $this->authorizeExam($exam);

        $request->validate([
            'type' => 'required|in:mcq,subjective',
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:type,mcq|array|min:2',
            'options.*.text' => 'required_if:type,mcq|string',
            'correct_option' => 'required_if:type,mcq|integer',
        ]);

        $question = $exam->questions()->create([
            'type' => $request->type,
            'question_text' => $request->question_text,
            'points' => $request->points,
            'order' => $exam->questions()->count() + 1,
        ]);

        if ($request->type === 'mcq' && $request->options) {
            foreach ($request->options as $i => $opt) {
                if (!empty($opt['text'])) {
                    $question->options()->create([
                        'option_text' => $opt['text'],
                        'is_correct' => $i == $request->correct_option,
                    ]);
                }
            }
        }

        return back()->with('success', 'Question added.');
    }

    public function editQuestion(Exam $exam, ExamQuestion $question)
    {
        $this->authorizeExam($exam);
        $question->load('options');
        return view('teacher.exams.edit_question', compact('exam', 'question'));
    }

    public function updateQuestion(Request $request, Exam $exam, ExamQuestion $question)
    {
        $this->authorizeExam($exam);

        $request->validate([
            'type' => 'required|in:mcq,subjective',
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:type,mcq|array|min:2',
            'options.*.text' => 'required_if:type,mcq|string',
            'correct_option' => 'required_if:type,mcq|integer',
        ]);

        $question->update([
            'type' => $request->type,
            'question_text' => $request->question_text,
            'points' => $request->points,
        ]);
        if ($request->type === 'mcq') {
            // Delete old options and create new ones for simplicity
            $question->options()->delete();
            if ($request->options) {
                foreach ($request->options as $i => $opt) {
                    if (!empty($opt['text'])) {
                        $question->options()->create([
                            'option_text' => $opt['text'],
                            'is_correct' => $i == $request->correct_option,
                        ]);
                    }
                }
            }
        } else {
            // If switched to subjective, remove any MCQ options
            $question->options()->delete();
        }

        return redirect()->route('teacher.exams.show', $exam)->with('success', 'Question updated.');
    }

    public function deleteQuestion(Exam $exam, ExamQuestion $question)
    {
        $this->authorizeExam($exam);
        $question->delete();
        return back()->with('success', 'Question deleted.');
    }

    public function toggleMarks(Exam $exam)
    {
        $this->authorizeExam($exam);
        $exam->update(['marks_released' => !$exam->marks_released]);
        $status = $exam->marks_released ? 'released' : 'hidden';
        return back()->with('success', "Marks have been {$status}.");
    }

    public function gradeSubjective(Request $request, Exam $exam)
    {
        $this->authorizeExam($exam);

        $request->validate([
            'grades' => 'array',
            'grades.*' => 'nullable|integer|min:0',
        ]);

        foreach ($request->input('grades', []) as $answerId => $points) {
            $answer = ExamAnswer::find($answerId);
            if (!$answer) continue;

            $question = $answer->question;
            if (!$question || $question->exam_id !== $exam->id || $question->type !== 'subjective') {
                continue;
            }

            $points = max(0, min((int) $points, $question->points));
            $answer->points_earned = $points;
            $answer->is_correct = $points >= $question->points;
            $answer->save();
        }

        return back()->with('success', 'Subjective answers graded successfully.');
    }

    public function destroy(Exam $exam)
    {
        $this->authorizeExam($exam);
        $exam->delete();
        return redirect()->route('teacher.exams.index')->with('success', 'Exam deleted.');
    }

    private function authorizeExam(Exam $exam)
    {
        if ($exam->created_by !== auth()->id())
            abort(403);
    }
}