<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CourseMaterial;
use App\Models\MaterialSubmission;
use App\Models\Subject;
use Illuminate\Http\Request;

class CourseMaterialController extends Controller
{
    public function index()
    {
        $materials = CourseMaterial::where('teacher_id', auth()->id())
            ->with('subject')->latest()->get();
        return view('teacher.materials.index', compact('materials'));
    }

    public function create()
    {
        $subjects = auth()->user()->subjects()->get();
        return view('teacher.materials.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,webm|max:102400',
            'document_file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx|max:20480',
        ]);

        $subject = Subject::findOrFail($request->subject_id);
        if ($subject->teacher_id !== auth()->id())
            abort(403);

        $videoPath = null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('course-materials/videos', 'public');
        }

        $documentPath = null;
        if ($request->hasFile('document_file')) {
            $documentPath = $request->file('document_file')->store('course-materials/documents', 'public');
        }

        CourseMaterial::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'teacher_id' => auth()->id(),
            'video_url' => $request->video_url,
            'file_path' => $videoPath,
            'document_path' => $documentPath,
            'is_group_activity' => $request->has('is_group_activity'),
        ]);

        return redirect()->route('teacher.materials.index')->with('success', 'Course material added.');
    }

    public function destroy(CourseMaterial $material)
    {
        if ($material->teacher_id !== auth()->id())
            abort(403);
        $material->delete();
        return back()->with('success', 'Material deleted.');
    }

    public function comment(Request $request, MaterialSubmission $submission)
    {
        // Ensure the submission belongs to a material owned by this teacher
        if ($submission->courseMaterial->teacher_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'teacher_comment' => 'required|string|max:1000',
        ]);

        $submission->update([
            'teacher_comment' => $request->teacher_comment,
            'commented_at'    => now(),
        ]);

        return back()->with('success', 'Comment saved successfully.');
    }

    public function submissions(CourseMaterial $material)
    {
        if ($material->teacher_id !== auth()->id())
            abort(403);

        $material->load('subject.sections.students');
        
        if ($material->is_group_activity) {
            $groups = $material->subject->studyGroups()->with('students')->get();
            $submissions = MaterialSubmission::where('course_material_id', $material->id)
                ->whereNotNull('study_group_id')
                ->get()->keyBy('study_group_id');
            
            return view('teacher.materials.submissions-group', compact('material', 'groups', 'submissions'));
        }

        // Individual logic (original)
        $enrolledStudents = $material->subject->sections->flatMap->students->unique('id');
        $submissions = MaterialSubmission::where('course_material_id', $material->id)
            ->whereNull('study_group_id')
            ->get()->keyBy('student_id');

        return view('teacher.materials.submissions', compact('material', 'enrolledStudents', 'submissions'));
    }
}
