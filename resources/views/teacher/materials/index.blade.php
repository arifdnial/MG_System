@extends('layouts.main')
@section('title', 'Course Materials - MGM-System')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Course Materials</h1>
                <p class="text-slate-500 mt-1">Share learning resources with your students</p>
            </div>
            <a href="{{ route('teacher.materials.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-5 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                <i class='bx bx-plus'></i>
                Add Material
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($materials as $material)
                <div
                    class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <span
                                class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                                <i class='bx bx-book mr-1'></i>
                                {{ $material->subject->title }}
                            </span>
                        </div>
                        <form method="POST" action="{{ route('teacher.materials.destroy', $material) }}"
                            onsubmit="return confirm('Delete this material?')">
                            @csrf @method('DELETE')
                            <button
                                class="w-8 h-8 rounded-lg hover:bg-red-50 flex items-center justify-center text-slate-400 hover:text-red-500 transition-all">
                                <i class='bx bx-trash text-lg'></i>
                            </button>
                        </form>
                    </div>

                    <h3 class="font-bold text-slate-800 text-lg group-hover:text-indigo-600 transition-colors">
                        {{ $material->title }}
                    </h3>
                    @if($material->is_group_activity)
                        <div class="flex items-center gap-1 mt-1 mb-2">
                            <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider border border-indigo-100">
                                <i class='bx bxs-group'></i> Group Activity
                            </span>
                        </div>
                    @endif

                    @if($material->description)
                        <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ $material->description }}</p>
                    @endif

                    <div class="grid grid-cols-1 gap-3 pt-4 border-t border-slate-100">
                        @if($material->video_url)
                            <a href="{{ $material->video_url }}" target="_blank"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                                <i class='bx bxl-youtube text-lg'></i>
                                Watch Video
                            </a>
                        @endif
                        @if($material->file_path)
                            <a href="{{ Storage::url($material->file_path) }}" target="_blank"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                <i class='bx bx-video text-lg'></i>
                                Video File
                            </a>
                        @endif
                        @if($material->document_path)
                            <a href="{{ Storage::url($material->document_path) }}" target="_blank"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition-colors">
                                <i class='bx bx-file text-lg'></i>
                                View Document
                            </a>
                        @endif
                        <a href="{{ route('teacher.materials.submissions', $material) }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-purple-600 bg-purple-50 hover:bg-purple-100 transition-colors mt-2">
                            <i class='bx bx-list-check text-lg'></i>
                            View Submissions
                        </a>
                    </div>

                    <div class="flex items-center gap-2 mt-4 text-xs text-slate-400">
                        <i class='bx bx-time-five'></i>
                        <span>{{ $material->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-slate-100 to-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <i class='bx bxs-video text-slate-400 text-3xl'></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">No materials yet</h3>
                        <p class="text-slate-500 mb-6 max-w-sm mx-auto">Upload videos and files to help your students learn</p>
                        <a href="{{ route('teacher.materials.create') }}"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25">
                            <i class='bx bx-plus'></i>
                            Add Your First Material
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection