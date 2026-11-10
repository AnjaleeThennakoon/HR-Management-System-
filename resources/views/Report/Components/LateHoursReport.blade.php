<x-layout title="Late Hours Report - HR System">
    <main class="mx-auto w-full max-w-7xl space-y-6 p-4 sm:p-6">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <header class="border-b border-slate-200 pb-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Attendance reports</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-900">Late Hours Report</h1>
                        <p class="mt-2 text-sm text-slate-500">Review late arrivals during a selected date range.</p>
                    </div>
                    <a href="{{ route('report.index') }}" class="shrink-0 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Reports Dashboard
                    </a>
                </div>

                <nav class="mt-5 flex flex-wrap border-b border-slate-200" aria-label="Attendance report tabs">
                    <a href="{{ route('report.show') }}" class="border-b-2 border-transparent px-5 py-3 text-sm font-semibold text-slate-500 transition hover:border-indigo-300 hover:text-indigo-700">Daily Report</a>
                    <a href="{{ route('report.monthlyReport') }}" class="border-b-2 border-transparent px-5 py-3 text-sm font-semibold text-slate-500 transition hover:border-indigo-300 hover:text-indigo-700">Monthly Report</a>
                    <span aria-current="page" class="border-b-2 border-indigo-600 px-5 py-3 text-sm font-semibold text-indigo-700">Late Hours Report</span>
                </nav>
            </header>

            <form method="GET" action="" class="mt-6 flex flex-wrap items-end gap-3">
                <div>
                    <label for="reportStart" class="mb-1 block text-sm font-semibold text-slate-700">Start date</label>
                    <input id="reportStart" name="start" type="date" value="{{ request()->query('start', '') }}" required class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="reportEnd" class="mb-1 block text-sm font-semibold text-slate-700">End date</label>
                    <input id="reportEnd" name="end" type="date" value="{{ request()->query('end', '') }}" required class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">View Late Hours</button>
            </form>

            @if (request()->filled('start') && request()->filled('end'))
                <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full min-w-[900px] text-left text-sm">
                        <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Date (ASC)</th>
                                <th class="px-4 py-3">Emp ID</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Shift In</th>
                                <th class="px-4 py-3">In Time</th>
                                <th class="px-4 py-3 text-right">Late Hours</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse (collect($report)->sortBy('date') as $lateArrival)
                                @php
                                    $lateMinutes = (int) ($lateArrival['late_minutes'] ?? 0);
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-4 py-3 text-slate-700">{{ $lateArrival['date'] }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $lateArrival['employee_id'] ?? '—' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $lateArrival['employee'] }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $lateArrival['scheduled_in'] }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $lateArrival['actual_in'] }}</td>
                                    <td class="px-4 py-3 text-right font-medium text-amber-700">{{ number_format($lateMinutes / 60, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-500">No late arrivals found for this date range.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                    <p class="text-sm font-semibold text-slate-700">Select a date range to view the late hours report.</p>
                </div>
            @endif
        </section>
    </main>
</x-layout>
