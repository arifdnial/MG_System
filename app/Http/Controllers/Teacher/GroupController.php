<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\StudyGroup;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Subject $subject)
    {
        if ($subject->teacher_id !== auth()->id()) {
            abort(403);
        }

        $groups = $subject->studyGroups()->withCount('students')->get();
        return view('teacher.groups.index', compact('subject', 'groups'));
    }

    public function store(Request $request, Subject $subject)
    {
        if ($subject->teacher_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'max_students' => 'required|integer|min:1',
        ]);

        $subject->studyGroups()->create([
            'name' => $request->name,
            'max_students' => $request->max_students,
            'teacher_id' => auth()->id(),
        ]);

        return back()->with('success', 'Group created successfully.');
    }

    public function destroy(StudyGroup $group)
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403);
        }

        $group->delete();
        return back()->with('success', 'Group deleted successfully.');
    }
}
