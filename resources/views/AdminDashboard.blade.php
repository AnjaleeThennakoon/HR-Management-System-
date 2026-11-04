<x-layout title="Dashboard - HR System">

    {{-- Page background: subtle gradient --}}
    <style>
        body { background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 50%, #f0fdf4 100%) !important; }
    </style>

    <div class="w-full max-w-4xl">

        {{-- Card shell --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl shadow-indigo-100/60 ring-1 ring-black/[0.06] p-8 md:p-10">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
                <div>
                    <div class="inline-flex items-center gap-2 mb-2">
                        <span class="h-2 w-2 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500"></span>
                        <span class="text-xs font-semibold tracking-widest text-indigo-500 uppercase">Admin Panel</span>
                    </div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        Dashboard
                    </h1>
                    <p class="text-sm text-gray-400 mt-1 font-medium">
                        Welcome back, <span class="text-indigo-600 font-semibold">{{ auth()->user()->name ?? 'User' }}</span>!
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 text-sm text-red-500 hover:text-white bg-red-50 hover:bg-red-500 border border-red-100 hover:border-red-500 px-4 py-2 rounded-xl font-semibold shadow-sm hover:shadow-md transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

            {{-- Menu Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Departments --}}
                <a href="{{ route('departments.index') }}"
                   class="group relative flex items-start gap-4 p-6 bg-gradient-to-br from-white to-indigo-50/40 border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg hover:shadow-indigo-100/60 hover:-translate-y-1 hover:border-indigo-200 transition-all duration-200 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-indigo-500/0 group-hover:from-indigo-500/5 group-hover:to-violet-500/5 transition-all duration-200 rounded-2xl"></div>
                    <div class="relative flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-600 group-hover:from-indigo-600 group-hover:to-violet-600 group-hover:text-white shadow-sm transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h2 class="text-base font-bold text-gray-800 mb-0.5 group-hover:text-indigo-700 transition-colors duration-200">Departments</h2>
                        <p class="text-sm text-gray-400 font-medium">Manage your departments</p>
                    </div>
                </a>

                {{-- Designations --}}
                <a href="{{ route('designations.index') }}"
                   class="group relative flex items-start gap-4 p-6 bg-gradient-to-br from-white to-violet-50/40 border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg hover:shadow-violet-100/60 hover:-translate-y-1 hover:border-violet-200 transition-all duration-200 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-violet-500/0 to-purple-500/0 group-hover:from-violet-500/5 group-hover:to-purple-500/5 transition-all duration-200 rounded-2xl"></div>
                    <div class="relative flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-100 to-violet-50 text-violet-600 group-hover:from-violet-600 group-hover:to-purple-600 group-hover:text-white shadow-sm transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.073a2.25 2.25 0 01-1.35 2.07A48.108 48.108 0 0112 21.75a48.108 48.108 0 01-6.9-.457 2.25 2.25 0 01-1.35-2.07V14.15M18.75 6.75a2.25 2.25 0 00-2.25-2.25H7.5A2.25 2.25 0 005.25 6.75v9.75a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25V6.75zM12 12.75a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h2 class="text-base font-bold text-gray-800 mb-0.5 group-hover:text-violet-700 transition-colors duration-200">Designations</h2>
                        <p class="text-sm text-gray-400 font-medium">Manage your designations</p>
                    </div>
                </a>

                {{-- Employees --}}
                <a href="{{ route('employees.index') }}"
                   class="group relative flex items-start gap-4 p-6 bg-gradient-to-br from-white to-sky-50/40 border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg hover:shadow-sky-100/60 hover:-translate-y-1 hover:border-sky-200 transition-all duration-200 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-500/0 to-blue-500/0 group-hover:from-sky-500/5 group-hover:to-blue-500/5 transition-all duration-200 rounded-2xl"></div>
                    <div class="relative flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-sky-100 to-sky-50 text-sky-600 group-hover:from-sky-600 group-hover:to-blue-600 group-hover:text-white shadow-sm transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h2 class="text-base font-bold text-gray-800 mb-0.5 group-hover:text-sky-700 transition-colors duration-200">Employees</h2>
                        <p class="text-sm text-gray-400 font-medium">Manage your employees</p>
                    </div>
                </a>

                {{-- Holidays --}}
                <a href="{{ route('holidays.index') }}"
                   class="group relative flex items-start gap-4 p-6 bg-gradient-to-br from-white to-amber-50/40 border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg hover:shadow-amber-100/60 hover:-translate-y-1 hover:border-amber-200 transition-all duration-200 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-orange-500/0 group-hover:from-amber-500/5 group-hover:to-orange-500/5 transition-all duration-200 rounded-2xl"></div>
                    <div class="relative flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 text-amber-600 group-hover:from-amber-500 group-hover:to-orange-500 group-hover:text-white shadow-sm transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-13.5-6h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75zm3-3h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75zm3-3h.008v.008h-.008V12.75zm0 3h.008v.008h-.008V15.75z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h2 class="text-base font-bold text-gray-800 mb-0.5 group-hover:text-amber-700 transition-colors duration-200">Holidays</h2>
                        <p class="text-sm text-gray-400 font-medium">Manage company holidays</p>
                    </div>
                </a>

                {{-- Attendance --}}
                <a href="{{ route('attendance.index') }}"
                   class="group relative flex items-start gap-4 p-6 bg-gradient-to-br from-white to-emerald-50/40 border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg hover:shadow-emerald-100/60 hover:-translate-y-1 hover:border-emerald-200 transition-all duration-200 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-teal-500/0 group-hover:from-emerald-500/5 group-hover:to-teal-500/5 transition-all duration-200 rounded-2xl"></div>
                    <div class="relative flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-50 text-emerald-600 group-hover:from-emerald-600 group-hover:to-teal-600 group-hover:text-white shadow-sm transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h2 class="text-base font-bold text-gray-800 mb-0.5 group-hover:text-emerald-700 transition-colors duration-200">Attendance</h2>
                        <p class="text-sm text-gray-400 font-medium">Track attendance records</p>
                    </div>
                </a>

                {{-- Leaves --}}
                <a href="{{ route('leaves.index') }}"
                   class="group relative flex items-start gap-4 p-6 bg-gradient-to-br from-white to-rose-50/40 border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg hover:shadow-rose-100/60 hover:-translate-y-1 hover:border-rose-200 transition-all duration-200 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-500/0 to-pink-500/0 group-hover:from-rose-500/5 group-hover:to-pink-500/5 transition-all duration-200 rounded-2xl"></div>
                    <div class="relative flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-100 to-rose-50 text-rose-600 group-hover:from-rose-600 group-hover:to-pink-600 group-hover:text-white shadow-sm transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h2 class="text-base font-bold text-gray-800 mb-0.5 group-hover:text-rose-700 transition-colors duration-200">Leaves</h2>
                        <p class="text-sm text-gray-400 font-medium">Manage employee leaves</p>
                    </div>
                </a>

                {{-- Monthly Payroll --}}
                <a href="{{ route('payrolls.index') }}"
                   class="group relative flex items-start gap-4 p-6 bg-gradient-to-br from-white to-cyan-50/40 border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg hover:shadow-cyan-100/60 hover:-translate-y-1 hover:border-cyan-200 transition-all duration-200 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/0 to-blue-500/0 group-hover:from-cyan-500/5 group-hover:to-blue-500/5 transition-all duration-200 rounded-2xl"></div>
                    <div class="relative flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-100 to-cyan-50 text-cyan-600 group-hover:from-cyan-600 group-hover:to-blue-600 group-hover:text-white shadow-sm transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9.75h19.5m-16.5 3h3m-3 3h3m-3 3h3m6-6h3m-3 3h3m-3 3h3M3.75 4.5h16.5A1.5 1.5 0 0121.75 6v12a1.5 1.5 0 01-1.5 1.5H3.75A1.5 1.5 0 012.25 18V6a1.5 1.5 0 011.5-1.5z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h2 class="text-base font-bold text-gray-800 mb-0.5 group-hover:text-cyan-700 transition-colors duration-200">Monthly Payroll</h2>
                        <p class="text-sm text-gray-400 font-medium">View generated monthly payroll and salary details</p>
                    </div>
                </a>

                {{-- Monthly Report --}}
                <a href="{{ $employees->isNotEmpty() ? route('attendance.monthly-report', [
                        'employee_id' => $employees->first()->id,
                        'month'       => now()->month,
                        'year'        => now()->year,
                    ]) : route('attendance.monthly-report') }}"
                   class="group relative flex items-start gap-4 p-6 bg-gradient-to-br from-white to-purple-50/40 border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg hover:shadow-purple-100/60 hover:-translate-y-1 hover:border-purple-200 transition-all duration-200 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 to-fuchsia-500/0 group-hover:from-purple-500/5 group-hover:to-fuchsia-500/5 transition-all duration-200 rounded-2xl"></div>
                    <div class="relative flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 text-purple-600 group-hover:from-purple-600 group-hover:to-fuchsia-600 group-hover:text-white shadow-sm transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h2 class="text-base font-bold text-gray-800 mb-0.5 group-hover:text-purple-700 transition-colors duration-200">Monthly Report</h2>
                        <p class="text-sm text-gray-400 font-medium">View monthly attendance reports</p>
                    </div>
                </a>

                {{-- Reports --}}
                <a href="{{ route('report.index') }}"
                   class="group relative md:col-span-2 flex items-start gap-4 p-6 bg-gradient-to-br from-white to-slate-50/60 border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg hover:shadow-slate-100/60 hover:-translate-y-1 hover:border-slate-300 transition-all duration-200 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-500/0 to-gray-500/0 group-hover:from-slate-500/5 group-hover:to-gray-500/5 transition-all duration-200 rounded-2xl"></div>
                    <div class="relative flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-slate-100 to-slate-50 text-slate-600 group-hover:from-slate-700 group-hover:to-gray-700 group-hover:text-white shadow-sm transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7.5 14.25l3-3 3 3 5.25-6" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h2 class="text-base font-bold text-gray-800 mb-0.5 group-hover:text-slate-700 transition-colors duration-200">Reports</h2>
                        <p class="text-sm text-gray-400 font-medium">View HR reports and workforce activity</p>
                    </div>
                </a>

            </div>
        </div>
    </div>

</x-layout>
