<x-layout title="Daily Attendance Report - HR System">
    <main class="mx-auto w-full max-w-7xl space-y-6 p-4 sm:p-6">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <header class="border-b border-slate-200 pb-5">
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Attendance reports</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Daily Attendance Report</h1>

                <nav class="mt-5 flex border-b border-slate-200" aria-label="Attendance report tabs">
                    <a href="{{ route('report.show') }}" aria-current="page" class="border-b-2 border-indigo-600 px-5 py-3 text-sm font-semibold text-indigo-700">
                        Daily Report
                    </a>
                    <a href="{{ route('report.monthlyReport') }}" class="border-b-2 border-transparent px-5 py-3 text-sm font-semibold text-slate-500 transition hover:border-indigo-300 hover:text-indigo-700">
                        Monthly Report
                    </a>
                </nav>
            </header>

            <form method="GET" action="{{ route('report.show') }}" class="mt-6 flex flex-wrap items-end gap-3">
                <div>
                    <label for="reportDate" class="mb-1 block text-sm font-semibold text-slate-700">Select date</label>
                    <input id="reportDate" name="date" type="date" value="{{ $reportDate ?? '' }}" max="{{ now()->toDateString() }}" required class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    View Daily Report
                </button>
            </form>

            @if ($reportDate)
                <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Employee ID</th>
                                <th class="px-4 py-3">Employee Name</th>
                                <th class="px-4 py-3">In Time</th>
                                <th class="px-4 py-3">Out Time</th>
                                <th class="px-4 py-3 text-center">Work Hours</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($attendances as $attendance)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $attendance['employee_id'] }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $attendance['first_name'] }} {{ $attendance['last_name'] }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $attendance['in_time'] ?? '—' }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $attendance['out_time'] ?? '—' }}</td>
                                    <td class="px-4 py-3 text-center text-slate-700">{{ $attendance['work_hours'] ?? 0 }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $attendance['status'] === 'Incomplete' ? '—' : $attendance['status'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-500">No employees found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                    <p class="text-sm font-semibold text-slate-700">Select a date to view the report.</p>
                    <p class="mt-1 text-sm text-slate-500">The attendance table will appear after you choose a date.</p>
                </div>
            @endif
        </section>
    </main>
</x-layout>
