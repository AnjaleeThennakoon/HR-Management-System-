<x-layout title="Monthly Attendance Report - HR System">

    {{-- Locked Page Layout --}}
    <div class="flex h-[calc(100vh-65px)] flex-col gap-4 overflow-hidden bg-slate-50/50 p-4">

        {{-- Header: Does Not Scroll --}}
        <header class="flex shrink-0 items-center justify-between gap-3">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-800">
                        Monthly Attendance
                    </h1>
                    <p class="text-xs text-slate-500">
                        Track daily attendance, late hours and overtime
                    </p>
                </div>
            </div>

            <a href="{{ route('attendance.index') }}"
               class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span class="hidden sm:inline">Back to Attendance</span>
                <span class="sm:hidden">Back</span>
            </a>
        </header>

        {{-- Main Layout: Fixed Height --}}
        <div class="grid min-h-0 flex-1 grid-cols-1 gap-4 overflow-hidden lg:grid-cols-[260px_minmax(0,1fr)]">

            {{-- Sidebar --}}
            <aside class="flex min-h-0 flex-col gap-4 overflow-y-auto custom-scrollbar">

                {{-- Filters --}}
                <form method="GET"
                      action="{{ route('attendance.monthly-report') }}"
                      class="shrink-0">

                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

                        <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                            <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                Report Filters
                            </h2>
                        </div>

                        <div class="space-y-4 p-4">

                            {{-- Month --}}
                            <div>
                                <label for="month"
                                       class="mb-1.5 block text-xs font-semibold text-slate-500">
                                    Month & Year
                                </label>

                                <input
                                    type="month"
                                    name="month"
                                    id="month"
                                    value="{{ request('month', now()->format('Y-m')) }}"
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>

                            {{-- Employee: Single Dropdown --}}
                            <div>
                                <label for="employee_select"
                                       class="mb-1.5 block text-xs font-semibold text-slate-500">
                                    Employee
                                </label>

                                <select
                                    name="employee_id"
                                    id="employee_select"
                                    class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="" class="bg-slate-50 text-slate-500">Select Employee</option>

                                    @foreach ($employees ?? [] as $emp)
                                        <option
                                            value="{{ $emp->id }}"
                                            class="bg-slate-50 text-slate-700"
                                            @selected(($selectedEmployeeId ?? null) == $emp->id)
                                        >
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button
                                type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-indigo-700"
                            >
                                Generate Report
                            </button>

                        </div>
                    </div>
                </form>

                @if ($employee)

                    {{-- Employee Profile --}}
                    <section class="shrink-0 rounded-xl border border-slate-200 bg-white p-4">

                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Employee Profile
                        </h2>

                        <div class="mt-3 flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 font-bold text-indigo-700">
                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-slate-800">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Employee
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">

                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs text-slate-500">Shift In</p>
                                <p class="mt-1 font-bold text-slate-700">
                                    {{ $employee->shift_in ? substr($employee->shift_in, 0, 5) : '08:00' }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs text-slate-500">Shift Out</p>
                                <p class="mt-1 font-bold text-slate-700">
                                    {{ $employee->shift_out ? substr($employee->shift_out, 0, 5) : '17:00' }}
                                </p>
                            </div>

                        </div>
                    </section>

                    {{-- Monthly Totals --}}
                    <section class="shrink-0 rounded-xl border border-slate-200 bg-white p-4">

                        <h2 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">
                            Monthly Totals
                        </h2>

                        <div class="grid grid-cols-2 gap-3">

                            <div class="rounded-lg bg-rose-50 p-3">
                                <p class="text-xs font-semibold text-rose-600">Late</p>
                                <p class="mt-1 text-lg font-extrabold text-rose-700">
                                    {{ number_format($totalLateMinutes ?? 0) }}
                                    <span class="text-xs">mins</span>
                                </p>
                            </div>

                            <div class="rounded-lg bg-indigo-50 p-3">
                                <p class="text-xs font-semibold text-indigo-600">Overtime</p>
                                <p class="mt-1 text-lg font-extrabold text-indigo-700">
                                    {{ number_format($totalOtMinutes ?? 0) }}
                                    <span class="text-xs">mins</span>
                                </p>
                            </div>

                        </div>
                    </section>

                @endif

            </aside>

            {{-- Attendance Table: Independent Scroll Container --}}
            <main class="flex min-h-0 min-w-0 flex-col overflow-hidden rounded-xl border border-slate-200 bg-white">

                @if ($employee)

                    @php
                        $year = (int) ($selectedYear ?? date('Y'));
                        $month = (int) ($selectedMonthNum ?? date('n'));

                        $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
                        $totalDays = $startOfMonth->daysInMonth;
                    @endphp

                    {{-- Table Title: Fixed --}}
                    <div class="flex shrink-0 flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 py-3">

                        <div>
                            <h2 class="font-bold text-slate-800">
                                {{ $startOfMonth->format('F Y') }}
                            </h2>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="rounded-md bg-emerald-50 px-2 py-1 font-semibold text-emerald-700">
                                On Time
                            </span>

                            <span class="rounded-md bg-rose-50 px-2 py-1 font-semibold text-rose-700">
                                Late
                            </span>

                            <span class="rounded-md bg-indigo-50 px-2 py-1 font-semibold text-indigo-700">
                                Overtime
                            </span>
                        </div>

                    </div>

                    {{-- ONLY THIS CONTAINER SCROLLS --}}
                    <div class="min-h-0 flex-1 overflow-auto custom-scrollbar">

                        <table class="w-full min-w-[700px] table-fixed border-collapse text-left text-xs">

                            {{-- Sticky Column Headers --}}
                            <thead class="sticky top-0 z-20 bg-slate-100 text-[11px] uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="w-[15%] border-b border-slate-200 px-3 py-3 font-bold">
                                    Date
                                </th>

                                <th class="w-[15%] border-b border-slate-200 px-2 py-3 font-bold">
                                    In Time
                                </th>

                                <th class="w-[15%] border-b border-slate-200 px-2 py-3 font-bold">
                                    Out Time
                                </th>

                                <th class="w-[18%] border-b border-slate-200 px-2 py-3 font-bold">
                                    Late Time
                                </th>

                                <th class="w-[20%] border-b border-slate-200 px-2 py-3 font-bold">
                                    Over Time
                                </th>

                                <th class="w-[17%] border-b border-slate-200 px-2 py-3 text-right font-bold">
                                    Action
                                </th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">

                            @for ($day = 1; $day <= $totalDays; $day++)

                                @php
                                    $dateObj = \Carbon\Carbon::createFromDate($year, $month, $day);
                                    $dateStr = $dateObj->toDateString();
                                    $isWeekend = $dateObj->isWeekend();

                                    $holiday = ($holidays ?? collect())->first(
                                        fn ($h) => \Carbon\Carbon::parse($h->date)->toDateString() === $dateStr
                                    );

                                    $leave = ($leaves ?? collect())->first(function ($l) use ($dateStr) {
                                        $leaveStart = \Carbon\Carbon::parse($l->start_date)->toDateString();
                                        $leaveEnd = \Carbon\Carbon::parse($l->end_date ?? $l->start_date)->toDateString();

                                        return $dateStr >= $leaveStart && $dateStr <= $leaveEnd;
                                    });

                                    $att = $attendances[$dateStr] ?? null;

                                    $rowType = $holiday
                                        ? 'holiday'
                                        : ($leave
                                            ? 'leave'
                                            : (($isWeekend && !$att) ? 'weekend' : 'normal'));
                                @endphp

                                <tr
                                    class="attendance-row {{ $rowType === 'holiday'
                                            ? 'bg-rose-50/50'
                                            : ($rowType === 'leave'
                                                ? 'bg-amber-50/50'
                                                : ($rowType === 'weekend'
                                                    ? 'bg-slate-50'
                                                    : 'hover:bg-indigo-50/40')) }}"
                                    data-attendance-id="{{ $att->id ?? '' }}"
                                    data-employee-id="{{ $employee->id }}"
                                    data-date="{{ $dateStr }}"
                                >

                                    {{-- Date --}}
                                    <td class="whitespace-nowrap px-3 py-3 font-semibold text-slate-700">
                                        {{ $dateObj->format('d M') }}
                                        <span class="text-slate-400">
                                                ({{ $dateObj->format('D') }})
                                            </span>
                                    </td>

                                    @if ($rowType === 'holiday')

                                        <td colspan="4" class="px-2 py-3">
                                                <span class="rounded-md bg-rose-100 px-2 py-1 font-semibold text-rose-700">
                                                    {{ $holiday->name ?? 'Public Holiday' }}
                                                </span>
                                        </td>

                                    @elseif ($rowType === 'leave')

                                        <td colspan="4" class="px-2 py-3">
                                                <span class="rounded-md bg-amber-100 px-2 py-1 font-semibold text-amber-700">
                                                    {{ $leave->leave_type ?? 'On Leave' }}
                                                </span>
                                        </td>

                                    @elseif ($rowType === 'weekend')

                                        <td colspan="4" class="px-2 py-3 italic text-slate-400">
                                            Weekend (Off)
                                        </td>

                                    @else

                                        {{-- In Time --}}
                                        <td class="px-2 py-3">
                                            @if ($att && $att->in_time)
                                                <span class="rounded-md bg-emerald-50 px-2 py-1 font-semibold text-emerald-700">
                                                        {{ $att->in_time }}
                                                    </span>
                                            @else
                                                <span class="text-slate-300">-</span>
                                            @endif
                                        </td>

                                        {{-- Out Time --}}
                                        <td class="px-2 py-3">
                                            @if ($att && $att->out_time)
                                                <span class="rounded-md bg-indigo-50 px-2 py-1 font-semibold text-indigo-700">
                                                        {{ $att->out_time }}
                                                    </span>
                                            @else
                                                <span class="text-slate-300">-</span>
                                            @endif
                                        </td>

                                        {{-- Late Time --}}
                                        <td class="px-2 py-3">
                                            @if ($att && $att->late_minutes > 0)
                                                <span class="rounded-md bg-rose-50 px-2 py-1 font-semibold text-rose-700">
                                                        {{ $att->late_minutes }} mins
                                                    </span>
                                            @elseif ($att && $att->in_time)
                                                <span class="font-semibold text-emerald-600">
                                                        On Time
                                                    </span>
                                            @else
                                                <span class="text-slate-300">-</span>
                                            @endif
                                        </td>

                                        {{-- Overtime --}}
                                        <td class="px-2 py-3">
                                            @if ($att && $att->ot_minutes > 0)
                                                <span class="rounded-md bg-blue-50 px-2 py-1 font-semibold text-blue-700">
                                                        {{ $att->ot_minutes }} mins
                                                    </span>
                                            @else
                                                <span class="text-slate-300">-</span>
                                            @endif
                                        </td>

                                    @endif

                                    {{-- Actions --}}
                                    <td class="px-2 py-3 text-right">

                                        @if ($rowType === 'normal')

                                            <div class="flex items-center justify-end gap-1">

                                                <div class="view-mode">
                                                    <button
                                                        type="button"
                                                        class="edit-btn rounded-md p-1.5 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600"
                                                        title="Edit attendance"
                                                    >
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                             stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="edit-mode hidden items-center justify-end gap-1">

                                                    <input
                                                        type="time"
                                                        class="in-time-input w-[85px] rounded border border-slate-300 px-1 py-1 text-xs"
                                                        value="{{ $att->in_time ?? '' }}"
                                                    >

                                                    <input
                                                        type="time"
                                                        class="out-time-input w-[85px] rounded border border-slate-300 px-1 py-1 text-xs"
                                                        value="{{ $att->out_time ?? '' }}"
                                                    >

                                                    <button
                                                        type="button"
                                                        class="save-btn rounded bg-emerald-100 p-1.5 text-emerald-700 hover:bg-emerald-200"
                                                        title="Save"
                                                    >
                                                        ✓
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="cancel-btn rounded bg-slate-100 p-1.5 text-slate-600 hover:bg-slate-200"
                                                        title="Cancel"
                                                    >
                                                        ✕
                                                    </button>

                                                </div>
                                            </div>

                                        @endif

                                    </td>

                                </tr>

                            @endfor

                            </tbody>
                        </table>
                    </div>

                @else

                    {{-- Empty State --}}
                    <div class="flex min-h-0 flex-1 flex-col items-center justify-center p-8 text-center">

                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>

                        <h2 class="text-lg font-bold text-slate-800">
                            No Report Generated
                        </h2>

                        <p class="mt-2 max-w-sm text-sm text-slate-500">
                            Select an employee and month, then click Generate Report.
                        </p>

                    </div>

                @endif

            </main>
        </div>
    </div>

    {{-- Scrollbar Styling --}}
    <style>
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
    </style>

    {{-- Attendance Edit Script --}}
    <script>
        document.addEventListener('click', async function (event) {
            const row = event.target.closest('.attendance-row');

            if (!row) {
                return;
            }

            const viewMode = row.querySelector('.view-mode');
            const editMode = row.querySelector('.edit-mode');

            if (!viewMode || !editMode) {
                return;
            }

            // Enter edit mode
            if (event.target.closest('.edit-btn')) {
                viewMode.classList.add('hidden');
                editMode.classList.remove('hidden');
                editMode.classList.add('flex');
            }

            // Cancel editing
            if (event.target.closest('.cancel-btn')) {
                editMode.classList.add('hidden');
                editMode.classList.remove('flex');
                viewMode.classList.remove('hidden');
            }

            // Save attendance
            if (event.target.closest('.save-btn')) {
                const inInput = row.querySelector('.in-time-input');
                const outInput = row.querySelector('.out-time-input');
                const attendanceId = row.dataset.attendanceId;

                const payload = {
                    employee_id: row.dataset.employeeId,
                    date: row.dataset.date,
                    in_time: inInput.value,
                    out_time: outInput.value,
                };

                const url = attendanceId
                    ? `{{ url('/attendance') }}/${attendanceId}`
                    : `{{ route('attendance.store') }}`;

                if (attendanceId) {
                    payload._method = 'PUT';
                }

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN':
                                document.querySelector('meta[name="csrf-token"]')?.content
                                ?? '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });

                    if (!response.ok) {
                        const error = await response.json().catch(() => ({}));
                        throw new Error(
                            error.message || 'Unable to save attendance.'
                        );
                    }

                    window.location.reload();

                } catch (error) {
                    alert(error.message || 'Could not save this entry. Please try again.');
                }
            }
        });
    </script>

</x-layout>
