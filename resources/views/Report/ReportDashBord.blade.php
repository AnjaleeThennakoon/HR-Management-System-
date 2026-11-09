<x-layout title="Reports Dashboard - HR System">
    <main class="mx-auto w-full max-w-6xl space-y-6 p-4 sm:p-6">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <header class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">HR reports</p>
                    <h1 class="mt-1 text-2xl font-bold text-slate-900">Reports Dashboard</h1>
                    <p class="mt-2 text-sm text-slate-500">Choose a report to review attendance and staff leave.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="shrink-0 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Back to Dashboard
                </a>
            </header>
            <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('report.show') }}" class="group rounded-xl border border-slate-200 p-5 transition hover:border-indigo-300 hover:bg-indigo-50/50">
                    <p class="text-sm font-semibold text-indigo-600">Attendance</p>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 group-hover:text-indigo-700">Daily Report</h2>
                    <p class="mt-1 text-sm text-slate-500">Review attendance for a selected date.</p>
                </a>
                <a href="{{ route('report.monthlyReport') }}" class="group rounded-xl border border-slate-200 p-5 transition hover:border-indigo-300 hover:bg-indigo-50/50">
                    <p class="text-sm font-semibold text-indigo-600">Attendance</p>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 group-hover:text-indigo-700">Monthly Report</h2>
                    <p class="mt-1 text-sm text-slate-500">View attendance and leave totals by month.</p>
                </a>
                <a href="{{ route('report.monthlyLeaveReport') }}" class="group rounded-xl border border-indigo-200 bg-indigo-50/60 p-5 transition hover:border-indigo-400 hover:bg-indigo-50">
                    <p class="text-sm font-semibold text-indigo-600">Leave</p>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 group-hover:text-indigo-700">Monthly Leave Report</h2>
                    <p class="mt-1 text-sm text-slate-500">Filter staff leave by month and review dates, reasons, and status.</p>
                </a>
            </div>

        </section>
    </main>
</x-layout>
