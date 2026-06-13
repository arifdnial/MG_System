@extends('layouts.main')
@section('title', 'Exams - MGM-System')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">My Exams</h1>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Exam</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($exams as $exam)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $exam->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->subject->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->exam_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($takenExamIds->contains($exam->id))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($takenExamIds->contains($exam->id))
                            <a href="{{ route('student.exams.result', $exam) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Result</a>
                        @else
                            <a href="{{ route('student.exams.take', $exam) }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">Take Exam</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No exams available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
