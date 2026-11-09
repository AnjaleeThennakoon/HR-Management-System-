<x-layout title="Monthly Leave Report - HR System">
    <main class="mx-auto w-full max-w-7xl space-y-6 p-4 sm:p-6">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <header class="border-b border-slate-200 pb-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Leave reports</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-900">Monthly Leave Report</h1>
                    </div>
                    <a href="{{ route('report.index') }}" class="shrink-0 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Reports Dashboard
                    </a>
                </div>

                <form method="GET" action="{{ route('report.monthlyLeaveReport') }}" class="mt-6 flex flex-wrap items-end gap-3">
                    <div>
                        <label for="reportMonth" class="mb-1 block text-sm font-semibold text-slate-700">Select month</label>
                        <input id="reportMonth" name="month" type="month" value="{{ $month ?? '' }}" required class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                        View Leave Report
                    </button>
                </form>
            </header>

            @if ($month)
                <div class="mt-6 overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="px-4 py-3">Start Date</th>
                                <th class="px-4 py-3">End Date</th>
                                <th class="px-4 py-3">Staff Name</th>
                                <th class="px-4 py-3">Reason</th>
                                <th class="px-4 py-3">Leave Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php($hasLeaves = false)
                            @foreach ($report as $employee)
                                @foreach ($employee['leaves'] as $leave)
                                    @php($hasLeaves = true)
                                    <tr class="hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-4 py-3 text-slate-700">{{ $leave['start_date'] }}</td>
                                        <td class="whitespace-nowrap px-4 py-3 text-slate-700">{{ $leave['end_date'] }}</td>
                                        <td class="px-4 py-3 font-medium text-slate-800">{{ $employee['first_name'] }} {{ $employee['last_name'] }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $leave['reason'] }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold capitalize text-slate-700">{{ $leave['status'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            @if (!$hasLeaves)
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-slate-500">No leave records found for this month.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                    <p class="text-sm font-semibold text-slate-700">Select a month to view staff leave dates, reasons, and status.</p>
                </div>
            @endif
        </section>
    </main>
</x-layout>
