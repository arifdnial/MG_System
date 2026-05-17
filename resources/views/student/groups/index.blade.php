@extends('layouts.main')
@section('title', 'Group Activities - EduPro LMS')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Group Activities</h1>
                <p class="text-slate-500 mt-1">Join a group to participate in collaborative activities</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($subjects as $subject)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-indigo-300 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 group">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center text-indigo-600">
                                <i class='bx bx-book text-2xl'></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">{{ $subject->title }}</h3>
                                <p class="text-xs text-slate-500">Teacher: {{ $subject->teacher->name }}</p>
                            </div>
                        </div>

                        @php
                            $userGroup = auth()->user()->studyGroups()->where('subject_id', $subject->id)->first();
                        @endphp

                        <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between">
                            @if($userGroup)
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-emerald-600 uppercase">My Group</span>
                                    <span class="text-sm font-semibold text-slate-700">{{ $userGroup->name }}</span>
                                </div>
                                <a href="{{ route('student.subjects.groups.index', $subject) }}" 
                                   class="px-4 py-2 bg-slate-100 hover:bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 transition-all">
                                    Manage
                                </a>
                            @else
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Status</span>
                                    <span class="text-sm font-semibold text-slate-400 italic">No Group Joined</span>
                                </div>
                                <a href="{{ route('student.subjects.groups.index', $subject) }}" 
                                   class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all shadow-lg shadow-indigo-500/20">
                                    Join Group
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class='bx bx-group text-slate-300 text-3xl'></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">No subjects found</h3>
                        <p class="text-slate-500">You need to be enrolled in a subject to join groups.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
