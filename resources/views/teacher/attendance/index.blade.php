@extends('layouts.main')
@section('title', 'Attendance - ' . $subject->title . ' - EduPro LMS')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1">
                <a href="{{ route('teacher.subjects.show', $subject) }}"
                    class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 transition-colors mb-2">
                    <i class='bx bx-arrow-back'></i>
                    Back to Subject
                </a>
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center text-white font-bold text-xl">
                        <i class='bx bx-calendar-check text-2xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Attendance</h1>
                        <p class="text-slate-500">{{ $subject->title }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('teacher.attendance.create', $subject) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg shadow-emerald-500/20">
                    <i class='bx bx-plus'></i>
                    New Attendance
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class='bx bx-history text-emerald-700 text-xl'></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-lg">Attendance History</h2>
                    <p class="text-sm text-slate-500">View and edit previous attendance sheets</p>
                </div>
            </div>

            <div class="p-5">
                @if($sessions->isEmpty())
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i class='bx bx-calendar-x text-slate-400 text-3xl'></i>
                        </div>
                        <p class="text-slate-500 font-medium">No attendance marked yet</p>
                        <a href="{{ route('teacher.attendance.create', $subject) }}"
                            class="text-emerald-700 font-semibold text-sm mt-2 inline-block hover:underline">
                            Create the first attendance sheet →
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-slate-500">
                                    <th class="py-3 px-3 font-semibold">Date</th>
                                    <th class="py-3 px-3 font-semibold">Section</th>
                                    <th class="py-3 px-3 font-semibold">Present</th>
                                    <th class="py-3 px-3 font-semibold">Absent</th>
                                    <th class="py-3 px-3 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($sessions as $s)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-3 px-3 font-semibold text-slate-800">
                                            {{ \Illuminate\Support\Carbon::parse($s->attendance_date)->format('M d, Y') }}
                                        </td>
                                        <td class="py-3 px-3 text-slate-700">{{ $s->section?->name ?? '-' }}</td>
                                        <td class="py-3 px-3">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold">
                                                {{ $s->present_count }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-red-700 font-semibold">
                                                {{ $s->absent_count }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-right">
                                            <a href="{{ route('teacher.attendance.edit', $s) }}"
                                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl font-semibold text-indigo-600 hover:bg-indigo-50 border border-indigo-200 transition-colors">
                                                <i class='bx bx-edit'></i>
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

