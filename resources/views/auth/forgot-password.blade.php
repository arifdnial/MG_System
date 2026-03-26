@extends('layouts.main')
@section('title', 'Forgot Password - EduPro LMS')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-2xl mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-1">Forgot Password</h1>
            <p class="text-indigo-200/70">Enter your IC number to reset your password</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 px-4 py-3 rounded-xl text-sm text-center">
                {{ session('success') }}
            </div>
        @endif
        @if(session('debug_reset_url'))
            <div class="mb-4 bg-yellow-500/20 border border-yellow-400/30 text-yellow-200 px-4 py-3 rounded-xl text-sm break-all">
                <strong>Dev Mode - Reset Link:</strong><br>
                <a href="{{ session('debug_reset_url') }}" class="underline hover:text-yellow-100">{{ session('debug_reset_url') }}</a>
            </div>
        @endif

        <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 p-8">
            <form method="POST" action="{{ route('password.request') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="ic" class="block text-sm font-medium text-white/80 mb-1.5">IC Number</label>
                    <input type="text" name="ic" id="ic" value="{{ old('ic') }}" required autofocus
                        class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        placeholder="Enter your IC number">
                    @error('ic') <p class="mt-1 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg">
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-indigo-300 hover:text-indigo-200 font-medium transition">← Back to Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
