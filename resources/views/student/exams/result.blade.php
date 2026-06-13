@extends('layouts.main')
@section('title', 'Exam Result - MGM-System')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('student.exams.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">← Back to Exams</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $exam->title }} — Result</h1>
        <p class="text-gray-500 text-sm">{{ $exam->subject->title }}</p>
    </div>

    {{-- Score Summary --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full {{ $percentage >= 50 ? 'bg-emerald-100' : 'bg-red-100' }} mb-4">
                <span class="text-3xl font-bold {{ $percentage >= 50 ? 'text-emerald-600' : 'text-red-600' }}">{{ $percentage }}%</span>
            </div>
            <p class="text-lg font-semibold text-gray-900">{{ $earnedPoints }} / {{ $totalPoints }} points</p>
            <p class="text-sm text-gray-500 mt-1">
                @if(!$exam->marks_released)
                    <span class="text-amber-600">⚠ Marks not yet released by teacher</span>
                @else
                    {{ $percentage >= 80 ? 'Excellent!' : ($percentage >= 50 ? 'Good job!' : 'Keep practicing!') }}
                @endif
            </p>
        </div>
    </div>

    {{-- Question Review --}}
    @if($exam->marks_released)
    @foreach($exam->questions as $i => $question)
    @php $answer = $answers[$question->id] ?? null; @endphp
    <div class="bg-white rounded-2xl shadow-sm border {{ $answer && $answer->is_correct ? 'border-emerald-200' : ($answer && $answer->is_correct === false ? 'border-red-200' : 'border-gray-100') }} p-6">
        <div class="flex items-start gap-3">
            <span class="text-sm font-bold w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                {{ $answer && $answer->is_correct ? 'bg-emerald-100 text-emerald-700' : ($answer && $answer->is_correct === false ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500') }}">
                {{ $i + 1 }}
            </span>
            <div class="flex-1">
                <p class="font-medium text-gray-900">{{ $question->question_text }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $question->points }} pts •
                    @if($answer) {{ $answer->points_earned }}/{{ $question->points }} earned @else Not answered @endif
                </p>

                @if($question->type === 'mcq')
                <div class="mt-3 space-y-1.5">
                    @foreach($question->options as $option)
                    <div class="flex items-center gap-2 p-2 rounded-lg text-sm
                        {{ $option->is_correct ? 'bg-emerald-50 text-emerald-700' : '' }}
                        {{ $answer && $answer->selected_option_id == $option->id && !$option->is_correct ? 'bg-red-50 text-red-700' : '' }}">
                        @if($option->is_correct) ✓
                        @elseif($answer && $answer->selected_option_id == $option->id) ✗
                        @else ○
                        @endif
                        {{ $option->option_text }}
                    </div>
                    @endforeach
                </div>
                @else
                <div class="mt-3 p-3 bg-gray-50 rounded-lg text-sm text-gray-700">
                    {{ $answer->answer_text ?? 'No answer provided' }}
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
