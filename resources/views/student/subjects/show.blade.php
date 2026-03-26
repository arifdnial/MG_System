@extends('layouts.main')
@section('title', $subject->title . ' - EduPro LMS')
@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('student.subjects.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">← Back to Subjects</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $subject->title }}</h1>
        <p class="text-gray-500">Teacher: {{ $subject->teacher->name }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Announcements --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <h2 class="font-bold text-gray-900">Announcements</h2>
            </div>
            <div class="p-5 space-y-3">
                @forelse($subject->announcements as $ann)
                <div class="p-4 rounded-xl border border-gray-100">
                    <h3 class="font-semibold text-gray-900">{{ $ann->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $ann->content }}</p>
                    @if($ann->video_link)
                    <a href="{{ $ann->video_link }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:underline mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg> Watch Video
                    </a>
                    @endif
                    <p class="text-xs text-gray-400 mt-2">{{ $ann->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-gray-400 text-sm text-center py-4">No announcements yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Course Materials --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <h2 class="font-bold text-gray-900">Course Materials</h2>
            </div>
            <div class="p-5 space-y-3">
                @forelse($subject->courseMaterials as $material)
                <div class="p-4 rounded-xl border border-gray-100">
                    <h3 class="font-semibold text-gray-900">{{ $material->title }}</h3>
                    @if($material->description)
                    <p class="text-sm text-gray-600 mt-1">{{ $material->description }}</p>
                    @endif
                    <div class="flex gap-3 mt-2">
                        @if($material->video_url)
                        <a href="{{ $material->video_url }}" target="_blank" class="text-sm text-indigo-600 hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg> Watch Video
                        </a>
                        @endif
                        @if($material->file_path)
                        <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="text-sm text-emerald-600 hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Download
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm text-center py-4">No materials yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
