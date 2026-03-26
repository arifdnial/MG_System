@extends('layouts.main')
@section('title', 'My Subjects - EduPro LMS')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">My Subjects</h1>
            <p class="text-slate-500 mt-1">Manage your teaching subjects and sections</p>
        </div>
        <a href="{{ route('teacher.subjects.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-5 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
            <i class='bx bx-plus'></i>
            New Subject
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($subjects as $subject)
        <a href="{{ route('teacher.subjects.show', $subject) }}" class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
                    {{ strtoupper(substr($subject->title, 0, 1)) }}
                </div>
                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-all">
                    <i class='bx bx-chevron-right text-xl'></i>
                </div>
            </div>
            <h3 class="font-bold text-slate-800 text-lg mb-2 group-hover:text-indigo-600 transition-colors">{{ $subject->title }}</h3>
            <p class="text-slate-500 text-sm mb-4 line-clamp-2 min-h-[40px]">{{ $subject->description ?? 'No description provided' }}</p>
            <div class="flex gap-4 pt-4 border-t border-slate-100">
                <div class="flex items-center gap-2 text-sm">
                    <div class="w-6 h-6 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <i class='bx bx-section text-indigo-600 text-xs'></i>
                    </div>
                    <span class="text-slate-600 font-medium">{{ $subject->sections_count }} sections</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <div class="w-6 h-6 rounded-lg bg-amber-50 flex items-center justify-center">
                        <i class='bx bx-file text-amber-600 text-xs'></i>
                    </div>
                    <span class="text-slate-600 font-medium">{{ $subject->exams_count }} exams</span>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <i class='bx bxs-book-alt text-slate-400 text-3xl'></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">No subjects yet</h3>
                <p class="text-slate-500 mb-6 max-w-sm mx-auto">Get started by creating your first subject to organize your teaching materials</p>
                <a href="{{ route('teacher.subjects.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25">
                    <i class='bx bx-plus'></i>
                    Create Your First Subject
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
