@extends('layouts.main')
@section('title', 'Manage Groups - ' . $subject->title)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between font-bold text-gray-900 border-b border-gray-100 pb-4">
            <div>
                <a href="{{ route('teacher.subjects.show', $subject) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">← Back to Subject</a>
                <h1 class="text-2xl mt-1">Manage Groups: {{ $subject->title }}</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Create Group Form --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                <h2 class="font-bold text-gray-900 mb-4">Create New Group</h2>
                <form action="{{ route('teacher.subjects.groups.store', $subject) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Group Name</label>
                        <input type="text" name="name" required placeholder="e.g. Group Alpha"
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Students</label>
                        <input type="number" name="max_students" required value="5" min="1"
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-indigo-200">
                        Create Group
                    </button>
                </form>
            </div>

            {{-- Groups List --}}
            <div class="lg:col-span-2 space-y-4">
                @forelse($groups as $group)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ $group->name }}</h3>
                            <div class="flex items-center gap-4 mt-1 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <i class='bx bx-user'></i> {{ $group->students_count }} / {{ $group->max_students }} Students
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('teacher.groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Delete this group?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-lg transition-all">
                                    <i class='bx bx-trash text-xl'></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-12 text-center">
                        <p class="text-gray-400 font-bold">No groups created yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
