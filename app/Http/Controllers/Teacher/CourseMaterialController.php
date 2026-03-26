<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CourseMaterial;
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
        ]);

        $subject = Subject::findOrFail($request->subject_id);
        if ($subject->teacher_id !== auth()->id())
            abort(403);

        $filePath = null;
        if ($request->hasFile('video_file')) {
            $filePath = $request->file('video_file')->store('course-materials', 'public');
        }

        CourseMaterial::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'teacher_id' => auth()->id(),
            'video_url' => $request->video_url,
            'file_path' => $filePath,
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
}
