@extends('layouts.main')
@section('title', 'Group Submissions - ' . $material->title)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div>
                <a href="{{ route('teacher.materials.index') }}" class="text-indigo-600 hover:underline text-sm font-medium">← Back to Materials</a>
                <h1 class="text-2xl font-bold text-slate-800 mt-1">Group Submissions: {{ $material->title }}</h1>
                <p class="text-slate-500 text-sm italic">Status: {{ $material->subject->title }} - Group Activity</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2">
                <i class='bx bxs-check-circle text-emerald-500'></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($groups as $group)
                @php
                    $submission = $submissions->get($group->id);
                @endphp
                <div class="bg-white rounded-2xl border {{ $submission ? 'border-emerald-200 bg-emerald-50/10' : 'border-slate-200' }} p-6 transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">{{ $group->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $group->students->count() }} Members</p>
                        </div>
                        @if($submission)
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold uppercase">Submitted</span>
                        @else
                            <span class="px-3 py-1 bg-slate-100 text-slate-400 rounded-full text-[10px] font-bold uppercase">Pending</span>
                        @endif
                    </div>

                    <div class="flex -space-x-2 mb-6 overflow-hidden">
                        @foreach($group->students as $student)
                            <div class="w-8 h-8 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-slate-600" title="{{ $student->name }}">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                        @endforeach
                    </div>

                    @if($submission)
                        <div class="space-y-3">
                            <div class="p-3 bg-white rounded-xl border border-emerald-100 text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-slate-400 italic font-medium">Uploaded by:</span>
                                    <span class="text-slate-700 font-bold">{{ $submission->student?->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 italic font-medium">Time:</span>
                                    <span class="text-slate-700 font-bold">{{ $submission->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            {{-- Existing Comment Display --}}
                            @if($submission->teacher_comment)
                                <div class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2">
                                    <span class="font-bold block mb-1">💬 Your comment:</span>
                                    {{ $submission->teacher_comment }}
                                    <span class="block text-[10px] text-amber-500 mt-1">{{ $submission->commented_at?->diffForHumans() }}</span>
                                </div>
                            @endif

                            {{-- Comment Form --}}
                            <form action="{{ route('teacher.submissions.comment', $submission) }}" method="POST" class="space-y-2">
                                @csrf
                                <textarea name="teacher_comment" rows="2"
                                    placeholder="{{ $submission->teacher_comment ? 'Update feedback...' : 'Add feedback for this group...' }}"
                                    class="w-full text-xs border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"
                                >{{ old('teacher_comment', $submission->teacher_comment) }}</textarea>
                                <button type="submit"
                                    class="w-full text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl transition-all shadow-sm">
                                    {{ $submission->teacher_comment ? '✏️ Update Comment' : '💬 Add Comment' }}
                                </button>
                            </form>

                            <a href="{{ Storage::url($submission->file_path) }}" target="_blank" 
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-emerald-500/20">
                                <i class='bx bx-desktop text-lg'></i>
                                View Submission
                            </a>
                        </div>
                    @else
                        <div class="py-8 text-center text-slate-300 italic text-sm">
                            <i class='bx bx-help-circle text-2xl mb-1'></i>
                            <p>No submission yet</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
