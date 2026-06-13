@extends('layouts.main')
@section('title', 'Edit Question - MGM-System')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('teacher.exams.show', $exam) }}" class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 italic">Edit Question</h1>
            <p class="text-slate-500">{{ $exam->title }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                <i class='bx bx-edit text-indigo-600 text-xl'></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-800">Update Question Details</h2>
                <p class="text-xs text-slate-500 font-medium">Modify the question text, type, and points</p>
            </div>
        </div>
        <div class="p-8">
            <form method="POST" action="{{ route('teacher.exams.updateQuestion', [$exam, $question]) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Question Type</label>
                        <select name="type" id="q_type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all font-medium" onchange="toggleOptions()">
                            <option value="mcq" {{ $question->type === 'mcq' ? 'selected' : '' }}>Multiple Choice (MCQ)</option>
                            <option value="subjective" {{ $question->type === 'subjective' ? 'selected' : '' }}>Subjective</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Points</label>
                        <input type="number" name="points" value="{{ $question->points }}" min="1" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Question Text</label>
                    <textarea name="question_text" rows="3" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all resize-none font-medium text-slate-800" placeholder="Enter your question">{{ $question->question_text }}</textarea>
                </div>

                <div id="options_section" class="space-y-4 pt-4 border-t border-slate-100 {{ $question->type !== 'mcq' ? 'hidden' : '' }}">
                    <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Options <span class="text-slate-400 font-normal lowercase">(select the correct answer)</span></label>
                    <div id="options_list" class="space-y-3">
                        @php $options = $question->options; @endphp
                        @for($i = 0; $i < 4; $i++)
                        @php $opt = $options[$i] ?? null; @endphp
                        <div class="flex items-center gap-4 group">
                            <div class="relative flex items-center justify-center">
                                <input type="radio" name="correct_option" value="{{ $i }}" {{ ($opt && $opt->is_correct) || (!$opt && $i === 0) ? 'checked' : '' }} class="w-6 h-6 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            </div>
                            <input type="text" name="options[{{ $i }}][text]" value="{{ $opt ? $opt->option_text : '' }}" placeholder="Option {{ chr(65 + $i) }}" {{ $question->type === 'mcq' ? 'required' : '' }} class="flex-1 px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all group-hover:border-slate-300">
                        </div>
                        @endfor
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-6">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold px-8 py-4 rounded-xl transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                        <i class='bx bx-check-circle text-xl'></i>
                        Save Changes
                    </button>
                    <a href="{{ route('teacher.exams.show', $exam) }}" class="px-8 py-4 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleOptions() {
    const isMcq = document.getElementById('q_type').value === 'mcq';
    const section = document.getElementById('options_section');
    if (isMcq) {
        section.classList.remove('hidden');
    } else {
        section.classList.add('hidden');
    }
    
    document.querySelectorAll('#options_section input[type="text"]').forEach(el => {
        el.required = isMcq;
    });
}
</script>
@endpush
@endsection
