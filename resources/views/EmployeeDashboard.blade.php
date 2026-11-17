<x-app-layout title="Employee Dashboard - HR System">
    @php
        $user = auth()->user();
        $employee = $user->employee;
        $upcomingHolidays = \App\Models\Holiday::where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->take(3)
            ->get();
    @endphp

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        {{-- Welcome Header Banner --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center text-xl font-bold shadow-md shadow-indigo-100 flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome, {{ $user->name }}!</h1>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active Employee
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ now()->format('l, F j, Y') }} • Employee Portal
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('attendance.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        View My Attendance
                    </a>
                </div>
            </div>
        </div>

        <x-alert.success />

        @if($errors->any())
            <div role="alert" class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm">
                <div class="flex items-center gap-2 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Password update failed:
                </div>
                <ul class="mt-2 list-inside list-disc pl-2 space-y-1 text-xs text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Employee Details Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Employee ID --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Employee ID</span>
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15A2.25 2.25 0 002.25 6.75v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                        </svg>
                    </span>
                </div>
                <p class="text-lg font-bold text-gray-900">{{ $employee->employee_id ?? 'EMP-' . str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-gray-500 mt-1">Official staff identifier</p>
            </div>

            {{-- Department --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Department</span>
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                        </svg>
                    </span>
                </div>
                <p class="text-lg font-bold text-gray-900 truncate">{{ $employee->department->name ?? 'General Staff' }}</p>
                <p class="text-xs text-gray-500 mt-1">Assigned department</p>
            </div>

            {{-- Designation --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Designation</span>
                    <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                        </svg>
                    </span>
                </div>
                <p class="text-lg font-bold text-gray-900 truncate">{{ $employee->designation->name ?? 'Employee' }}</p>
                <p class="text-xs text-gray-500 mt-1">Job title / role</p>
            </div>

            {{-- Shift Hours --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Working Shift</span>
                    <span class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <p class="text-lg font-bold text-gray-900">
                    {{ $employee && $employee->shift_in ? \Carbon\Carbon::parse($employee->shift_in)->format('h:i A') : '08:30 AM' }} - {{ $employee && $employee->shift_out ? \Carbon\Carbon::parse($employee->shift_out)->format('h:i A') : '05:30 PM' }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Standard office hours</p>
            </div>
        </div>

        {{-- Two-Column Section: Account & Security + Quick Access & Holidays --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Change Password & Security Card (2 Cols) --}}
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-6">
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Account Security & Password</h2>
                        <p class="text-xs text-gray-500">Update your account credentials and password</p>
                    </div>
                </div>

                {{-- Account Info Summary --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100 text-sm">
                    <div>
                        <span class="text-xs text-gray-500 block">Logged-in Username</span>
                        <span class="font-mono font-semibold text-gray-800">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 block">Account Status</span>
                        <span class="font-semibold text-emerald-600 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Verified & Active
                        </span>
                    </div>
                </div>

                {{-- Change Password Form --}}
                <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Current Password --}}
                        <div>
                            <label for="current_password" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Current Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="current_password" id="current_password" required
                                   class="w-full px-3.5 py-2.5 text-sm border @error('current_password') border-red-500 ring-1 ring-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                   placeholder="Enter current password">
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label for="password" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" id="password" minlength="8" required
                                   class="w-full px-3.5 py-2.5 text-sm border @error('password') border-red-500 ring-1 ring-red-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                   placeholder="Minimum 8 characters">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <p class="text-xs text-gray-400">Must be at least 8 characters long.</p>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition shadow-sm active:scale-[0.98]">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Right Column: Upcoming Holidays & Info (1 Col) --}}
            <div class="space-y-6">

                {{-- Upcoming Holidays --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 mb-4">
                        <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center text-rose-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-gray-900">Upcoming Holidays</h2>
                    </div>

                    <div class="space-y-3">
                        @forelse($upcomingHolidays as $holiday)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100 text-xs">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $holiday->name }}</p>
                                    <p class="text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($holiday->date)->format('l') }}</p>
                                </div>
                                <span class="px-2.5 py-1 bg-white border border-gray-200 rounded-lg font-medium text-gray-600">
                                    {{ \Carbon\Carbon::parse($holiday->date)->format('M d') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 py-3 text-center">No upcoming holidays scheduled.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Attendance Quick Card --}}
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white rounded-2xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold">Attendance Log</h3>
                    </div>
                    <p class="text-xs text-gray-300 leading-relaxed mb-4">
                        Check your monthly attendance log, shift timings, and check-in history in the attendance portal.
                    </p>
                    <a href="{{ route('attendance.index') }}"
                       class="inline-flex items-center gap-2 text-xs font-semibold text-indigo-300 hover:text-white transition">
                        Go to Attendance Portal &rarr;
                    </a>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
