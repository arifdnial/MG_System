<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Subject;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('teacher_id', auth()->id())
            ->with('subject')->latest()->get();
        return view('teacher.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $subjects = auth()->user()->subjects()->get();
        return view('teacher.announcements.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'video_link' => 'nullable|url',
        ]);

        $subject = Subject::findOrFail($request->subject_id);
        if ($subject->teacher_id !== auth()->id())
            abort(403);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'subject_id' => $request->subject_id,
            'teacher_id' => auth()->id(),
            'video_link' => $request->video_link,
        ]);

        return redirect()->route('teacher.announcements.index')->with('success', 'Announcement posted.');
    }

    public function edit(Announcement $announcement)
    {
        if ($announcement->teacher_id !== auth()->id())
            abort(403);
        $subjects = auth()->user()->subjects()->get();
        return view('teacher.announcements.edit', compact('announcement', 'subjects'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        if ($announcement->teacher_id !== auth()->id())
            abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_link' => 'nullable|url',
        ]);

        $announcement->update($request->only(['title', 'content', 'video_link']));
        return redirect()->route('teacher.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->teacher_id !== auth()->id())
            abort(403);
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}
