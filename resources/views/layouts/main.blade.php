<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(app()->getLocale() == 'ar') dir="rtl" @endif>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MGM-System')</title>
    <meta name="description" content="MGM-System">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Vazirmatn:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }

        [dir="rtl"] body {
            font-family: 'Inter', 'Vazirmatn', sans-serif;
        }

        /* Custom Scrollbar is now handled in app.css */
    </style>
</head>

<body class="bg-slate-50 min-h-screen text-slate-900">
    @auth
        <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

            {{-- Sidebar --}}
            <aside id="sidebar" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="w-72 bg-slate-950 text-white flex flex-col fixed inset-y-0 left-0 z-50 lg:translate-x-0 transition-transform duration-500 ease-in-out border-r border-white/5 shadow-2xl">

                {{-- Brand Section --}}
                <div class="p-8 shrink-0">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-extrabold tracking-tight">MGM-System
                            </h1>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em]">{{ __('Workspace') }}
                                ({{ __(ucfirst(auth()->user()->role)) }})</p>
                        </div>
                    </div>
                </div>

                {{-- Language Switcher --}}
                <div class="px-8 mb-6">
                    <div class="flex items-center gap-2 p-1 bg-white/5 rounded-xl border border-white/10">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="flex-1 flex items-center justify-center py-2 px-3 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all {{ app()->getLocale() == 'en' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}">
                            English
                        </a>
                        <a href="{{ route('lang.switch', 'ar') }}"
                            class="flex-1 flex items-center justify-center py-2 px-3 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all {{ app()->getLocale() == 'ar' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}">
                            العربية
                        </a>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar custom-scrollbar-dark">
                    <div class="px-4 mb-4">
                        <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">{{ __('Main Menu') }}</p>
                    </div>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users.index', 'teacher') }}"
                            class="sidebar-link {{ request()->routeIs('admin.users.*') && request()->segment(3) == 'teacher' ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>
                            </svg>
                            {{ __('Teachers') }}
                        </a>
                    @elseif(auth()->user()->isTeacher())
                        <a href="{{ route('teacher.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('teacher.dashboard') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('teacher.subjects.index') }}"
                            class="sidebar-link {{ request()->routeIs('teacher.subjects.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                            {{ __('My Subjects') }}
                        </a>
                        <a href="{{ route('teacher.exams.index') }}"
                            class="sidebar-link {{ request()->routeIs('teacher.exams.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                            {{ __('Exams') }}
                        </a>
                        <a href="{{ route('teacher.announcements.index') }}"
                            class="sidebar-link {{ request()->routeIs('teacher.announcements.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                </path>
                            </svg>
                            {{ __('Announcements') }}
                        </a>
                        <a href="{{ route('teacher.materials.index') }}"
                            class="sidebar-link {{ request()->routeIs('teacher.materials.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            {{ __('Course Materials') }}
                        </a>
                    @elseif(auth()->user()->isStudent())
                        <a href="{{ route('student.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                                {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('student.subjects.index') }}"
                            class="sidebar-link {{ request()->routeIs('student.subjects.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                               {{ __('My Subjects') }}
                        </a>
                        <a href="{{ route('student.groups.index') }}"
                            class="sidebar-link {{ request()->routeIs('student.groups.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            {{ __('Group Activities') }}
                        </a>
                        <a href="{{ route('student.exams.index') }}"
                            class="sidebar-link {{ request()->routeIs('student.exams.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                                    {{ __('Exams') }}
                        </a>
                        <a href="{{ route('student.marks') }}"
                            class="sidebar-link {{ request()->routeIs('student.marks') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                              {{ __('My Marks') }}
                        </a>
                    @endif
                </nav>

                {{-- Profile / Footer --}}
                <div class="p-4 border-t border-white/5 bg-white/[0.02]">
                    <div class="flex items-center gap-3 p-3 mb-3 rounded-2xl bg-white/5">
                        <div
                            class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center font-bold text-white shadow-inner">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate text-white">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                                {{ auth()->user()->role }}
                            </p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full group flex items-center justify-center gap-2 py-3 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all duration-300 font-bold text-xs uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            {{ __('Sign Out') }}
                        </button>
                    </form>
                </div>
            </aside>

            {{-- Mobile Sidebar Overlay --}}
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden" x-cloak></div>

            {{-- Main Wrapper --}}
            <div class="flex-1 lg:ml-72 flex flex-col h-full overflow-y-auto custom-scrollbar custom-scrollbar-light">

                {{-- Mobile Header --}}
                <header
                    class="lg:hidden bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-40">
                    <button @click="sidebarOpen = true"
                        class="p-2.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <span class="font-extrabold text-xl tracking-tight text-slate-900">MGM-System</span>
                    <div class="w-10"></div>
                </header>

                {{-- Content Area --}}
                <main class="flex-1 p-6 lg:p-10 max-w-[1600px] w-full mx-auto">
                    {{-- Flash Messages as modern Toasts --}}
                    @if(session('success'))
                        <div
                            class="mb-8 flex items-center gap-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-800 shadow-sm animate-fade-in-down">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold">{{ session('success') }}</p>
                            <button onclick="this.parentElement.remove()"
                                class="ml-auto text-emerald-400 hover:text-emerald-600">&times;</button>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <div class="min-h-screen flex flex-col items-center justify-center p-6">
            @yield('content')
        </div>
    @endauth

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>

</html>