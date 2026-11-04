<x-layout title="Attendance Reports - HR System">
    <main class="mx-auto w-full max-w-5xl space-y-6 p-4 sm:p-6">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">HR reports</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">Attendance Reports</h1>
            <p class="mt-2 text-sm text-slate-500">Choose which attendance report you want to view.</p>

            <nav class="mt-6 flex border-b border-slate-200" aria-label="Attendance report types">
                <a href="{{ route('report.show') }}" class="border-b-2 border-transparent px-5 py-3 text-sm font-semibold text-slate-500 transition hover:border-indigo-300 hover:text-indigo-700">
                    Daily Report
                </a>
                <a href="{{ route('report.monthlyReport') }}" class="border-b-2 border-transparent px-5 py-3 text-sm font-semibold text-slate-500 transition hover:border-indigo-300 hover:text-indigo-700">
                    Monthly Report
                </a>
            </nav>

            <p class="mt-6 rounded-xl bg-slate-50 p-5 text-sm text-slate-600">Use the tabs above to open the daily or monthly attendance report.</p>
        </section>
    </main>
</x-layout>
