<x-layout title="Dashboard - HR System">
    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-black/5 p-10 max-w-4xl w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8 pb-5 border-b border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Dashboard
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Welcome back, {{ auth()->user()->name ?? 'User' }}!
                </p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-1.5 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-lg font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>

        {{-- Menu Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <a href="/departments"
               class="group flex items-start gap-4 p-6 border border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-900 mb-1">
                        Departments
                    </h2>
                    <p class="text-sm text-gray-500">
                        Manage your departments
                    </p>
                </div>
            </a>

            <a href="#"
               class="group flex items-start gap-4 p-6 border border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-900 mb-1">
                        Employees
                    </h2>
                    <p class="text-sm text-gray-500">
                        Manage your employees
                    </p>
                </div>
            </a>

            <a href="#"
               class="group flex items-start gap-4 p-6 border border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-900 mb-1">
                        Attendance
                    </h2>
                    <p class="text-sm text-gray-500">
                        Track attendance records
                    </p>
                </div>
            </a>

            <a href="#"
               class="group flex items-start gap-4 p-6 border border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-900 mb-1">
                        Reports
                    </h2>
                    <p class="text-sm text-gray-500">
                        View reports
                    </p>
                </div>
            </a>

        </div>

    </div>
</x-layout>
