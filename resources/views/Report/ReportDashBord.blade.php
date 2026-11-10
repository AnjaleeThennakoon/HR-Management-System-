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
            <div class="mt-7 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <a href="{{ route('report.show') }}" class="group flex min-h-52 flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-100/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition group-hover:bg-indigo-600 group-hover:text-white">
                        <svg aria-hidden="true" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M8 2.75v3M16 2.75v3M3.75 9.25h16.5M5.5 4.75h13A1.75 1.75 0 0 1 20.25 6.5v12A1.75 1.75 0 0 1 18.5 20.25h-13A1.75 1.75 0 0 1 3.75 18.5v-12A1.75 1.75 0 0 1 5.5 4.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 13h.01M12 13h.01M16 13h.01M8 16.5h.01M12 16.5h.01"/></svg>
                    </span>
                    <h2 class="text-lg font-bold text-slate-900 transition group-hover:text-indigo-700">Daily Report</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Review attendance and arrival details for a selected day.</p>
                    <span class="mt-auto pt-4 text-sm font-semibold text-indigo-600">Open report <span aria-hidden="true">&rarr;</span></span>
                </a>

                <a href="{{ route('report.monthlyReport') }}" class="group flex min-h-52 flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-sky-300 hover:shadow-lg hover:shadow-sky-100/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2">
                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition group-hover:bg-sky-600 group-hover:text-white">
                        <svg aria-hidden="true" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.25h16M5.5 16V9.5M10 16V5.75M14.5 16v-4.25M19 16V7.5"/><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 7 5-2.75 4.5 4 5.5-3"/></svg>
                    </span>
                    <h2 class="text-lg font-bold text-slate-900 transition group-hover:text-sky-700">Monthly Report</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Compare staff attendance, overtime, late time, and leave totals.</p>
                    <span class="mt-auto pt-4 text-sm font-semibold text-sky-600">Open report <span aria-hidden="true">&rarr;</span></span>
                </a>

                <a href="{{ route('report.monthlyLeaveReport') }}" class="group flex min-h-52 flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-100/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-600 group-hover:text-white">
                        <svg aria-hidden="true" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6.25 3.75h8l4.5 4.5v12h-12.5a1.75 1.75 0 0 1-1.75-1.75V5.5a1.75 1.75 0 0 1 1.75-1.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 4v5h5M8 13h8M8 16.5h8"/></svg>
                    </span>
                    <h2 class="text-lg font-bold text-slate-900 transition group-hover:text-emerald-700">Monthly Leave</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Review leave dates, staff names, reasons, and approval status.</p>
                    <span class="mt-auto pt-4 text-sm font-semibold text-emerald-600">Open report <span aria-hidden="true">&rarr;</span></span>
                </a>

                <a href="{{ route('report.lateHoursReport') }}" class="group flex min-h-52 flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg hover:shadow-amber-100/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 focus-visible:ring-offset-2">
                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition group-hover:bg-amber-500 group-hover:text-white">
                        <svg aria-hidden="true" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="8.75"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3.25 2"/></svg>
                    </span>
                    <h2 class="text-lg font-bold text-slate-900 transition group-hover:text-amber-700">Late Hours</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Review late arrivals and hours within a selected date range.</p>
                    <span class="mt-auto pt-4 text-sm font-semibold text-amber-700">Open report <span aria-hidden="true">&rarr;</span></span>
                </a>
            </div>

        </section>
    </main>
</x-layout>
