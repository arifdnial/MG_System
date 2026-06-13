@extends('layouts.main')
@section('title', 'My Subjects - MGM-System')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">My Subjects</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($subjects as $subject)
        <a href="{{ route('student.subjects.show', $subject) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all group">
            <h3 class="font-bold text-lg text-gray-900 group-hover:text-indigo-600">{{ $subject->title }}</h3>
            <p class="text-gray-500 text-sm mt-1">Teacher: {{ $subject->teacher->name }}</p>
            <div class="flex gap-4 mt-4 text-xs text-gray-400">
                <span>{{ $subject->announcements->count() }} announcements</span>
                <span>{{ $subject->courseMaterials->count() }} materials</span>
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">You are not enrolled in any subjects yet. Ask your teacher to add you.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
