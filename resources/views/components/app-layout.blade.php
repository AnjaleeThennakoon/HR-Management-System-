@props(['title' => 'HR System'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-50 h-screen overflow-hidden flex">

{{-- Sidebar --}}
<aside class="w-56 bg-white border-r border-gray-200 flex flex-col h-screen flex-shrink-0">

    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-gray-100">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 bg-gray-900 rounded-md flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-sm font-bold text-gray-900 tracking-tight">HR System</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md mb-1 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- People Section --}}
        <div class="mt-4 mb-1 px-3">
            <span class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">People</span>
        </div>

        <a href="{{ route('employees.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md mb-1 text-sm font-medium {{ request()->routeIs('employees.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
            Employees
        </a>

        <a href="{{ route('departments.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md mb-1 text-sm font-medium {{ request()->routeIs('departments.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75"/>
            </svg>
            Departments
        </a>

        <a href="{{ route('designations.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md mb-1 text-sm font-medium {{ request()->routeIs('designations.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
            </svg>
            Designations
        </a>

        {{-- Time & Leave Section --}}
        <div class="mt-4 mb-1 px-3">
            <span class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Time & Leave</span>
        </div>

        <a href="{{ route('attendance.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md mb-1 text-sm font-medium {{ request()->routeIs('attendance.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Attendance
        </a>

        <a href="{{ route('leaves.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md mb-1 text-sm font-medium {{ request()->routeIs('leaves.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
            Leaves
        </a>

        <a href="{{ route('holidays.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md mb-1 text-sm font-medium {{ request()->routeIs('holidays.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-13.5-6h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75zm3-3h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75zm3-3h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75z"/>
            </svg>
            Holidays
        </a>

        {{-- Finance Section --}}
        <div class="mt-4 mb-1 px-3">
            <span class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Finance</span>
        </div>

        <a href="{{ route('payrolls.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md mb-1 text-sm font-medium {{ request()->routeIs('payrolls.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9.75h19.5m-16.5 3h3m-3 3h3m-3 3h3m6-6h3m-3 3h3m-3 3h3M3.75 4.5h16.5A1.5 1.5 0 0121.75 6v12a1.5 1.5 0 01-1.5 1.5H3.75A1.5 1.5 0 012.25 18V6a1.5 1.5 0 011.5-1.5z"/>
            </svg>
            Payroll
        </a>

        {{-- Reports Section --}}
        <div class="mt-4 mb-1 px-3">
            <span class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Analytics</span>
        </div>

        <a href="{{ route('report.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md mb-1 text-sm font-medium {{ request()->routeIs('report.*') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7.5 14.25l3-3 3 3 5.25-6"/>
            </svg>
            Reports
        </a>

    </nav>

    {{-- User + Logout --}}
    <div class="border-t border-gray-200 p-3">
        <div class="flex items-center gap-2.5 px-2 py-1.5 mb-1">
            <div class="w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold text-gray-700">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-gray-900 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                <p class="text-[10px] text-gray-500 truncate">Admin</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
                Sign out
            </button>
        </form>
    </div>
</aside>

{{-- Main content --}}
<main class="flex-1 overflow-y-auto h-screen bg-gray-50">
    {{ $slot }}
</main>

</body>
</html>
