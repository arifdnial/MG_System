@extends('layouts.main')
@section('title', 'Login - MGM-System')

@section('content')
<div class="min-h-screen flex">
    {{-- Left Side - Branding --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-500/20 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>
        </div>
        <div class="relative z-10 flex flex-col justify-center items-center w-full p-12 text-white">
            <div class="w-24 h-24 bg-white/20 backdrop-blur-xl rounded-3xl flex items-center justify-center shadow-2xl mb-8">
                <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h1 class="text-5xl font-bold mb-4 text-center">Markaz Ghazali Management System</h1>
            
            <div class="mt-12 grid grid-cols-3 gap-6 text-center">
                <div class="p-4 bg-white/10 rounded-2xl backdrop-blur-sm">
                    <div class="text-3xl font-bold">📚</div>
                    <div class="text-sm text-indigo-200 mt-1">Courses</div>
                </div>
                <div class="p-4 bg-white/10 rounded-2xl backdrop-blur-sm">
                    <div class="text-3xl font-bold">📝</div>
                    <div class="text-sm text-indigo-200 mt-1">Exams</div>
                </div>
                <div class="p-4 bg-white/10 rounded-2xl backdrop-blur-sm">
                    <div class="text-3xl font-bold">📊</div>
                    <div class="text-sm text-indigo-200 mt-1">Results</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side - Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-slate-50">
        <div class="w-full max-w-md">
            {{-- Mobile Logo --}}
            <div class="lg:hidden text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-xl mb-4">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">MGM-System</h1>
            </div>

            {{-- Flash --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('debug_reset_url'))
                <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl text-sm break-all">
                    <strong>Debug:</strong> <a href="{{ session('debug_reset_url') }}" class="underline">{{ session('debug_reset_url') }}</a>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome back</h2>
                <p class="text-gray-500 mb-8">Sign in to continue to your account</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                  {{-- Role Selection --}}
<div>
    <label class="block text-sm font-semibold text-gray-700 mb-3">Login As</label>
    <div class="grid grid-cols-3 gap-3">
                      {{-- Admin --}}
                      <label class="relative cursor-pointer">
                          <input type="radio" name="role" value="admin" class="peer sr-only" {{ old('role') === 'admin' ? 'checked' : '' }}>
                          <div class="p-4 rounded-2xl border-2 border-gray-200 text-center transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:ring-2 peer-checked:ring-amber-500 peer-checked:scale-105 hover:border-amber-300">
                              <svg class="w-6 h-6 mx-auto mb-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                              <span class="text-sm font-semibold text-gray-700 capitalize">admin</span>
                          </div>
                      </label>
                      {{-- Teacher --}}
                      <label class="relative cursor-pointer">
                          <input type="radio" name="role" value="teacher" class="peer sr-only" {{ old('role') === 'teacher' ? 'checked' : '' }}>
                          <div class="p-4 rounded-2xl border-2 border-gray-200 text-center transition-all peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:ring-2 peer-checked:ring-indigo-500 peer-checked:scale-105 hover:border-indigo-300">
                              <svg class="w-6 h-6 mx-auto mb-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                              <span class="text-sm font-semibold text-gray-700 capitalize">teacher</span>
                          </div>
                      </label>
                      {{-- Student (default checked) --}}
                      <label class="relative cursor-pointer">
                          <input type="radio" name="role" value="student" class="peer sr-only" {{ old('role', 'student') === 'student' ? 'checked' : '' }}>
                          <div class="p-4 rounded-2xl border-2 border-gray-200 text-center transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:ring-2 peer-checked:ring-emerald-500 peer-checked:scale-105 hover:border-emerald-300">
                              <svg class="w-6 h-6 mx-auto mb-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                              <span class="text-sm font-semibold text-gray-700 capitalize">student</span>
                          </div>
                      </label>
    </div>
</div>

                    {{-- IC --}}
                    <div>
                        <label for="ic" class="block text-sm font-semibold text-gray-700 mb-2">IC Number</label>
                        <input type="text" name="ic" id="ic" value="{{ old('ic') }}" required autofocus
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none"
                            placeholder="Enter your IC number">
                        @error('ic') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none"
                            placeholder="Enter your password">
                        @error('password') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Remember me
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">Forgot Password?</a>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/30 transform hover:-translate-y-1">
                        Sign In
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500">Don't have an account?
                        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 transition">Register here</a>
                    </p>
                </div>
            </div>

            
            
        </div>
    </div>
</div>
@endsection
