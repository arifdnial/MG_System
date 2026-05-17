@extends('layouts.main')
@section('title', 'Edit Attendance - ' . $session->subject->title . ' - EduPro LMS')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1">
                <a href="{{ route('teacher.attendance.index', $session->subject) }}"
                    class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 transition-colors mb-2">
                    <i class='bx bx-arrow-back'></i>
                    Back to Attendance
                </a>
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center text-white font-bold text-xl">
                        <i class='bx bx-calendar-check text-2xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Mark Attendance</h1>
                        <p class="text-slate-500">
                            {{ $session->subject->title }} • {{ $session->section?->name ?? '-' }} •
                            {{ $session->attendance_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="setAll('present')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-emerald-700 hover:bg-emerald-50 border border-emerald-200 transition-colors">
                    <i class='bx bx-check-circle'></i>
                    Mark all Present
                </button>
                <button type="button" onclick="setAll('absent')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-red-700 hover:bg-red-50 border border-red-200 transition-colors">
                    <i class='bx bx-x-circle'></i>
                    Mark all Absent
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class='bx bx-list-check text-emerald-700 text-xl'></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-lg">Students</h2>
                    <p class="text-sm text-slate-500">Choose present/absent, optional note</p>
                </div>
            </div>

            <div class="p-5">
                <div class="mb-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2 border border-slate-200 rounded-2xl p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-slate-800">Student Check-in (QR / Code)</h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    Students scan the QR or enter the code to mark themselves present (only while open).
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if($session->checkin_open)
                                    <form method="POST" action="{{ route('teacher.attendance.checkin.close', $session) }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-red-700 hover:bg-red-50 border border-red-200 transition-colors">
                                            <i class='bx bx-lock-alt'></i>
                                            Close
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('teacher.attendance.checkin.open', $session) }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="number" name="minutes" min="1" max="480" value="30"
                                            class="w-24 border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition-all"
                                            title="Minutes open" />
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-emerald-700 hover:bg-emerald-50 border border-emerald-200 transition-colors">
                                            <i class='bx bx-qr-scan'></i>
                                            Open
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        @php
                            $checkinUrl = $session->checkin_code
                                ? route('student.attendance.checkin.show', ['code' => $session->checkin_code])
                                : null;
                        @endphp

                        <div class="mt-4 flex flex-col sm:flex-row gap-4">
                            <div class="sm:w-56">
                                <div class="w-full aspect-square rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                                    <div id="qrcode" class="p-2"></div>
                                    @if(!$checkinUrl)
                                        <div class="text-slate-400 text-sm font-semibold">QR will appear when opened</div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Status</span>
                                    @if($session->checkin_open)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-xs">
                                            Open
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            Ends {{ $session->checkin_ends_at?->diffForHumans() ?? '' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-semibold text-xs">
                                            Closed
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Code</div>
                                    <div class="flex items-center gap-2">
                                        <div class="px-4 py-3 rounded-2xl bg-slate-950 text-white font-extrabold tracking-[0.35em] text-lg">
                                            {{ $session->checkin_code ?? '------' }}
                                        </div>
                                        @if($checkinUrl)
                                            <button type="button" onclick="copyCheckinUrl()"
                                                class="inline-flex items-center gap-2 px-4 py-3 rounded-xl font-semibold text-indigo-600 hover:bg-indigo-50 border border-indigo-200 transition-colors">
                                                <i class='bx bx-copy'></i>
                                                Copy link
                                            </button>
                                        @endif
                                    </div>
                                    @if($checkinUrl)
                                        <div class="text-xs text-slate-500 mt-2 break-all">
                                            Link: <span class="font-semibold text-slate-700">{{ $checkinUrl }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border border-slate-200 rounded-2xl p-5">
                        <h3 class="font-bold text-slate-800">Quick stats</h3>
                        <p class="text-sm text-slate-500 mt-1">Saved records for this session</p>
                        @php
                            $present = $records->where('status', 'present')->count();
                            $absent = $records->where('status', 'absent')->count();
                        @endphp
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                                <span class="font-semibold text-emerald-800">Present</span>
                                <span class="font-extrabold text-emerald-800">{{ $present }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 border border-red-100">
                                <span class="font-semibold text-red-800">Absent</span>
                                <span class="font-extrabold text-red-800">{{ $absent }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200">
                                <span class="font-semibold text-slate-700">Total</span>
                                <span class="font-extrabold text-slate-800">{{ $students->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('teacher.attendance.update', $session) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-slate-500">
                                    <th class="py-3 px-3 font-semibold">Student</th>
                                    <th class="py-3 px-3 font-semibold">IC</th>
                                    <th class="py-3 px-3 font-semibold">Status</th>
                                    <th class="py-3 px-3 font-semibold">Note</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($students as $student)
                                    @php
                                        $r = $records[$student->id] ?? null;
                                        $status = old("status.$student->id", $r?->status ?? 'absent');
                                        $note = old("note.$student->id", $r?->note);
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-3 px-3">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                </div>
                                                <div class="font-semibold text-slate-800">{{ $student->name }}</div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3 text-slate-600">{{ $student->ic }}</td>
                                        <td class="py-3 px-3">
                                            <select name="status[{{ $student->id }}]"
                                                class="attendance-status w-40 border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition-all">
                                                <option value="present" @selected($status === 'present')>Present</option>
                                                <option value="absent" @selected($status === 'absent')>Absent</option>
                                            </select>
                                        </td>
                                        <td class="py-3 px-3">
                                            <input type="text" name="note[{{ $student->id }}]" value="{{ $note }}"
                                                placeholder="Optional note..."
                                                class="w-full min-w-[220px] border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition-all" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-2 flex items-center gap-3">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg shadow-emerald-500/20">
                            <i class='bx bx-save'></i>
                            Save Attendance
                        </button>
                        <a href="{{ route('teacher.attendance.index', $session->subject) }}"
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-slate-700 hover:bg-slate-100 border border-slate-200 transition-colors">
                            Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        const CHECKIN_URL = @json($checkinUrl);

        function setAll(status) {
            document.querySelectorAll('.attendance-status').forEach((el) => {
                el.value = status;
            });
        }

        function copyCheckinUrl() {
            if (!CHECKIN_URL)
                return;
            navigator.clipboard.writeText(CHECKIN_URL);
        }

        if (CHECKIN_URL) {
            const el = document.getElementById('qrcode');
            el.innerHTML = '';
            new QRCode(el, {
                text: CHECKIN_URL,
                width: 180,
                height: 180,
            });
        }
    </script>
@endpush

