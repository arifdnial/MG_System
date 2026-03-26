@extends('layouts.main')
@section('title', 'Add ' . ucfirst($role) . ' - Admin - EduPro LMS')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.users.index', $role) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">← Back to {{ ucfirst($role) }}s</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Add New {{ ucfirst($role) }}</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.users.store', $role) }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="ic" class="block text-sm font-medium text-gray-700 mb-1">IC Number</label>
                    <input type="text" name="ic" id="ic" value="{{ old('ic') }}" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('ic') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Optional">
            </div>

            @if($role === 'student')
            <div>
                <label for="form_class" class="block text-sm font-medium text-gray-700 mb-1">Form / Class</label>
                <input type="text" name="form_class" id="form_class" value="{{ old('form_class') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="e.g. Form 3A">
            </div>
            @endif

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Minimum 8 characters">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-medium px-6 py-2.5 rounded-xl transition-all shadow-md">
                    Create {{ ucfirst($role) }}
                </button>
                <a href="{{ route('admin.users.index', $role) }}" class="text-gray-600 hover:text-gray-800 px-6 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-50 transition font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
