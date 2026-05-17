@extends('layouts.main')
@section('title', $subject->title . ' - EduPro LMS')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1">
                <a href="{{ route('teacher.subjects.index') }}"
                    class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 transition-colors mb-2">
                    <i class='bx bx-arrow-back'></i>
                    Back to Subjects
                </a>
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($subject->title, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">{{ $subject->title }}</h1>
                        <p class="text-slate-500">{{ $subject->description ?? 'No description' }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('teacher.attendance.index', $subject) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-emerald-700 hover:bg-emerald-50 border border-emerald-200 transition-colors">
                    <i class='bx bx-calendar-check'></i>
                    Attendance
                </a>
                <a href="{{ route('teacher.subjects.groups.index', $subject) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-indigo-600 hover:bg-indigo-50 border border-indigo-200 transition-colors">
                    <i class='bx bxs-group'></i>
                    Manage Groups
                </a>
                <form method="POST" action="{{ route('teacher.subjects.destroy', $subject) }}"
                    onsubmit="return confirm('Delete this subject and all its data? This action cannot be undone.')">
                    @csrf @method('DELETE')
                    <button
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-red-600 hover:bg-red-50 border border-red-200 transition-colors">
                        <i class='bx bx-trash'></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <i class='bx bx-section text-indigo-600 text-xl'></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-lg">Sections</h2>
                    <p class="text-sm text-slate-500">Manage student sections for this subject</p>
                </div>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('teacher.sections.store', $subject) }}" class="flex gap-3 mb-6">
                    @csrf
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-user-plus text-slate-400'></i>
                        </div>
                        <input type="text" name="name" placeholder="e.g. Form 1A, Form 2B" required
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                    </div>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25">
                        <i class='bx bx-plus'></i>
                    </button>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($subject->sections as $section)
                        <div
                            class="border border-slate-200 rounded-xl p-5 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-500/5 transition-all duration-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                                        <i class='bx bxs-graduation text-indigo-600'></i>
                                    </div>
                                    <h3 class="font-bold text-slate-800">{{ $section->name }}</h3>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-sm font-medium">
                                        {{ $section->students->count() }} students
                                    </span>
                                    <form method="POST" action="{{ route('teacher.sections.destroy', [$subject, $section]) }}"
                                        onsubmit="return confirm('Delete this section? All student enrollments for this section will be removed.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-all duration-200">
                                            <i class='bx bx-trash text-sm'></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4 max-h-40 overflow-y-auto">
                                @forelse($section->students as $student)
                                    <div class="flex items-center justify-between py-2 px-3 rounded-lg bg-slate-50">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-6 h-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </div>
                                            <span class="text-sm text-slate-700">{{ $student->name }}</span>
                                            <span class="text-xs text-slate-400">({{ $student->ic }})</span>
                                        </div>
                                        <form method="POST"
                                            action="{{ route('teacher.sections.removeStudent', [$subject, $section, $student]) }}">
                                            @csrf @method('DELETE')
                                            <button
                                                class="w-6 h-6 rounded-full hover:bg-red-100 flex items-center justify-center text-slate-400 hover:text-red-500 transition-all">
                                                <i class='bx bx-x text-sm'></i>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-400 text-center py-2">No students enrolled</p>
                                @endforelse
                            </div>

                            <form method="POST" action="{{ route('teacher.sections.addStudent', [$subject, $section]) }}"
                                class="flex gap-2">
                                @csrf
                                <select name="student_id" required
                                    class="flex-1 text-sm border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Add student...</option>
                                    @foreach($allStudents->whereNotIn('id', $section->students->pluck('id')) as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->ic }})</option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors">
                                    <i class='bx bx-plus'></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                @if($subject->sections->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class='bx bx-user-plus text-slate-400 text-2xl'></i>
                        </div>
                        <p class="text-slate-500">No sections created yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection