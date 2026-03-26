@extends('layouts.main')
@section('title', 'My Profile - EduPro LMS')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('teacher.dashboard') }}" class="hover:text-indigo-600 transition-colors">
            <i class='bx bx-home'></i>
        </a>
        <i class='bx bx-chevron-right'></i>
        <span class="text-slate-800 font-medium">My Profile</span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 relative">
            <div class="absolute top-0 left-0 w-full h-full bg-black/10"></div>
        </div>
        <div class="px-8 pb-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 -mt-12 mb-6">
                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-xl border-4 border-white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-2xl font-bold text-slate-800">{{ $user->name }}</h1>
                    <p class="text-slate-500 capitalize flex items-center justify-center sm:justify-start gap-2 mt-1">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        {{ $user->role }} • IC: {{ $user->ic }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <span class="px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-sm font-medium border border-emerald-100">
                        <i class='bx bx-check-circle mr-1'></i>
                        Verified
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route(auth()->user()->isTeacher() ? 'teacher.profile.update' : 'student.profile.update') }}" class="space-y-6">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-user text-slate-400'></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                        </div>
                        @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-envelope text-slate-400'></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all">
                        </div>
                        @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">IC Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-id-card text-slate-400'></i>
                            </div>
                            <input type="text" value="{{ $user->ic }}" disabled 
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed">
                        </div>
                        <p class="text-xs text-slate-400">IC number cannot be changed</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-phone text-slate-400'></i>
                            </div>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" placeholder="+60">
                        </div>
                    </div>

                    @if($user->isStudent())
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Form / Class</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-school text-slate-400'></i>
                            </div>
                            <input type="text" name="form_class" value="{{ old('form_class', $user->form_class) }}" 
                                class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition-all" placeholder="e.g. Form 5A">
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-3 rounded-xl font-semibold transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                        <i class='bx bx-save'></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
