<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\StudyGroup;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        // Get subjects the student is enrolled in
        $subjects = auth()->user()->sections()->with('subject.studyGroups.students')->get()
                        ->pluck('subject')->unique('id');
        
        return view('student.groups.index', compact('subjects'));
    }

    public function showSubjectGroups(Subject $subject)
    {
        // Ensure student is enrolled in the subject
        if (!auth()->user()->sections()->where('subject_id', $subject->id)->exists()) {
            abort(403);
        }

        $groups = $subject->studyGroups()->withCount('students')->get();
        $userGroup = auth()->user()->studyGroups()->where('subject_id', $subject->id)->first();

        return view('student.groups.subject', compact('subject', 'groups', 'userGroup'));
    }

    public function join(StudyGroup $group)
    {
        $user = auth()->user();
        
        // 1. Check if student is enrolled in the subject
        if (!$user->sections()->where('subject_id', $group->subject_id)->exists()) {
            return back()->with('error', 'You are not enrolled in this subject.');
        }

        // 2. Already in a group for this subject?
        if ($user->studyGroups()->where('subject_id', $group->subject_id)->exists()) {
            return back()->with('error', 'You are already in a group for this subject.');
        }

        // 3. Group full?
        if ($group->students()->count() >= $group->max_students) {
            return back()->with('error', 'This group is already full.');
        }

        $group->students()->attach($user->id);

        return back()->with('success', 'Joined group ' . $group->name);
    }

    public function leave(StudyGroup $group)
    {
        auth()->user()->studyGroups()->detach($group->id);
        return back()->with('success', 'Left group ' . $group->name);
    }
}
