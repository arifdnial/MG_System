@extends('layouts.main')
@section('title', 'Submissions - ' . $material->title)

@section('content')
    <div class="space-y-6">
        <div>
            <a href="{{ route('teacher.materials.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">← Back to Materials</a>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Submissions: {{ $material->title }}</h1>
            <p class="text-gray-500">{{ $material->subject->title }}</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2">
                <i class='bx bxs-check-circle text-emerald-500'></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h2 class="font-bold text-gray-900">Student Submission Status</h2>
                <div class="flex gap-4 text-sm">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Submitted</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-400"></span> Pending</span>
                </div>
            </div>
            
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-sm font-semibold text-gray-600 border-b border-gray-100">
                        <th class="p-5">Student Name</th>
                        <th class="p-5">IC Number</th>
                        <th class="p-5">Status</th>
                        <th class="p-5">Submitted At</th>
                        <th class="p-5">Feedback</th>
                        <th class="p-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($enrolledStudents as $student)
                        @php $sub = $submissions->get($student->id); @endphp
                        <tr class="hover:bg-gray-50 transition-colors align-top">
                            <td class="p-5">
                                <div class="font-medium text-gray-900">{{ $student->name }}</div>
                            </td>
                            <td class="p-5 text-gray-500">{{ $student->ic }}</td>
                            <td class="p-5">
                                @if($sub)
                                    <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-bold uppercase">Submitted</span>
                                @else
                                    <span class="px-2 py-1 rounded-lg bg-red-100 text-red-600 text-xs font-bold uppercase">Missing</span>
                                @endif
                            </td>
                            <td class="p-5 text-gray-500 text-sm">
                                {{ $sub ? $sub->updated_at->format('d M Y, h:i A') : '-' }}
                            </td>
                            <td class="p-5 max-w-xs">
                                @if($sub)
                                    @if($sub->teacher_comment)
                                        <div class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-2">
                                            <span class="font-bold block mb-1">💬 Your comment:</span>
                                            {{ $sub->teacher_comment }}
                                            <span class="block text-[10px] text-amber-500 mt-1">{{ $sub->commented_at?->diffForHumans() }}</span>
                                        </div>
                                    @endif
                                    {{-- Comment form --}}
                                    <form action="{{ route('teacher.submissions.comment', $sub) }}" method="POST" class="space-y-1">
                                        @csrf
                                        <textarea name="teacher_comment" rows="2"
                                            placeholder="{{ $sub->teacher_comment ? 'Update comment...' : 'Add feedback...' }}"
                                            class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"
                                        >{{ old('teacher_comment', $sub->teacher_comment) }}</textarea>
                                        <button type="submit"
                                            class="text-[11px] font-bold bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-lg transition-colors">
                                            {{ $sub->teacher_comment ? '✏️ Update' : '💬 Comment' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-300 text-sm">-</span>
                                @endif
                            </td>
                            <td class="p-5 text-right">
                                @if($sub)
                                    <a href="{{ Storage::url($sub->file_path) }}" target="_blank" 
                                       class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-900 font-semibold px-3 py-1 rounded-lg border border-indigo-200 hover:bg-indigo-50">
                                        <i class='bx bx-download'></i> Download
                                    </a>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
