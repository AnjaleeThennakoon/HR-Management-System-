<x-app-layout title="Dashboard - HR System">
    <div class="p-8">

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, {{ auth()->user()->name ?? 'User' }}</p>
        </div>

        {{-- Module Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            {{-- Employees --}}
            <a href="{{ route('employees.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-400 block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Employees</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Manage your team</p>
                    </div>
                </div>
            </a>

            {{-- Departments --}}
            <a href="{{ route('departments.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-400 block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Departments</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Organize your structure</p>
                    </div>
                </div>
            </a>

            {{-- Designations --}}
            <a href="{{ route('designations.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-400 block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Designations</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Define job roles</p>
                    </div>
                </div>
            </a>

            {{-- Attendance --}}
            <a href="{{ route('attendance.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-400 block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Attendance</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Track daily records</p>
                    </div>
                </div>
            </a>

            {{-- Leaves --}}
            <a href="{{ route('leaves.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-400 block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Leaves</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Manage leave requests</p>
                    </div>
                </div>
            </a>

            {{-- Holidays --}}
            <a href="{{ route('holidays.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-400 block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-13.5-6h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75zm3-3h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75zm3-3h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Holidays</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Company holidays</p>
                    </div>
                </div>
            </a>

            {{-- Payroll --}}
            <a href="{{ route('payrolls.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-400 block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9.75h19.5m-16.5 3h3m-3 3h3m-3 3h3m6-6h3m-3 3h3m-3 3h3M3.75 4.5h16.5A1.5 1.5 0 0121.75 6v12a1.5 1.5 0 01-1.5 1.5H3.75A1.5 1.5 0 012.25 18V6a1.5 1.5 0 011.5-1.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Payroll</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Monthly salary records</p>
                    </div>
                </div>
            </a>

            {{-- Reports --}}
            <a href="{{ route('report.index') }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-400 block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7.5 14.25l3-3 3 3 5.25-6"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Reports</h2>
                        <p class="text-xs text-gray-500 mt-0.5">HR analytics & insights</p>
                    </div>
                </div>
            </a>

            {{-- Monthly Report --}}
            <a href="{{ $employees->isNotEmpty() ? route('attendance.monthly-report', ['employee_id' => $employees->first()->id, 'month' => now()->month, 'year' => now()->year]) : route('attendance.monthly-report') }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-400 block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Monthly Report</h2>
                        <p class="text-xs text-gray-500 mt-0.5">View attendance summary</p>
                    </div>
                </div>
            </a>

        </div>
    </div>
</x-app-layout>
