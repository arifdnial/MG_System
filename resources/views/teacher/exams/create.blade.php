@extends('layouts.main')
@section('title', 'Create Exam - MGM-System')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('teacher.exams.index') }}" class="text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-1">
            <i class='bx bx-arrow-back'></i>
            Back
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-amber-50 to-orange-50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white">
                    <i class='bx bxs-file text-xl'></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Create New Exam</h1>
                    <p class="text-sm text-slate-500">Set up a new exam for your students</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('teacher.exams.store') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Exam Title</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-heading text-slate-400'></i>
                        </div>
                        <input type="text" name="title" value="{{ old('title') }}" required 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" placeholder="e.g. Mid-Term Test Chapter 1-3">
                    </div>
                    @error('title') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Subject</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-book text-slate-400'></i>
                            </div>
                            <select name="subject_id" id="subject_select" required 
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all appearance-none" onchange="updateSections()">
                                <option value="">Select subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" data-sections='@json($subject->sections)'>{{ $subject->title }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i class='bx bx-chevron-down text-slate-400'></i>
                            </div>
                        </div>
                        @error('subject_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Section (optional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-group text-slate-400'></i>
                            </div>
                            <select name="section_id" id="section_select" 
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all appearance-none">
                                <option value="">All sections</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i class='bx bx-chevron-down text-slate-400'></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Exam Date</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-calendar text-slate-400'></i>
                        </div>
                        <input type="datetime-local" name="exam_date" value="{{ old('exam_date') }}" 
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Description</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 pt-4 pointer-events-none">
                            <i class='bx bx-align-left text-slate-400'></i>
                        </div>
                        <textarea name="description" rows="3" 
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all resize-none" placeholder="Optional description">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('teacher.exams.index') }}" class="px-6 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                        <i class='bx bx-check'></i>
                        Create Exam
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateSections() {
    const select = document.getElementById('subject_select');
    const sectionSelect = document.getElementById('section_select');
    const option = select.options[select.selectedIndex];
    const sections = JSON.parse(option.getAttribute('data-sections') || '[]');

    sectionSelect.innerHTML = '<option value="">All sections</option>';
    sections.forEach(s => {
        sectionSelect.innerHTML += `<option value="${s.id}">${s.name}</option>`;
    });
}
</script>
@endpush
@endsection
