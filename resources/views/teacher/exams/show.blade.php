@extends('layouts.main')
@section('title', $exam->title . ' - EduPro LMS')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <a href="{{ route('teacher.exams.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 transition-colors mb-2">
                <i class='bx bx-arrow-back'></i>
                Back to Exams
            </a>
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white">
                    <i class='bx bxs-file text-2xl'></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">{{ $exam->title }}</h1>
                    <p class="text-slate-500 flex items-center gap-3 mt-1">
                        <span class="flex items-center gap-1"><i class='bx bx-book'></i> {{ $exam->subject->title }}</span>
                        <span class="text-slate-300">•</span>
                        <span>{{ $exam->section->name ?? 'All Sections' }}</span>
                        <span class="text-slate-300">•</span>
                        <span>{{ $exam->exam_date?->format('M d, Y h:i A') ?? 'No date set' }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('teacher.exams.toggleMarks', $exam) }}">
                @csrf
                <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium transition-all {{ $exam->marks_released ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200' }}">
                    <i class='bx {{ $exam->marks_released ? 'bx-lock-open' : 'bx-lock-alt' }}'></i>
                    {{ $exam->marks_released ? 'Marks Released' : 'Marks Hidden' }}
                </button>
            </form>
            <form method="POST" action="{{ route('teacher.exams.destroy', $exam) }}" onsubmit="return confirm('Delete this exam? This action cannot be undone.')">
                @csrf @method('DELETE')
                <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-red-600 hover:bg-red-50 border border-red-200 transition-colors">
                    <i class='bx bx-trash'></i>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                <i class='bx bx-list-ol text-indigo-600 text-xl'></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 text-lg">Questions</h2>
                <p class="text-sm text-slate-500">{{ $exam->questions->count() }} questions in this exam</p>
            </div>
        </div>
        <div class="p-5 space-y-4">
            @forelse($exam->questions as $i => $question)
            <div class="border border-slate-200 rounded-xl p-5 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-500/5 transition-all duration-200">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">Q{{ $i + 1 }}</span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase {{ $question->type === 'mcq' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                {{ $question->type }}
                            </span>
                            <span class="text-sm text-slate-500">{{ $question->points }} pts</span>
                        </div>
                        <p class="font-medium text-slate-800 text-lg">{{ $question->question_text }}</p>
                        @if($question->type === 'mcq')
                        <div class="mt-3 space-y-2">
                            @foreach($question->options as $opt)
                            <div class="flex items-center gap-3 py-2 px-3 rounded-lg {{ $opt->is_correct ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-50 text-slate-600' }}">
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $opt->is_correct ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300' }}">
                                    @if($opt->is_correct)
                                    <i class='bx bx-check text-xs'></i>
                                    @endif
                                </div>
                                <span class="font-medium">{{ $opt->option_text }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('teacher.exams.deleteQuestion', [$exam, $question]) }}" onsubmit="return confirm('Delete this question?')">
                        @csrf @method('DELETE')
                        <button class="w-9 h-9 rounded-lg hover:bg-red-50 flex items-center justify-center text-slate-400 hover:text-red-500 transition-all">
                            <i class='bx bx-trash text-lg'></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class='bx bx-question-mark text-slate-400 text-2xl'></i>
                </div>
                <p class="text-slate-500">No questions added yet</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                <i class='bx bx-plus text-purple-600 text-xl'></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 text-lg">Add Question</h2>
                <p class="text-sm text-slate-500">Add a new question to this exam</p>
            </div>
        </div>
        <div class="p-5">
            <form method="POST" action="{{ route('teacher.exams.addQuestion', $exam) }}" class="space-y-4" id="add-question-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Question Type</label>
                        <select name="type" id="q_type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" onchange="toggleOptions()">
                            <option value="mcq">Multiple Choice (MCQ)</option>
                            <option value="subjective">Subjective</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Points</label>
                        <input type="number" name="points" value="1" min="1" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Question Text</label>
                    <textarea name="question_text" rows="2" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all resize-none" placeholder="Enter your question"></textarea>
                </div>

                <div id="options_section" class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700">Options <span class="text-slate-400 font-normal">(select the correct answer)</span></label>
                    <div id="options_list" class="space-y-2">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="correct_option" value="0" checked class="w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                            <input type="text" name="options[0][text]" placeholder="Option A" required class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="radio" name="correct_option" value="1" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                            <input type="text" name="options[1][text]" placeholder="Option B" required class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="radio" name="correct_option" value="2" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                            <input type="text" name="options[2][text]" placeholder="Option C" class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="radio" name="correct_option" value="3" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500">
                            <input type="text" name="options[3][text]" placeholder="Option D" class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                        </div>
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                    <i class='bx bx-plus'></i>
                    Add Question
                </button>
            </form>
        </div>
    </div>

    @if(count($submissions) > 0)
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                <i class='bx bxs-user-check text-emerald-600 text-xl'></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 text-lg">Student Submissions</h2>
                <p class="text-sm text-slate-500">{{ count($submissions) }} students have submitted</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-600 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-600 uppercase">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-600 uppercase">Percentage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($submissions as $sub)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($sub['student']->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-800">{{ $sub['student']->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-slate-700">{{ $sub['earned'] }} / {{ $sub['total'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 bg-slate-200 rounded-full h-2.5 max-w-[140px]">
                                    <div class="h-2.5 rounded-full {{ $sub['percentage'] >= 50 ? 'bg-gradient-to-r from-emerald-500 to-emerald-400' : 'bg-gradient-to-r from-red-500 to-red-400' }}" style="width: {{ $sub['percentage'] }}%"></div>
                                </div>
                                <span class="text-sm font-bold {{ $sub['percentage'] >= 50 ? 'text-emerald-600' : 'text-red-600' }}">{{ $sub['percentage'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleOptions() {
    const isMcq = document.getElementById('q_type').value === 'mcq';
    document.getElementById('options_section').style.display = isMcq ? 'block' : 'none';
    document.querySelectorAll('#options_section input[type="text"]').forEach(el => {
        el.required = isMcq;
    });
}
</script>
@endpush
@endsection
