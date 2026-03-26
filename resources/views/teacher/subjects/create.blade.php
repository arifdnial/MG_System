@extends('layouts.main')
@section('title', 'New Subject - EduPro LMS')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('teacher.subjects.index') }}" class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-1">
            <i class='bx bx-arrow-back'></i>
            Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-purple-50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white">
                    <i class='bx bxs-book-alt text-xl'></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Create New Subject</h1>
                    <p class="text-sm text-slate-500">Add a new subject to your teaching portfolio</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('teacher.subjects.store') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-semibold text-slate-700">Subject Title</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-book text-slate-400'></i>
                        </div>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" placeholder="e.g. Mathematics">
                    </div>
                    @error('title') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 pt-4 pointer-events-none">
                            <i class='bx bx-align-left text-slate-400'></i>
                        </div>
                        <textarea name="description" id="description" rows="4" 
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all resize-none" placeholder="Describe what this subject covers...">{{ old('description') }}</textarea>
                    </div>
                    <p class="text-xs text-slate-400">Brief description helps students understand the subject content</p>
                </div>
                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('teacher.subjects.index') }}" class="px-6 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                        <i class='bx bx-check'></i>
                        Create Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
