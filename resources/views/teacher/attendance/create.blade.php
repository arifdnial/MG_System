@extends('layouts.main')
@section('title', 'New Attendance - ' . $subject->title . ' - EduPro LMS')

@section('content')
    <div class="max-w-3xl space-y-6">
        <div>
            <a href="{{ route('teacher.attendance.index', $subject) }}"
                class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 transition-colors mb-2">
                <i class='bx bx-arrow-back'></i>
                Back to Attendance
            </a>
            <h1 class="text-2xl font-bold text-slate-800">New Attendance</h1>
            <p class="text-slate-500">{{ $subject->title }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class='bx bx-calendar-plus text-emerald-700 text-xl'></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-lg">Create Attendance Sheet</h2>
                    <p class="text-sm text-slate-500">Choose section and date</p>
                </div>
            </div>

            <div class="p-5">
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

                <form method="POST" action="{{ route('teacher.attendance.store', $subject) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Section</label>
                        <select name="section_id" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition-all">
                            <option value="">Select a section...</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" @selected(old('section_id') == $section->id)>
                                    {{ $section->name }} ({{ $section->students_count }} students)
                                </option>
                            @endforeach
                        </select>
                        @if($sections->isEmpty())
                            <p class="text-sm text-slate-500 mt-2">
                                No sections yet. Create a section and add students first.
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Date</label>
                        <input type="date" name="attendance_date" required value="{{ old('attendance_date', now()->toDateString()) }}"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition-all" />
                    </div>

                    <div class="pt-2 flex items-center gap-3">
                        <button type="submit" @disabled($sections->isEmpty())
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg shadow-emerald-500/20 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class='bx bx-check'></i>
                            Create Sheet
                        </button>
                        <a href="{{ route('teacher.attendance.index', $subject) }}"
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-slate-700 hover:bg-slate-100 border border-slate-200 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

