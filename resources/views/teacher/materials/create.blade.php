@extends('layouts.main')
@section('title', 'Add Material - EduPro LMS')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('teacher.materials.index') }}" class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-1">
            <i class='bx bx-arrow-back'></i>
            Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white">
                    <i class='bx bxs-video text-xl'></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Add Course Material</h1>
                    <p class="text-sm text-slate-500">Upload videos and files for your students</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('teacher.materials.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Subject</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-book text-slate-400'></i>
                        </div>
                        <select name="subject_id" required 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all appearance-none">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->title }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class='bx bx-chevron-down text-slate-400'></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Title</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-heading text-slate-400'></i>
                        </div>
                        <input type="text" name="title" value="{{ old('title') }}" required 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" placeholder="Material title">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Description <span class="text-slate-400 font-normal">(optional)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 pt-4 pointer-events-none">
                            <i class='bx bx-align-left text-slate-400'></i>
                        </div>
                        <textarea name="description" rows="3" 
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all resize-none" placeholder="Brief description of this material">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Video Source</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class='bx bx-link text-slate-400'></i>
                                </div>
                                <input type="url" name="video_url" value="{{ old('video_url') }}" 
                                    class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" placeholder="Video URL (YouTube)">
                            </div>
                            <div class="relative">
                                <label class="flex items-center justify-center w-full h-14 px-4 transition bg-slate-50 border-2 border-slate-200 border-dashed rounded-xl cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/50 transition-all">
                                    <div class="flex items-center gap-2 text-slate-500">
                                        <i class='bx bx-upload text-lg'></i>
                                        <span class="text-sm font-medium">Or upload video file</span>
                                    </div>
                                    <input type="file" name="video_file" accept="video/*" class="hidden">
                                </label>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Max file size: 100MB. Supported formats: mp4, avi, mov, webm</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('teacher.materials.index') }}" class="px-6 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                        <i class='bx bx-plus'></i>
                        Add Material
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
