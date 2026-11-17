<x-app-layout title="Employee Dashboard - HR System">
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">

        {{-- Welcome Header Banner --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-gray-900 via-slate-800 to-indigo-950 rounded-2xl p-6 sm:p-8 text-white shadow-lg">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-indigo-200 border border-white/10 backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        Employee Portal
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-2">
                        Welcome back, {{ $employee ? $employee->first_name.' '.$employee->last_name : $user->name }}!
                    </h1>
                    <p class="text-sm text-gray-300 mt-1 max-w-xl">
                        View your employment profile, track daily attendance, manage leave requests, explore company holidays, and view payslips.
                    </p>

                    {{-- Permitted Profile Badges --}}
                    <div class="flex flex-wrap items-center gap-2 mt-4 text-xs font-medium text-gray-200">
                        @if ($canViewProfile && $employee)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 border border-white/10">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                ID: #{{ $employee->employee_id }}
                            </span>
                        @endif

                        @if ($canViewDepartment && $employee?->department)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 border border-white/10">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                {{ $employee->department->name }}
                            </span>
                        @endif

                        @if ($canViewDesignation && $employee?->designation)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 border border-white/10">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                {{ $employee->designation->name }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="flex items-center gap-3">
                    @if ($canStoreLeave && $employee)
                        <button type="button" onclick="openApplyLeaveModal()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold shadow-md transition transform active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                            Apply Leave
                        </button>
                    @endif
                    <a href="{{ route('employee.profile') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-medium border border-white/10 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Security & Profile
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Alerts --}}
        @if (session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-sm text-emerald-800">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 flex items-center gap-3 text-sm text-rose-800">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm text-rose-800 space-y-1">
                <p class="font-semibold">Please check the submitted form for errors:</p>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Clean Navigation Tabs --}}
        <div class="border-b border-gray-200 bg-white rounded-xl shadow-sm px-4">
            <nav class="flex flex-wrap gap-2 sm:gap-4 -mb-px text-sm font-medium" aria-label="Tabs">
                @if ($canViewProfile && $employee)
                    <button type="button" onclick="switchDashboardTab('profile-tab')" id="btn-profile-tab"
                            class="dashboard-tab-btn py-3.5 px-3 border-b-2 border-indigo-600 text-indigo-600 font-semibold flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Profile & Details
                    </button>
                @endif

                @if ($canViewAttendance && $employee)
                    <button type="button" onclick="switchDashboardTab('attendance-tab')" id="btn-attendance-tab"
                            class="dashboard-tab-btn py-3.5 px-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Attendance
                    </button>
                @endif

                @if (($canStoreLeave || $canUpdateLeave || $canDestroyLeave) && $employee)
                    <button type="button" onclick="switchDashboardTab('leaves-tab')" id="btn-leaves-tab"
                            class="dashboard-tab-btn py-3.5 px-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Leaves
                        @if ($leaves->where('status', 'pending')->count() > 0)
                            <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-800 font-bold">
                                {{ $leaves->where('status', 'pending')->count() }}
                            </span>
                        @endif
                    </button>
                @endif

                @if ($canViewHolidays)
                    <button type="button" onclick="switchDashboardTab('holidays-tab')" id="btn-holidays-tab"
                            class="dashboard-tab-btn py-3.5 px-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        Holidays
                        <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] bg-gray-100 text-gray-600 font-bold">
                            {{ $holidays->count() }}
                        </span>
                    </button>
                @endif

                @if ($canViewPayroll && $employee)
                    <button type="button" onclick="switchDashboardTab('payroll-tab')" id="btn-payroll-tab"
                            class="dashboard-tab-btn py-3.5 px-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z" /></svg>
                        Payroll & Payslips
                    </button>
                @endif
            </nav>
        </div>

        {{-- ========================================== --}}
        {{-- TAB 1: PROFILE & EMPLOYMENT DETAILS        --}}
        {{-- ========================================== --}}
        @if ($canViewProfile && $employee)
            <div id="profile-tab" class="dashboard-tab-content space-y-6">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50/50">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Personal & Employment Profile</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Your official organizational record and contact information</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Active Employee
                            </span>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Full Name --}}
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">Full Name</span>
                            <span class="text-sm font-bold text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                        </div>

                        {{-- Employee ID --}}
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">Employee ID</span>
                            <span class="text-sm font-bold text-gray-900">#{{ $employee->employee_id }}</span>
                        </div>

                        {{-- Email --}}
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">Email Address</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $user->email }}</span>
                        </div>

                        {{-- Department --}}
                        @if ($canViewDepartment)
                            <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">Department</span>
                                <span class="text-sm font-bold text-indigo-700">{{ $employee->department?->name ?? 'Not Assigned' }}</span>
                            </div>
                        @endif

                        {{-- Designation --}}
                        @if ($canViewDesignation)
                            <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">Designation</span>
                                <span class="text-sm font-bold text-indigo-700">{{ $employee->designation?->name ?? 'Not Assigned' }}</span>
                            </div>
                        @endif

                        {{-- Phone --}}
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">Phone Number</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $employee->phone ?: 'Not Provided' }}</span>
                        </div>

                        {{-- National ID / NIC --}}
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">National ID (NIC)</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $employee->nic ?: 'Not Provided' }}</span>
                        </div>

                        {{-- Date of Birth --}}
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">Date of Birth</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') : 'Not Provided' }}
                            </span>
                        </div>

                        {{-- Gender --}}
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">Gender</span>
                            <span class="text-sm font-semibold text-gray-900 capitalize">{{ $employee->gender ?: 'Not Specified' }}</span>
                        </div>

                        {{-- Shift Schedule --}}
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 md:col-span-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">Work Shift Schedule</span>
                            <div class="flex items-center gap-3 text-sm font-semibold text-gray-900">
                                <span class="inline-flex items-center gap-1.5 text-gray-700">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                    Shift In: {{ $employee->shift_in ? \Carbon\Carbon::parse($employee->shift_in)->format('h:i A') : '09:00 AM' }}
                                </span>
                                <span class="text-gray-300">|</span>
                                <span class="inline-flex items-center gap-1.5 text-gray-700">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    Shift Out: {{ $employee->shift_out ? \Carbon\Carbon::parse($employee->shift_out)->format('h:i A') : '05:00 PM' }}
                                </span>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 lg:col-span-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">Residential Address</span>
                            <span class="text-sm text-gray-800">{{ $employee->address ?: 'No registered address on file' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========================================== --}}
        {{-- TAB 2: ATTENDANCE RECORDS                  --}}
        {{-- ========================================== --}}
        @if ($canViewAttendance && $employee)
            <div id="attendance-tab" class="dashboard-tab-content space-y-6 hidden">
                {{-- Monthly Stats --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Present Days ({{ date('F') }})</span>
                            <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $attendanceStats['present_days'] }} Days</p>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Late Minutes</span>
                            <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $attendanceStats['late_minutes'] }} Mins</p>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Overtime Recorded</span>
                            <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $attendanceStats['ot_minutes'] }} Mins</p>
                        </div>
                    </div>
                </div>

                {{-- Attendance Table --}}
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <div>
                            <h2 class="text-base font-bold text-gray-900">Attendance Log</h2>
                            <p class="text-xs text-gray-500">Your recent daily clock-in and clock-out entries</p>
                        </div>
                        <a href="{{ route('attendance.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                            View Full Log &rarr;
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="py-3 px-5 font-semibold">Date</th>
                                    <th class="py-3 px-5 font-semibold">Clock In</th>
                                    <th class="py-3 px-5 font-semibold">Clock Out</th>
                                    <th class="py-3 px-5 font-semibold text-right">Late</th>
                                    <th class="py-3 px-5 font-semibold text-right">Overtime</th>
                                    <th class="py-3 px-5 font-semibold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($attendances as $att)
                                    <tr class="hover:bg-gray-50/70 transition">
                                        <td class="py-3.5 px-5 font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($att->date)->format('D, d M Y') }}
                                        </td>
                                        <td class="py-3.5 px-5 text-gray-700">
                                            {{ $att->in_time ? \Carbon\Carbon::parse($att->in_time)->format('h:i A') : '—' }}
                                        </td>
                                        <td class="py-3.5 px-5 text-gray-700">
                                            {{ $att->out_time ? \Carbon\Carbon::parse($att->out_time)->format('h:i A') : '—' }}
                                        </td>
                                        <td class="py-3.5 px-5 text-right font-medium {{ (int) $att->late_minutes > 0 ? 'text-amber-600' : 'text-gray-400' }}">
                                            {{ (int) $att->late_minutes > 0 ? $att->late_minutes.'m' : '—' }}
                                        </td>
                                        <td class="py-3.5 px-5 text-right font-medium {{ (int) $att->ot_minutes > 0 ? 'text-emerald-600' : 'text-gray-400' }}">
                                            {{ (int) $att->ot_minutes > 0 ? $att->ot_minutes.'m' : '—' }}
                                        </td>
                                        <td class="py-3.5 px-5 text-center">
                                            @if ($att->in_time)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Present
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                                    Absent / Non-Working
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-sm text-gray-400">
                                            No attendance records recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========================================== --}}
        {{-- TAB 3: LEAVES & REQUESTS                   --}}
        {{-- ========================================== --}}
        @if (($canStoreLeave || $canUpdateLeave || $canDestroyLeave) && $employee)
            <div id="leaves-tab" class="dashboard-tab-content space-y-6 hidden">
                {{-- Leave Balances Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach (['Annual' => 'indigo', 'Medical' => 'teal', 'casual' => 'amber'] as $type => $theme)
                        @php
                            $bal = $leaveBalances[$type] ?? ['remaining_days' => 0, 'max_days' => 0, 'used_days' => 0];
                        @endphp
                        <div class="p-5 rounded-2xl bg-white border border-gray-200 shadow-sm relative overflow-hidden">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">{{ ucfirst($type) }} Leave</span>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-{{ $theme }}-50 text-{{ $theme }}-700">
                                    {{ $bal['used_days'] }} used
                                </span>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-extrabold text-gray-900">{{ $bal['remaining_days'] }}</span>
                                <span class="text-xs text-gray-400">/ {{ $bal['max_days'] }} days remaining</span>
                            </div>
                            <div class="w-full bg-gray-100 h-1.5 rounded-full mt-3 overflow-hidden">
                                @php
                                    $pct = $bal['max_days'] > 0 ? min(100, round(($bal['used_days'] / $bal['max_days']) * 100)) : 0;
                                @endphp
                                <div class="bg-{{ $theme }}-600 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Leave Requests List --}}
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50/50">
                        <div>
                            <h2 class="text-base font-bold text-gray-900">My Leave Requests</h2>
                            <p class="text-xs text-gray-500">Track and manage your submitted leave applications</p>
                        </div>
                        @if ($canStoreLeave)
                            <button type="button" onclick="openApplyLeaveModal()"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold shadow-sm transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                                New Leave Request
                            </button>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="py-3 px-5 font-semibold">Type</th>
                                    <th class="py-3 px-5 font-semibold">Period</th>
                                    <th class="py-3 px-5 font-semibold">Reason</th>
                                    <th class="py-3 px-5 font-semibold text-center">Status</th>
                                    <th class="py-3 px-5 font-semibold">Approval Info</th>
                                    <th class="py-3 px-5 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($leaves as $leave)
                                    <tr class="hover:bg-gray-50/70 transition">
                                        <td class="py-3.5 px-5">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                                {{ $leave->leave_type }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-5 font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                            @if ($leave->end_date && $leave->end_date !== $leave->start_date)
                                                <span class="text-gray-400">→</span>
                                                {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-5 text-gray-600 max-w-xs truncate" title="{{ $leave->reason }}">
                                            {{ $leave->reason }}
                                        </td>
                                        <td class="py-3.5 px-5 text-center">
                                            @if ($leave->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Approved
                                                </span>
                                            @elseif ($leave->status === 'rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                    Rejected
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-5 text-xs text-gray-500">
                                            @if ($leave->status === 'approved' && $leave->approved_at)
                                                Approved on {{ $leave->approved_at->format('d M Y') }}
                                            @elseif ($leave->status === 'rejected')
                                                Declined
                                            @else
                                                Awaiting review
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                            @if ($leave->status === 'pending')
                                                <div class="inline-flex items-center gap-1.5">
                                                    @if ($canUpdateLeave)
                                                        <button type="button"
                                                                onclick="openEditLeaveModal({{ $leave->id }}, '{{ $leave->leave_type }}', '{{ $leave->start_date }}', '{{ $leave->end_date }}', {{ json_encode($leave->reason) }})"
                                                                class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                                                title="Edit Leave">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                        </button>
                                                    @endif

                                                    @if ($canDestroyLeave)
                                                        <form method="POST" action="{{ route('leaves.destroy', $leave->id) }}" onsubmit="return confirm('Are you sure you want to cancel this pending leave request?');" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-1.5 text-gray-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Leave">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 font-medium">Finalized</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-sm text-gray-400">
                                            No leave requests recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========================================== --}}
        {{-- TAB 4: COMPANY HOLIDAYS                    --}}
        {{-- ========================================== --}}
        @if ($canViewHolidays)
            <div id="holidays-tab" class="dashboard-tab-content space-y-6 hidden">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Company and Public Holidays ({{ date('Y') }})</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Upcoming recognized non-working holidays and office closures</p>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse ($holidays as $holiday)
                            @php
                                $holidayDate = \Carbon\Carbon::parse($holiday->date);
                                $isUpcoming = $holidayDate->isFuture();
                                $isToday = $holidayDate->isToday();
                            @endphp
                            <div class="p-4 rounded-xl border {{ $isToday ? 'border-emerald-500 bg-emerald-50/40 ring-2 ring-emerald-500/20' : ($isUpcoming ? 'border-gray-200 bg-white hover:border-indigo-200' : 'border-gray-100 bg-gray-50/50 opacity-70') }} shadow-sm transition">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $holiday->type === 'Public' ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $holiday->type ?: 'Holiday' }}
                                        </span>
                                        <h3 class="text-sm font-bold text-gray-900 mt-1.5">{{ $holiday->name }}</h3>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-extrabold text-indigo-700 block">{{ $holidayDate->format('d M') }}</span>
                                        <span class="text-[10px] text-gray-400 block">{{ $holidayDate->format('D') }}</span>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                                    <span class="text-gray-500">{{ $holidayDate->format('F d, Y') }}</span>
                                    @if ($isToday)
                                        <span class="font-bold text-emerald-600">Today!</span>
                                    @elseif ($isUpcoming)
                                        <span class="font-medium text-indigo-600">In {{ $holidayDate->diffForHumans(['parts' => 1]) }}</span>
                                    @else
                                        <span class="text-gray-400">Passed</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-sm text-gray-400">
                                No company holidays listed for this year.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        {{-- ========================================== --}}
        {{-- TAB 5: PAYROLL & PAYSLIPS                  --}}
        {{-- ========================================== --}}
        @if ($canViewPayroll && $employee)
            <div id="payroll-tab" class="dashboard-tab-content space-y-6 hidden">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Payroll Records and Salary Statements</h2>
                            <p class="text-xs text-gray-500 mt-0.5">View your monthly compensation statements and download payslip PDFs</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="py-3.5 px-5 font-semibold">Pay Period</th>
                                    <th class="py-3.5 px-5 font-semibold text-right">Basic Salary</th>
                                    <th class="py-3.5 px-5 font-semibold text-right">Allowances</th>
                                    <th class="py-3.5 px-5 font-semibold text-right">Deductions</th>
                                    <th class="py-3.5 px-5 font-semibold text-right">EPF (8%)</th>
                                    <th class="py-3.5 px-5 font-semibold text-right">Net Salary</th>
                                    <th class="py-3.5 px-5 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($payrolls as $payroll)
                                    @php
                                        $monthName = \Carbon\Carbon::create(null, (int) $payroll->month, 1)->format('F');
                                        $grossSalary = (float) $payroll->basic_salary + (float) $payroll->allowances;
                                        $periodStr = $monthName.' '.$payroll->year;
                                    @endphp
                                    <tr class="hover:bg-gray-50/70 transition">
                                        <td class="py-4 px-5 font-bold text-gray-900">
                                            {{ $periodStr }}
                                        </td>
                                        <td class="py-4 px-5 text-right font-medium text-gray-600">
                                            Rs. {{ number_format((float) $payroll->basic_salary, 2) }}
                                        </td>
                                        <td class="py-4 px-5 text-right font-medium text-emerald-600">
                                            Rs. {{ number_format((float) $payroll->allowances, 2) }}
                                        </td>
                                        <td class="py-4 px-5 text-right font-medium text-rose-600">
                                            Rs. {{ number_format((float) $payroll->deductions, 2) }}
                                        </td>
                                        <td class="py-4 px-5 text-right font-medium text-gray-600">
                                            Rs. {{ number_format((float) $payroll->EPF, 2) }}
                                        </td>
                                        <td class="py-4 px-5 text-right font-extrabold text-gray-900">
                                            Rs. {{ number_format((float) $payroll->net_salary, 2) }}
                                        </td>
                                        <td class="py-4 px-5 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center gap-2">
                                                {{-- View Slip Modal button --}}
                                                <button type="button"
                                                        onclick="viewEmployeePayslip({{ json_encode([
                                                            'period' => $periodStr,
                                                            'employeeName' => $employee->first_name.' '.$employee->last_name,
                                                            'employeeId' => $employee->employee_id,
                                                            'department' => $employee->department?->name ?? 'N/A',
                                                            'designation' => $employee->designation?->name ?? 'N/A',
                                                            'basic' => number_format((float) $payroll->basic_salary, 2),
                                                            'allowances' => number_format((float) $payroll->allowances, 2),
                                                            'gross' => number_format((float) $grossSalary, 2),
                                                            'deductions' => number_format((float) $payroll->deductions, 2),
                                                            'epf' => number_format((float) $payroll->EPF, 2),
                                                            'net' => number_format((float) $payroll->net_salary, 2),
                                                            'downloadUrl' => route('payrolls.download', $payroll->id),
                                                            'canDownload' => $canDownloadPayroll,
                                                        ]) }})"
                                                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                                                    View Slip
                                                </button>

                                                {{-- Direct PDF Download --}}
                                                @if ($canDownloadPayroll)
                                                    <a href="{{ route('payrolls.download', $payroll->id) }}"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-teal-800 hover:bg-teal-700 text-white shadow-sm transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                        PDF
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-sm text-gray-400">
                                            No payroll records generated yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- ========================================== --}}
    {{-- MODAL 1: APPLY LEAVE MODAL                 --}}
    {{-- ========================================== --}}
    @if ($canStoreLeave && $employee)
        <div id="applyLeaveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-[fadeIn_0.2s_ease-out]">
                <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Apply for Leave</h3>
                    </div>
                    <button type="button" onclick="closeApplyLeaveModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('leaves.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">
                            Leave Type <span class="text-red-500">*</span>
                        </label>
                        <select name="leave_type" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            <option value="Annual">Annual Leave ({{ $leaveBalances['Annual']['remaining_days'] ?? 0 }} days available)</option>
                            <option value="Medical">Medical Leave ({{ $leaveBalances['Medical']['remaining_days'] ?? 0 }} days available)</option>
                            <option value="casual">Casual Leave ({{ $leaveBalances['casual']['remaining_days'] ?? 0 }} days available)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="apply_start_date" required min="{{ date('Y-m-d') }}"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">
                                End Date
                            </label>
                            <input type="date" name="end_date" id="apply_end_date"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">
                            Reason for Leave <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reason" rows="3" required placeholder="Please provide details about your leave..."
                                  class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeApplyLeaveModal()"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md transition">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- MODAL 2: EDIT LEAVE MODAL                  --}}
    {{-- ========================================== --}}
    @if ($canUpdateLeave && $employee)
        <div id="editLeaveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-[fadeIn_0.2s_ease-out]">
                <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Edit Leave Request</h3>
                    </div>
                    <button type="button" onclick="closeEditLeaveModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form id="editLeaveForm" method="POST" action="" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">
                            Leave Type <span class="text-red-500">*</span>
                        </label>
                        <select name="leave_type" id="edit_leave_type" required class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            <option value="Annual">Annual Leave</option>
                            <option value="Medical">Medical Leave</option>
                            <option value="casual">Casual Leave</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="edit_start_date" required
                                   class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">
                                End Date
                            </label>
                            <input type="date" name="end_date" id="edit_end_date"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">
                            Reason for Leave <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reason" id="edit_reason" rows="3" required
                                  class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeEditLeaveModal()"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 shadow-md transition">
                            Update Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- MODAL 3: VIEW PAYSLIP MODAL                --}}
    {{-- ========================================== --}}
    @if ($canViewPayroll && $employee)
        <div id="payslipModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto">
            <div class="relative my-4 flex max-h-[85vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/10">
                <div class="flex justify-between items-center border-b border-slate-100 px-6 py-4">
                    <span class="text-xs font-semibold uppercase tracking-widest text-teal-800">HR System · Salary Statement</span>
                    <button type="button" onclick="closePayslipModal()" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="overflow-y-auto p-6 space-y-6">
                    {{-- Payslip Banner --}}
                    <div class="rounded-xl bg-teal-900 text-white p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h3 class="text-xl font-bold tracking-tight">Salary Slip</h3>
                                <p class="text-xs text-teal-200 mt-1">Pay Period: <span id="slipPeriod" class="font-bold text-white"></span></p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-extrabold uppercase tracking-wider text-teal-200">HR System</span>
                                <p class="text-xs text-teal-300">Confidential</p>
                            </div>
                        </div>
                    </div>

                    {{-- Employee Meta --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-xl border border-slate-200 bg-slate-50 text-xs">
                        <div>
                            <span class="text-slate-400 block mb-0.5">Employee Name</span>
                            <span id="slipEmployeeName" class="font-bold text-slate-900"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Employee ID</span>
                            <span id="slipEmployeeId" class="font-bold text-slate-900"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Department</span>
                            <span id="slipDepartment" class="font-bold text-slate-900"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Designation</span>
                            <span id="slipDesignation" class="font-bold text-slate-900"></span>
                        </div>
                    </div>

                    {{-- Breakdown Tables --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Earnings --}}
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <div class="bg-teal-700 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider">
                                Earnings
                            </div>
                            <div class="p-3 space-y-2 text-xs">
                                <div class="flex justify-between py-1 border-b border-slate-100">
                                    <span class="text-slate-600">Basic Salary</span>
                                    <span id="slipBasic" class="font-semibold text-slate-900">Rs. 0.00</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-slate-100">
                                    <span class="text-slate-600">Allowances / OT</span>
                                    <span id="slipAllowances" class="font-semibold text-emerald-700">Rs. 0.00</span>
                                </div>
                                <div class="flex justify-between py-1 font-bold text-teal-900">
                                    <span>Total Gross</span>
                                    <span id="slipGross">Rs. 0.00</span>
                                </div>
                            </div>
                        </div>

                        {{-- Deductions --}}
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <div class="bg-rose-700 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider">
                                Deductions
                            </div>
                            <div class="p-3 space-y-2 text-xs">
                                <div class="flex justify-between py-1 border-b border-slate-100">
                                    <span class="text-slate-600">Late / No-Pay / Other</span>
                                    <span id="slipDeductions" class="font-semibold text-rose-700">Rs. 0.00</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-slate-100">
                                    <span class="text-slate-600">EPF (Employee 8%)</span>
                                    <span id="slipEPF" class="font-semibold text-rose-700">Rs. 0.00</span>
                                </div>
                                <div class="flex justify-between py-1 font-bold text-rose-900">
                                    <span>Total Deductions</span>
                                    <span id="slipTotalDeductions">Rs. 0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Net Pay Highlight --}}
                    <div class="p-4 rounded-xl bg-teal-900 text-white flex items-center justify-between">
                        <span class="text-sm font-extrabold uppercase tracking-wide">Net Salary Received</span>
                        <span id="slipNet" class="text-xl font-extrabold text-white">Rs. 0.00</span>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50 px-6 py-4">
                    <button type="button" onclick="closePayslipModal()"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 transition">
                        Close
                    </button>
                    <a id="slipDownloadBtn" href="#"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-teal-800 hover:bg-teal-700 shadow-md transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Interactive Tabs and Modal Script --}}
    <script>
        function switchDashboardTab(tabId) {
            document.querySelectorAll('.dashboard-tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.dashboard-tab-btn').forEach(btn => {
                btn.classList.remove('border-indigo-600', 'text-indigo-600', 'font-semibold');
                btn.classList.add('border-transparent', 'text-gray-500', 'font-medium');
            });

            const activeTab = document.getElementById(tabId);
            const activeBtn = document.getElementById('btn-' + tabId);

            if (activeTab) activeTab.classList.remove('hidden');
            if (activeBtn) {
                activeBtn.classList.add('border-indigo-600', 'text-indigo-600', 'font-semibold');
                activeBtn.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
            }
        }

        function openApplyLeaveModal() {
            const modal = document.getElementById('applyLeaveModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeApplyLeaveModal() {
            const modal = document.getElementById('applyLeaveModal');
            if (modal) modal.classList.add('hidden');
        }

        function openEditLeaveModal(id, type, startDate, endDate, reason) {
            const modal = document.getElementById('editLeaveModal');
            const form = document.getElementById('editLeaveForm');
            if (modal && form) {
                form.action = '/leaves/' + id;
                document.getElementById('edit_leave_type').value = type;
                document.getElementById('edit_start_date').value = startDate ? startDate.substring(0, 10) : '';
                document.getElementById('edit_end_date').value = endDate ? endDate.substring(0, 10) : '';
                document.getElementById('edit_reason').value = reason || '';
                modal.classList.remove('hidden');
            }
        }

        function closeEditLeaveModal() {
            const modal = document.getElementById('editLeaveModal');
            if (modal) modal.classList.add('hidden');
        }

        function viewEmployeePayslip(data) {
            document.getElementById('slipPeriod').textContent = data.period;
            document.getElementById('slipEmployeeName').textContent = data.employeeName;
            document.getElementById('slipEmployeeId').textContent = '#' + data.employeeId;
            document.getElementById('slipDepartment').textContent = data.department;
            document.getElementById('slipDesignation').textContent = data.designation;
            document.getElementById('slipBasic').textContent = 'Rs. ' + data.basic;
            document.getElementById('slipAllowances').textContent = 'Rs. ' + data.allowances;
            document.getElementById('slipGross').textContent = 'Rs. ' + data.gross;
            document.getElementById('slipDeductions').textContent = 'Rs. ' + data.deductions;
            document.getElementById('slipEPF').textContent = 'Rs. ' + data.epf;
            document.getElementById('slipTotalDeductions').textContent = 'Rs. ' + data.deductions;
            document.getElementById('slipNet').textContent = 'Rs. ' + data.net;

            const downloadBtn = document.getElementById('slipDownloadBtn');
            if (downloadBtn) {
                if (data.canDownload) {
                    downloadBtn.href = data.downloadUrl;
                    downloadBtn.classList.remove('hidden');
                } else {
                    downloadBtn.classList.add('hidden');
                }
            }

            const modal = document.getElementById('payslipModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closePayslipModal() {
            const modal = document.getElementById('payslipModal');
            if (modal) modal.classList.add('hidden');
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeApplyLeaveModal();
                closeEditLeaveModal();
                closePayslipModal();
            }
        });
    </script>
</x-app-layout>
