@extends('layouts.main')
@section('title', 'Accept Admin Invitation - EduPro LMS')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-1">Accept Invitation</h1>
            <p class="text-indigo-200/70">Set up your admin account</p>
        </div>

        <div class="bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 p-8">
            <div class="mb-6 bg-indigo-500/20 border border-indigo-400/30 text-indigo-200 px-4 py-3 rounded-xl text-sm">
                <p><strong>IC:</strong> {{ $invitation->ic }}</p>
                <p><strong>Email:</strong> {{ $invitation->email }}</p>
            </div>

            <form method="POST" action="{{ route('admin.accept-invite.store', $token) }}" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-white/80 mb-1.5">Your Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('name') <p class="mt-1 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-white/80 mb-1.5">Password</label>
                    <input type="password" name="password" id="password" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('password') <p class="mt-1 text-sm text-red-300">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-white/80 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-lg">
                    Create Admin Account
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
