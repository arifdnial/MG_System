@extends('layouts.main')
@section('title', 'Attendance Check-in - MGM-System')

@section('content')
    <div class="max-w-xl mx-auto space-y-6">
        <div class="text-center">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center text-white mx-auto mb-3">
                <i class='bx bx-qr-scan text-3xl'></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900">Attendance Check-in</h1>
            <p class="text-slate-500 mt-1">
                {{ $session->subject->title }} • {{ $session->section?->name ?? '-' }} •
                {{ $session->attendance_date->format('M d, Y') }}
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h2 class="font-bold text-slate-800">Enter code</h2>
                <p class="text-sm text-slate-500 mt-1">If you scanned a QR, the code is already filled.</p>
            </div>
            <div class="p-5">
                @if($record)
                    <div class="mb-4 p-4 rounded-2xl border border-slate-200 bg-slate-50">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-slate-600">Your current status</div>
                            @if($record->status === 'present')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-xs">
                                    Present
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-red-700 font-semibold text-xs">
                                    Absent
                                </span>
                            @endif
                        </div>
                        @if($record->checked_in_at)
                            <div class="text-xs text-slate-500 mt-2">
                                Checked in at <span class="font-semibold text-slate-700">{{ $record->checked_in_at->format('g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 rounded-2xl bg-red-50 border border-red-100 text-red-800">
                        <p class="font-semibold mb-2">Please fix the errors:</p>
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('student.attendance.checkin.submit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Code</label>
                        <input name="code" value="{{ old('code', $session->checkin_code) }}" required
                            class="w-full text-center tracking-[0.35em] font-extrabold text-lg border border-slate-200 rounded-2xl px-4 py-4 bg-slate-50 focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition-all"
                            placeholder="XXXXXX" />
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Status:</span>
                        @if($session->checkin_open)
                            <span class="font-semibold text-emerald-700">Open</span>
                        @else
                            <span class="font-semibold text-red-700">Closed</span>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg shadow-emerald-500/20">
                        <i class='bx bx-check'></i>
                        Check in (Mark Present)
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('student.attendance.scan') }}" class="text-indigo-600 hover:underline text-sm font-semibold">
                        Open camera scanner
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center text-xs text-slate-400">
            Logged in as <span class="font-semibold text-slate-600">{{ auth()->user()->name }}</span>
        </div>
    </div>
@endsection
