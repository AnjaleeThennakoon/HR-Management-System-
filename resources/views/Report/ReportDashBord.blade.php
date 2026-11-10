<x-app-layout title="Reports Dashboard - HR System">
    <div class="p-6">
        <div class="bg-white border border-gray-200 rounded-xl p-6 sm:p-8">
            <header class="flex flex-wrap items-start justify-between gap-4 mb-6 pb-5 border-b border-gray-200">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">HR reports</p>
                    <h1 class="mt-1 text-xl font-bold text-gray-900">Reports Dashboard</h1>
                    <p class="mt-1 text-sm text-gray-500">Choose a report to review attendance and staff leave.</p>
                </div>
            </header>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('report.show') }}" class="group flex flex-col rounded-xl border border-gray-200 bg-white p-5 hover:border-gray-900">
                    <span class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-800">
                        <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M8 2.75v3M16 2.75v3M3.75 9.25h16.5M5.5 4.75h13A1.75 1.75 0 0 1 20.25 6.5v12A1.75 1.75 0 0 1 18.5 20.25h-13A1.75 1.75 0 0 1 3.75 18.5v-12A1.75 1.75 0 0 1 5.5 4.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 13h.01M12 13h.01M16 13h.01M8 16.5h.01M12 16.5h.01"/></svg>
                    </span>
                    <h2 class="text-sm font-bold text-gray-900">Daily Report</h2>
                    <p class="mt-1 text-xs text-gray-500">Review attendance and arrival details for a selected day.</p>
                    <span class="mt-4 pt-2 text-xs font-semibold text-gray-900 border-t border-gray-100">Open report &rarr;</span>
                </a>

                <a href="{{ route('report.monthlyReport') }}" class="group flex flex-col rounded-xl border border-gray-200 bg-white p-5 hover:border-gray-900">
                    <span class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-800">
                        <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.25h16M5.5 16V9.5M10 16V5.75M14.5 16v-4.25M19 16V7.5"/><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 7 5-2.75 4.5 4 5.5-3"/></svg>
                    </span>
                    <h2 class="text-sm font-bold text-gray-900">Monthly Report</h2>
                    <p class="mt-1 text-xs text-gray-500">Compare staff attendance, overtime, late time, and leave totals.</p>
                    <span class="mt-4 pt-2 text-xs font-semibold text-gray-900 border-t border-gray-100">Open report &rarr;</span>
                </a>

                <a href="{{ route('report.monthlyLeaveReport') }}" class="group flex flex-col rounded-xl border border-gray-200 bg-white p-5 hover:border-gray-900">
                    <span class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-800">
                        <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6.25 3.75h8l4.5 4.5v12h-12.5a1.75 1.75 0 0 1-1.75-1.75V5.5a1.75 1.75 0 0 1 1.75-1.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 4v5h5M8 13h8M8 16.5h8"/></svg>
                    </span>
                    <h2 class="text-sm font-bold text-gray-900">Monthly Leave</h2>
                    <p class="mt-1 text-xs text-gray-500">Review leave dates, staff names, reasons, and approval status.</p>
                    <span class="mt-4 pt-2 text-xs font-semibold text-gray-900 border-t border-gray-100">Open report &rarr;</span>
                </a>

                <a href="{{ route('report.lateHoursReport') }}" class="group flex flex-col rounded-xl border border-gray-200 bg-white p-5 hover:border-gray-900">
                    <span class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-800">
                        <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="8.75"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3.25 2"/></svg>
                    </span>
                    <h2 class="text-sm font-bold text-gray-900">Late Hours</h2>
                    <p class="mt-1 text-xs text-gray-500">Review late arrivals and hours within a selected date range.</p>
                    <span class="mt-4 pt-2 text-xs font-semibold text-gray-900 border-t border-gray-100">Open report &rarr;</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>