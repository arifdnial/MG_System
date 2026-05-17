@extends('layouts.main')
@section('title', 'Select Group - ' . $subject->title)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div>
                <a href="{{ route('student.groups.index') }}" class="text-indigo-600 hover:underline text-sm font-medium">← All Group Activities</a>
                <h1 class="text-2xl font-bold text-slate-800 mt-1">Study Groups: {{ $subject->title }}</h1>
            </div>
        </div>

        @if($userGroup)
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl p-6 text-white shadow-lg shadow-emerald-500/20 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <i class='bx bxs-group text-3xl'></i>
                        </div>
                        <div>
                            <p class="text-emerald-50 opacity-80 text-xs font-bold uppercase tracking-wider">Currently Joined</p>
                            <h2 class="text-2xl font-bold">{{ $userGroup->name }}</h2>
                        </div>
                    </div>
                    <form action="{{ route('student.groups.leave', $userGroup) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="px-6 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl text-sm font-bold transition-all"
                                onclick="return confirm('Are you sure you want to leave this group?')">
                            Leave Group
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($groups as $group)
                <div class="bg-white rounded-2xl border {{ $userGroup && $userGroup->id == $group->id ? 'border-emerald-500 shadow-lg shadow-emerald-500/5' : 'border-slate-200' }} p-6 transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="font-bold text-slate-800 text-lg">{{ $group->name }}</h3>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $group->students_count >= $group->max_students ? 'bg-red-50 text-red-600' : 'bg-indigo-50 text-indigo-600' }}">
                            {{ $group->students_count }} / {{ $group->max_students }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($group->students as $student)
                            <div class="w-8 h-8 rounded-full bg-slate-100 border border-white flex items-center justify-center text-[10px] font-bold text-slate-600 ring-2 ring-white" title="{{ $student->name }}">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                        @endforeach
                        @for($i = 0; $i < ($group->max_students - $group->students_count); $i++)
                            <div class="w-8 h-8 rounded-full bg-slate-50 border border-dashed border-slate-200 flex items-center justify-center text-slate-300">
                                <i class='bx bx-plus text-xs'></i>
                            </div>
                        @endfor
                    </div>

                    @if(!$userGroup)
                        <form action="{{ route('student.groups.join', $group) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full py-3 rounded-xl font-bold text-sm transition-all {{ $group->students_count >= $group->max_students ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-500/20' }}"
                                    {{ $group->students_count >= $group->max_students ? 'disabled' : '' }}>
                                {{ $group->students_count >= $group->max_students ? 'Full' : 'Join Group' }}
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-12 text-center">
                        <p class="text-slate-400 font-bold">No groups created by teacher yet.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
