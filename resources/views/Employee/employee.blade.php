<x-layout title="Employee Dashboard - HR System">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-3xl w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <div>

                <p class="text-sm text-gray-500 mt-1">
                    Welcome back, {{ auth()->user()->name }}!
                </p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                    Logout
                </button>
            </form>
        </div>

        {{-- Welcome Message --}}
        <div class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-lg">
            <p class="text-sm text-indigo-800">
                <strong>Welcome to HR System!</strong> This is your personal dashboard.
            </p>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <a href="#" class="block p-5 border border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 flex items-center justify-center bg-indigo-100 rounded-lg text-xl">

                    </div>
                    <h2 class="text-base font-semibold text-gray-800">My Profile</h2>
                </div>
                <p class="text-sm text-gray-500">View and update your personal information</p>
            </a>

            <a href="#" class="block p-5 border border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 flex items-center justify-center bg-green-100 rounded-lg text-xl">

                    </div>
                    <h2 class="text-base font-semibold text-gray-800">My Attendance</h2>
                </div>
                <p class="text-sm text-gray-500">Mark attendance and view your records</p>
            </a>

            <a href="#" class="block p-5 border border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 flex items-center justify-center bg-yellow-100 rounded-lg text-xl">

                    </div>
                    <h2 class="text-base font-semibold text-gray-800">My Leaves</h2>
                </div>
                <p class="text-sm text-gray-500">Request leave and view leave history</p>
            </a>

            <a href="#" class="block p-5 border border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 flex items-center justify-center bg-purple-100 rounded-lg text-xl">

                    </div>
                    <h2 class="text-base font-semibold text-gray-800">My Payslip</h2>
                </div>
                <p class="text-sm text-gray-500">Download your monthly payslips</p>
            </a>

        </div>

        {{-- Footer --}}
        <div class="mt-6 pt-4 border-t text-xs text-gray-400 text-center">
            HR System &copy; {{ date('Y') }}
        </div>

    </div>
</x-layout>
