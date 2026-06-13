@extends('layouts.main')
@section('title', 'Edit Announcement - MGM-System')

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
                    <i class='bx bx-edit text-xl'></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Edit Announcement</h1>
                    <p class="text-sm text-slate-500">Update your announcement</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('teacher.announcements.update', $announcement) }}" class="space-y-6">
                @csrf @method('PUT')
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Subject</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-book text-slate-400'></i>
                        </div>
                        <input type="text" value="{{ $announcement->subject->title }}" disabled 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Title</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-heading text-slate-400'></i>
                        </div>
                        <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" placeholder="Announcement title">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Content</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 pt-4 pointer-events-none">
                            <i class='bx bx-align-left text-slate-400'></i>
                        </div>
                        <textarea name="content" rows="5" required 
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all resize-none" placeholder="Write your announcement content...">{{ old('content', $announcement->content) }}</textarea>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Video Link <span class="text-slate-400 font-normal">(optional)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bxl-youtube text-slate-400'></i>
                        </div>
                        <input type="url" name="video_link" value="{{ old('video_link', $announcement->video_link) }}" 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" placeholder="https://youtube.com/...">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('teacher.announcements.index') }}" class="px-6 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                        <i class='bx bx-save'></i>
                        Update Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
