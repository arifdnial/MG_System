@extends('layouts.main')
@section('title', 'My Marks - EduPro LMS')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">My Marks</h1>
    <p class="text-gray-500 text-sm">Only showing exams where marks have been released by the teacher.</p>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Exam</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Score</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Percentage</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($results as $result)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $result['exam']->title }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $result['exam']->subject->title }}</td>
                    <td class="px-6 py-4 text-sm">{{ $result['earned'] }} / {{ $result['total'] }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[120px]">
                                <div class="h-2 rounded-full {{ $result['percentage'] >= 50 ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ $result['percentage'] }}%"></div>
                            </div>
                            <span class="text-sm font-medium {{ $result['percentage'] >= 50 ? 'text-emerald-600' : 'text-red-600' }}">{{ $result['percentage'] }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('student.exams.result', $result['exam']) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No marks available yet. Marks will appear here once your teacher releases them.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
