@extends('layouts.main')
@section('title', 'Invite Admin - EduPro LMS')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Invite New Admin</h1>
        <p class="text-gray-500">Send an invitation to create a new admin account.</p>
    </div>

    @if(session('invite_url'))
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
            <div class="flex gap-3">
                <svg class="w-6 h-6 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-semibold text-amber-800">Invitation Created for {{ session('invite_name') }}</p>
                    <p class="text-sm text-amber-700 mt-1">In production, this link would be emailed. For testing, share this link:</p>
                    <div class="mt-2 bg-white/80 rounded-lg px-3 py-2 text-sm font-mono break-all text-amber-900">
                        <a href="{{ session('invite_url') }}" class="hover:underline">{{ session('invite_url') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.invite.send') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Admin Name</label>
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

            <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white font-medium px-6 py-2.5 rounded-xl transition-all shadow-md">
                Send Invitation
            </button>
        </form>
    </div>

    @if($invitations->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Recent Invitations</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">IC</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($invitations as $inv)
                    <tr>
                        <td class="px-6 py-3 font-mono text-sm">{{ $inv->ic }}</td>
                        <td class="px-6 py-3 text-sm">{{ $inv->email }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $inv->used ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $inv->used ? 'Accepted' : 'Pending' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-500">{{ $inv->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
