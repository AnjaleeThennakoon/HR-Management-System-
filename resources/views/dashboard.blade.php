<x-layout title="Dashboard - HR System">
    <div class="bg-white rounded-lg shadow-lg p-10 max-w-2xl w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8 border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Dashboard
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Welcome back, {{ auth()->user()->name ?? 'User' }}!
                </p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-sm text-red-600 hover:text-red-800 font-medium">
                    Logout
                </button>
            </form>
        </div>

        {{-- Menu Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <a href="/departments"
               class="block p-6 border border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-md transition">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">
                     Departments
                </h2>
                <p class="text-sm text-gray-500">
                    Manage your departments
                </p>
            </a>

            <a href="#"
               class="block p-6 border border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-md transition">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">
                    Employees
                </h2>
                <p class="text-sm text-gray-500">
                    Manage your employees
                </p>
            </a>

            <a href="#"
               class="block p-6 border border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-md transition">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">
                     Attendance
                </h2>
                <p class="text-sm text-gray-500">
                    Track attendance records
                </p>
            </a>

            <a href="#"
               class="block p-6 border border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-md transition">
                <h2 class="text-lg font-semibold text-gray-800 mb-1">
                     Reports
                </h2>
                <p class="text-sm text-gray-500">
                    View reports
                </p>
            </a>

        </div>

    </div>
</x-layout>
