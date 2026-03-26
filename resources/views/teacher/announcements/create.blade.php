@extends('layouts.main')
@section('title', 'New Announcement - EduPro LMS')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('teacher.announcements.index') }}" class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-1">
            <i class='bx bx-arrow-back'></i>
            Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-pink-50 to-rose-50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center text-white">
                    <i class='bx bxs-megaphone text-xl'></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">{{ isset($announcement) ? 'Edit' : 'New' }} Announcement</h1>
                    <p class="text-sm text-slate-500">{{ isset($announcement) ? 'Update your announcement' : 'Share updates with your students' }}</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ isset($announcement) ? route('teacher.announcements.update', $announcement) : route('teacher.announcements.store') }}" class="space-y-6">
                @csrf
                @if(isset($announcement)) @method('PUT') @endif

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Subject</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-book text-slate-400'></i>
                        </div>
                        <select name="subject_id" required 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all appearance-none" {{ isset($announcement) ? 'disabled' : '' }}>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ (isset($announcement) && $announcement->subject_id == $subject->id) || old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->title }}</option>
                            @endforeach
                        </select>
                        @if(isset($announcement))
                        <input type="hidden" name="subject_id" value="{{ $announcement->subject_id }}">
                        @endif
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class='bx bx-chevron-down text-slate-400'></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Title</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-heading text-slate-400'></i>
                        </div>
                        <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" placeholder="Announcement title">
                    </div>
                    @error('title') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Content</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 pt-4 pointer-events-none">
                            <i class='bx bx-align-left text-slate-400'></i>
                        </div>
                        <textarea name="content" rows="5" required 
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all resize-none" placeholder="Write your announcement content...">{{ old('content', $announcement->content ?? '') }}</textarea>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Video Link <span class="text-slate-400 font-normal">(optional)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bxl-youtube text-slate-400'></i>
                        </div>
                        <input type="url" name="video_link" value="{{ old('video_link', $announcement->video_link ?? '') }}" 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" placeholder="https://youtube.com/...">
                    </div>
                    <p class="text-xs text-slate-400">Paste a YouTube or video link for students to watch</p>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('teacher.announcements.index') }}" class="px-6 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                        <i class='bx {{ isset($announcement) ? 'bx-save' : 'bx-send' }}'></i>
                        {{ isset($announcement) ? 'Update' : 'Post' }} Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
