@extends('layouts.main')
@section('title', 'Student Dashboard - EduPro LMS')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-gray-500">Welcome back, <span class="font-semibold text-indigo-600">{{ auth()->user()->name }}</span> {{ auth()->user()->form_class ? '(' . auth()->user()->form_class . ')' : '' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @php
            $cards = [
                ['label' => 'Enrolled Subjects', 'value' => $stats['total_subjects'], 'color' => 'indigo', 'bg' => 'from-indigo-500 to-indigo-600', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['label' => 'Exams Taken', 'value' => $stats['total_exams_taken'], 'color' => 'emerald', 'bg' => 'from-emerald-500 to-emerald-600', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                ['label' => 'Upcoming Exams', 'value' => $stats['upcoming_exams'], 'color' => 'amber', 'bg' => 'from-amber-500 to-orange-500', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 card-hover">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br {{ $card['bg'] }} rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">{{ $card['label'] }}</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $card['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-900 text-lg">My Subjects</h2>
                    <p class="text-sm text-gray-500 mt-1">Subjects you're enrolled in</p>
                </div>
                <a href="{{ route('student.subjects.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View all →</a>
            </div>
            <div class="p-5 space-y-3">
                @forelse($subjects as $subject)
                <a href="{{ route('student.subjects.show', $subject) }}" class="block p-4 rounded-xl hover:bg-indigo-50 transition border border-gray-100 hover:border-indigo-200 group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                            {{ strtoupper(substr($subject->title, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 group-hover:text-indigo-600 transition">{{ $subject->title }}</p>
                            <p class="text-sm text-gray-500">Teacher: {{ $subject->teacher->name }}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <p class="text-gray-400">Not enrolled in any subjects yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-gray-900 text-lg">Upcoming Exams</h2>
                        <p class="text-sm text-gray-500 mt-1">Exams to prepare for</p>
                    </div>
                </div>
                <div class="p-5 space-y-3">
                    @forelse($upcomingExams as $exam)
                    <a href="{{ route('student.exams.take', $exam) }}" class="block p-4 rounded-xl hover:bg-amber-50 transition border border-gray-100 hover:border-amber-200 group">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-900 group-hover:text-amber-600 transition">{{ $exam->title }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $exam->subject->title }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-lg">{{ $exam->exam_date?->format('M d') ?? 'TBA' }}</span>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-gray-400 text-sm">No upcoming exams</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-gray-900 text-lg">Recent Announcements</h2>
                        <p class="text-sm text-gray-500 mt-1">Latest updates from teachers</p>
                    </div>
                </div>
                <div class="p-5 space-y-3">
                    @forelse($recentAnnouncements as $ann)
                    <div class="p-4 rounded-xl border border-gray-100">
                        <p class="font-semibold text-gray-900 text-sm">{{ $ann->title }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $ann->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-gray-400 text-sm">No announcements</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
