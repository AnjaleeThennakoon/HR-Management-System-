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




</div>
</div>
</x-app-layout>
