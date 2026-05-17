@extends('layouts.main')
@section('title', $subject->title . ' - EduPro LMS')
@section('content')
    <div class="space-y-6">
        <div class="flex items-start justify-between gap-4">
            <a href="{{ route('student.subjects.index') }}"
                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">← Back to Subjects</a>
            <div class="text-right">
                <a href="{{ route('student.attendance.subject', $subject) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-emerald-700 hover:bg-emerald-50 border border-emerald-200 transition-colors">
                    <i class='bx bx-calendar-check'></i>
                    My Attendance
                </a>
                <a href="{{ route('student.attendance.scan') }}"
                    class="ml-2 inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-indigo-700 hover:bg-indigo-50 border border-indigo-200 transition-colors">
                    <i class='bx bx-qr-scan'></i>
                    Scan QR
                </a>
            </div>
        </div>
        <div>
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
                                <a href="{{ $ann->video_link }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:underline mt-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg> Watch Video
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
                            <div class="grid grid-cols-1 gap-2 mt-2">
                                @if($material->video_url)
                                    <a href="{{ $material->video_url }}" target="_blank"
                                        class="text-sm text-indigo-600 hover:underline flex items-center gap-1">
                                        <i class='bx bxl-youtube'></i> Watch Video
                                    </a>
                                @endif
                                @if($material->file_path)
                                    <a href="{{ Storage::url($material->file_path) }}" target="_blank"
                                        class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                                        <i class='bx bx-video'></i> Video File
                                    </a>
                                @endif
                                @if($material->document_path)
                                    <a href="{{ Storage::url($material->document_path) }}" target="_blank"
                                        class="text-sm text-emerald-600 hover:underline flex items-center gap-1">
                                        <i class='bx bx-file'></i> View Document (PDF/Image/Doc)
                                    </a>
                                @endif
                            </div>

                            {{-- Submission Logic --}}
                            @php
                                $submission = \App\Models\MaterialSubmission::where('course_material_id', $material->id)
                                    ->where('student_id', auth()->id())
                                    ->first();
                            @endphp
                            
                            <div class="mt-4 pt-4 border-t border-gray-50">
                                @if($submission)
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2 text-emerald-600 font-bold uppercase">
                                            <i class='bx bxs-check-circle'></i> Submitted
                                        </div>
                                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank" 
                                           class="text-indigo-600 hover:underline">View My Submission</a>
                                    </div>
                                    <div class="mt-1 text-[10px] text-gray-400">
                                        Updated: {{ $submission->updated_at->diffForHumans() }}
                                    </div>

                                    {{-- Teacher Feedback Box --}}
                                    @if($submission->teacher_comment)
                                        <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                                            <div class="flex items-center gap-1.5 text-amber-700 font-bold text-xs mb-1">
                                                <i class='bx bx-comment-detail'></i>
                                                Teacher Feedback
                                            </div>
                                            <p class="text-sm text-amber-800">{{ $submission->teacher_comment }}</p>
                                            <p class="text-[10px] text-amber-500 mt-1">{{ $submission->commented_at?->diffForHumans() }}</p>
                                            <p class="text-[10px] text-indigo-500 mt-2 font-medium">📎 You can resubmit below after reviewing the feedback.</p>
                                        </div>
                                    @endif
                                @endif

                                <form action="{{ route('student.materials.submit', $material) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                    @csrf
                                    <div class="flex items-center gap-2">
                                        <label class="flex-1 cursor-pointer">
                                            <div class="bg-gray-50 border border-dashed border-gray-200 rounded-lg py-2 px-3 text-gray-500 hover:bg-gray-100 transition-all text-xs flex items-center justify-center gap-2">
                                                <i class='bx bx-cloud-upload text-sm'></i>
                                                {{ $submission ? ($submission->teacher_comment ? '🔄 Resubmit with changes' : 'Re-submit activity') : 'Submit activity' }}
                                                <input type="file" name="submission_file" class="hidden" onchange="this.form.submit()">
                                            </div>
                                        </label>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1">Accepts PDF, PNG, JPG, DOC, ZIP (Max 20MB)</p>
                                </form>
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