@extends('layouts.main')
@section('title', 'Teacher Dashboard - EduPro LMS')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-gray-500">Welcome back, <span class="font-semibold text-indigo-600">{{ auth()->user()->name }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $cards = [
                ['label' => 'My Subjects', 'value' => $stats['total_subjects'], 'color' => 'indigo', 'bg' => 'from-indigo-500 to-indigo-600', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['label' => 'My Students', 'value' => $stats['total_students'], 'color' => 'emerald', 'bg' => 'from-emerald-500 to-emerald-600', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Exams', 'value' => $stats['total_exams'], 'color' => 'amber', 'bg' => 'from-amber-500 to-orange-500', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ['label' => 'Announcements', 'value' => $stats['total_announcements'], 'color' => 'purple', 'bg' => 'from-purple-500 to-purple-600', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
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
        {{-- Upcoming Exams --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-900 text-lg">Upcoming Exams</h2>
                    <p class="text-sm text-gray-500 mt-1">Your scheduled exams</p>
                </div>
                <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Exam
                </a>
            </div>
            <div class="p-5 space-y-3">
                @forelse($upcomingExams as $exam)
                <a href="{{ route('teacher.exams.show', $exam) }}" class="block p-4 rounded-xl hover:bg-indigo-50 transition border border-gray-100 hover:border-indigo-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-indigo-600 transition">{{ $exam->title }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $exam->subject->title }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-lg">{{ $exam->exam_date?->format('M d') ?? 'No date' }}</span>
                    </div>
                </a>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <p class="text-gray-400">No upcoming exams</p>
                    <a href="{{ route('teacher.exams.create') }}" class="text-indigo-600 font-medium text-sm mt-2 inline-block hover:underline">Create your first exam →</a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Announcements --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-900 text-lg">Recent Announcements</h2>
                    <p class="text-sm text-gray-500 mt-1">Latest announcements</p>
                </div>
                <a href="{{ route('teacher.announcements.create') }}" class="inline-flex items-center gap-1 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New
                </a>
            </div>
            <div class="p-5 space-y-3">
                @forelse($recentAnnouncements as $ann)
                <div class="p-4 rounded-xl border border-gray-100 hover:border-purple-200 hover:bg-purple-50 transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $ann->title }}</p>
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ Str::limit($ann->content, 100) }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">{{ $ann->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <p class="text-gray-400">No announcements yet</p>
                    <a href="{{ route('teacher.announcements.create') }}" class="text-purple-600 font-medium text-sm mt-2 inline-block hover:underline">Create your first announcement →</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
