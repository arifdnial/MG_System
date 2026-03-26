@extends('layouts.main')
@section('title', 'Announcements - EduPro LMS')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Announcements</h1>
            <p class="text-slate-500 mt-1">Keep your students informed</p>
        </div>
        <a href="{{ route('teacher.announcements.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-5 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
            <i class='bx bx-plus'></i>
            New Announcement
        </a>
    </div>

    <div class="space-y-4">
        @forelse($announcements as $ann)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 group">
            <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-3 flex-wrap">
                        <h3 class="font-bold text-slate-800 text-lg">{{ $ann->title }}</h3>
                        <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                            <i class='bx bx-book mr-1'></i>
                            {{ $ann->subject->title }}
                        </span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">{{ $ann->content }}</p>
                    @if($ann->video_link)
                    <a href="{{ $ann->video_link }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-700 font-medium mt-4 group/link">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center group-hover/link:bg-indigo-200 transition-colors">
                            <i class='bx bxl-youtube'></i>
                        </div>
                        Watch Video
                        <i class='bx bx-external-link'></i>
                    </a>
                    @endif
                    <div class="flex items-center gap-2 mt-4 text-xs text-slate-400">
                        <i class='bx bx-time-five'></i>
                        <span>{{ $ann->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 lg:ml-4">
                    <a href="{{ route('teacher.announcements.edit', $ann) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                        <i class='bx bx-edit'></i>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('teacher.announcements.destroy', $ann) }}" onsubmit="return confirm('Delete this announcement?')">
                        @csrf @method('DELETE')
                        <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-medium text-red-600 hover:bg-red-50 transition-colors">
                            <i class='bx bx-trash'></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-pink-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i class='bx bxs-megaphone text-slate-400 text-3xl'></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">No announcements yet</h3>
            <p class="text-slate-500 mb-6 max-w-sm mx-auto">Create announcements to keep your students updated</p>
            <a href="{{ route('teacher.announcements.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25">
                <i class='bx bx-plus'></i>
                Create Your First Announcement
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
