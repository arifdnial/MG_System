@extends('layouts.main')
@section('title', 'My Attendance - ' . $subject->title . ' - MGM-System')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1">
                <a href="{{ route('student.subjects.show', $subject) }}"
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
                        <h1 class="text-2xl font-bold text-slate-800">My Attendance</h1>
                        <p class="text-slate-500">{{ $subject->title }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('student.attendance.scan') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/20">
                    <i class='bx bx-qr-scan'></i>
                    Scan QR
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h2 class="font-bold text-slate-800 text-lg">Attendance History</h2>
                <p class="text-sm text-slate-500 mt-1">Your status per date</p>
            </div>

            <div class="p-5">
                @if($sessions->isEmpty())
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i class='bx bx-calendar-x text-slate-400 text-3xl'></i>
                        </div>
                        <p class="text-slate-500 font-medium">No attendance sessions yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-slate-500">
                                    <th class="py-3 px-3 font-semibold">Date</th>
                                    <th class="py-3 px-3 font-semibold">Section</th>
                                    <th class="py-3 px-3 font-semibold">Status</th>
                                    <th class="py-3 px-3 font-semibold">Checked-in</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($sessions as $s)
                                    @php
                                        $r = $recordsBySession[$s->id] ?? null;
                                        $status = $r?->status ?? 'absent';
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-3 px-3 font-semibold text-slate-800">
                                            {{ $s->attendance_date->format('M d, Y') }}
                                        </td>
                                        <td class="py-3 px-3 text-slate-700">{{ $s->section?->name ?? '-' }}</td>
                                        <td class="py-3 px-3">
                                            @if($status === 'present')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold">
                                                    Present
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-red-700 font-semibold">
                                                    Absent
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-slate-600">
                                            {{ $r?->checked_in_at?->format('g:i A') ?? '-' }}
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

