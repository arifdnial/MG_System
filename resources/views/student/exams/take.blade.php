@extends('layouts.main')
@section('title', $exam->title . ' - Take Exam - EduPro LMS')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('student.exams.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">← Back to Exams</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $exam->title }}</h1>
        <p class="text-gray-500 text-sm">{{ $exam->subject->title }} • {{ $exam->questions->count() }} questions • {{ $exam->questions->sum('points') }} total points</p>
    </div>

    <form method="POST" action="{{ route('student.exams.submit', $exam) }}" class="space-y-4">
        @csrf
        @foreach($exam->questions as $i => $question)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start gap-3">
                <span class="bg-indigo-100 text-indigo-700 text-sm font-bold w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ $question->question_text }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $question->points }} point{{ $question->points > 1 ? 's' : '' }} • {{ strtoupper($question->type) }}</p>

                    @if($question->type === 'mcq')
                    <div class="mt-3 space-y-2">
                        @foreach($question->options as $option)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50 transition cursor-pointer">
                            <input type="radio" name="answers[{{ $question->id }}][option]" value="{{ $option->id }}"
                                {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id]->selected_option_id == $option->id ? 'checked' : '' }}
                                class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                        </label>
                        @endforeach
                    </div>
                    @else
                    <textarea name="answers[{{ $question->id }}][text]" rows="4" class="mt-3 w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Type your answer here...">{{ $existingAnswers[$question->id]->answer_text ?? '' }}</textarea>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" onclick="return confirm('Are you sure you want to submit? You may not be able to change your answers.')" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-semibold py-3 px-8 rounded-xl transition-all shadow-md hover:shadow-lg">
                Submit Exam
            </button>
        </div>
    </form>
</div>
@endsection
