@php use Carbon\Carbon; @endphp
<x-layout title="Monthly Attendance Report - HR System">

    <div class="flex h-[calc(100vh-65px)] flex-col gap-4 overflow-hidden bg-slate-50/50 p-4">

        {{-- Header --}}
        <header class="flex shrink-0 items-center justify-between gap-3">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-800">Monthly Attendance</h1>
                    <p class="text-xs text-slate-500">Track daily attendance, late hours and overtime</p>
                </div>
            </div>

            <a href="{{ route('attendance.index') }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-indigo-300 hover:text-indigo-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span class="hidden sm:inline">Back to Attendance</span>
                <span class="sm:hidden">Back</span>
            </a>
        </header>

        <div class="grid min-h-0 flex-1 grid-cols-1 gap-4 overflow-hidden lg:grid-cols-[260px_minmax(0,1fr)]">

            {{-- Sidebar --}}
            <aside class="flex min-h-0 flex-col gap-4 overflow-y-auto custom-scrollbar">

                {{-- Filters --}}
                <form method="GET" action="{{ route('attendance.monthly-report') }}" class="shrink-0">
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                            <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-600">Report Filters</h2>
                        </div>

                        <div class="space-y-4 p-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="year" class="mb-1.5 block text-xs font-semibold text-slate-500">Year</label>
                                    <select name="year" id="year" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                                        @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}" @selected(($selectedYear ?? date('Y')) == $i)>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label for="month" class="mb-1.5 block text-xs font-semibold text-slate-500">Month</label>
                                    <select name="month" id="month" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach (range(1, 12) as $m)
                                            <option value="{{ $m }}" @selected(($selectedMonth ?? date('n')) == $m)>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="employee_select" class="mb-1.5 block text-xs font-semibold text-slate-500">Employee</label>
                                <select name="employee_id" id="employee_select" autocomplete="off" class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="" class="bg-slate-50 text-slate-500" @selected(!old('employee_id', $employee_id ?? null))>Select Employee</option>
                                    @foreach ($employees ?? [] as $emp)
                                        <option value="{{ $emp->id }}" class="bg-slate-50 text-slate-700" @selected(old('employee_id', $employee_id ?? null) == $emp->id)>
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-indigo-700">
                                Generate Report
                            </button>
                        </div>
                    </div>
                </form>

                @if ($employee)
                    <section class="shrink-0 rounded-xl border border-slate-200 bg-white p-4">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Employee Profile</h2>
                        <div class="mt-3 flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 font-bold text-indigo-700">
                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-slate-800">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                                <p class="text-xs text-slate-500">Employee</p>
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs text-slate-500">Shift In</p>
                                <p class="mt-1 font-bold text-slate-700">{{ $employee->shift_in ? substr($employee->shift_in, 0, 5) : '08:00' }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs text-slate-500">Shift Out</p>
                                <p class="mt-1 font-bold text-slate-700">{{ $employee->shift_out ? substr($employee->shift_out, 0, 5) : '17:00' }}</p>
                            </div>
                        </div>
                        <div class="mt-3 rounded-lg border {{ !empty($allLeaveBalancesUsed) ? 'border-amber-200 bg-amber-50/70' : 'border-slate-100 bg-slate-50' }} p-2.5 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500 font-medium">Leave Balances:</span>
                                @if (!empty($allLeaveBalancesUsed))
                                    <span class="font-bold text-amber-700">0d (Exhausted)</span>
                                @else
                                    <span class="font-bold text-emerald-700">{{ $totalRemainingLeaveDays ?? 0 }}d remaining</span>
                                @endif
                            </div>
                        </div>
                    </section>

                    <section class="shrink-0 rounded-xl border border-slate-200 bg-white p-4">
                        <h2 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">Monthly Totals</h2>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="rounded-lg bg-rose-50 p-2 text-center">
                                <p class="text-xs font-semibold text-rose-600">Late</p>
                                <p class="mt-1 text-lg font-extrabold text-rose-700">{{ number_format($totalLateMinutes ?? 0) }} <span class="text-[10px]">m</span></p>
                            </div>
                            <div class="rounded-lg bg-indigo-50 p-2 text-center">
                                <p class="text-xs font-semibold text-indigo-600">OT</p>
                                <p class="mt-1 text-lg font-extrabold text-indigo-700">{{ number_format($totalOtMinutes ?? 0) }} <span class="text-[10px]">m</span></p>
                            </div>
                            @if (!empty($allLeaveBalancesUsed))
                                <div class="rounded-lg bg-amber-50 border border-amber-200 p-2 text-center" title="All leave balances exhausted - non-pay days">
                                    <p class="text-xs font-semibold text-amber-700">Non-Pay</p>
                                    <p class="mt-1 text-lg font-extrabold text-amber-800">{{ $nonPayDays ?? $nonWorkingDays ?? 0 }} <span class="text-[10px]">d</span></p>
                                </div>
                            @else
                                <div class="rounded-lg bg-slate-100 p-2 text-center" title="Empty non-working days">
                                    <p class="text-xs font-semibold text-slate-600">Empty Days</p>
                                    <p class="mt-1 text-lg font-extrabold text-slate-700">{{ $nonWorkingDays ?? $nonworkingDays ?? 0 }} <span class="text-[10px]">d</span></p>
                                </div>
                            @endif
                        </div>
                    </section>
                @endif
            </aside>

            {{-- Table --}}
            <main class="flex min-h-0 min-w-0 flex-col overflow-hidden rounded-xl border border-slate-200 bg-white">
                @if ($employee)
                    @php
                        $year  = (int) ($selectedYear ?? date('Y'));
                        $month = (int) ($selectedMonth ?? date('n'));
                        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                        $totalDays    = $startOfMonth->daysInMonth;
                    @endphp

                    <div class="flex shrink-0 flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-white px-4 py-3">
                        <div>
                            <h2 class="font-bold text-slate-800">{{ $startOfMonth->format('F Y') }}</h2>
                            <p class="mt-1 text-xs text-slate-500">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="rounded-md bg-emerald-50 px-2 py-1 font-semibold text-emerald-700">On Time</span>
                            <span class="rounded-md bg-rose-50 px-2 py-1 font-semibold text-rose-700">Late</span>
                            <span class="rounded-md bg-indigo-50 px-2 py-1 font-semibold text-indigo-700">Overtime</span>
                            <span class="rounded-md bg-blue-100 px-2 py-1 font-semibold text-blue-800 border border-blue-200">Weekend</span>
                            @if (!empty($allLeaveBalancesUsed))
                                <span class="rounded-md bg-amber-100 px-2 py-1 font-semibold text-amber-800 border border-amber-300">Non-Pay Day</span>
                            @endif
                        </div>
                    </div>

                    <div class="min-h-0 flex-1 overflow-auto custom-scrollbar">
                        <table class="w-full min-w-[760px] table-fixed border-collapse text-left text-xs">
                            <thead class="sticky top-0 z-20 bg-slate-100 text-[11px] uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="w-[16%] border-b border-slate-200 px-3 py-3 font-bold">Date</th>
                                <th class="w-[13%] border-b border-slate-200 px-2 py-3 font-bold">In Time</th>
                                <th class="w-[13%] border-b border-slate-200 px-2 py-3 font-bold">Out Time</th>
                                <th class="w-[15%] border-b border-slate-200 px-2 py-3 font-bold">Late Time</th>
                                <th class="w-[15%] border-b border-slate-200 px-2 py-3 font-bold">Over Time</th>
                                <th class="w-[28%] border-b border-slate-200 px-2 py-3 text-right font-bold">Actions</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                            @for ($day = 1; $day <= $totalDays; $day++)
                                @php
                                    $dateObj   = Carbon::createFromDate($year, $month, $day);
                                    $dateStr   = $dateObj->toDateString();
                                    $isWeekend = $dateObj->isWeekend();
                                    $holiday = ($holidays ?? collect())->first(fn ($h) => Carbon::parse($h->date)->toDateString() === $dateStr);
                                    $leave = ($leaves ?? collect())->first(function ($l) use ($dateStr) {
                                        $leaveStart = Carbon::parse($l->start_date)->toDateString();
                                        $leaveEnd   = Carbon::parse($l->end_date ?? $l->start_date)->toDateString();
                                        return $dateStr >= $leaveStart && $dateStr <= $leaveEnd;
                                    });
                                    $att = $attendances[$dateStr] ?? null;
                                    $hasAttendance = $att && ($att->in_time || $att->out_time);

                                    if ($holiday) {
                                        $rowType = 'holiday';
                                    } elseif ($leave) {
                                        $rowType = 'leave';
                                    } elseif ($isWeekend) {
                                        $rowType = 'weekend';
                                    } elseif (! $hasAttendance) {
                                        $rowType = (!empty($allLeaveBalancesUsed)) ? 'non_pay' : 'empty';
                                    } else {
                                        $rowType = 'normal';
                                    }

                                    $inTimeDisplay = ($att && $att->in_time) ? \Carbon\Carbon::parse($att->in_time)->format('H:i') : null;
                                    $outTimeDisplay = ($att && $att->out_time) ? \Carbon\Carbon::parse($att->out_time)->format('H:i') : null;
                                @endphp

                                <tr class="attendance-row {{ $rowType === 'holiday' ? 'bg-rose-50/50' : ($rowType === 'leave' ? 'bg-amber-50/50' : ($rowType === 'weekend' ? 'bg-blue-100/70 hover:bg-blue-100/90' : ($rowType === 'non_pay' ? 'bg-amber-50/70 hover:bg-amber-100/60' : ($rowType === 'empty' ? 'bg-slate-50/60' : 'hover:bg-indigo-50/40')))) }}"
                                    data-attendance-id="{{ $att->id ?? '' }}"
                                    data-leave-id="{{ $leave->id ?? '' }}"
                                    data-employee-id="{{ $employee->id }}"
                                    data-employee-name="{{ $employee->first_name }} {{ $employee->last_name }}"
                                    data-date="{{ $dateStr }}"
                                    data-date-display="{{ $dateObj->format('d M Y') }}"
                                    data-row-type="{{ $rowType }}">
                                    <td class="whitespace-nowrap px-3 py-3 font-semibold {{ $rowType === 'weekend' ? 'text-blue-900' : 'text-slate-700' }}">
                                        {{ $dateObj->format('d M') }} <span class="{{ $rowType === 'weekend' ? 'inline-flex items-center rounded-md bg-blue-200/80 px-1.5 py-0.5 text-[10px] font-extrabold text-blue-800' : 'text-slate-400' }}">({{ $dateObj->format('D') }})</span>
                                    </td>

                                    @if ($rowType === 'holiday')
                                        <td colspan="4" class="px-2 py-3"><span class="rounded-md bg-rose-100 px-2 py-1 font-semibold text-rose-700">{{ $holiday->name ?? 'Public Holiday' }}</span></td>
                                    @elseif ($rowType === 'leave')
                                        <td colspan="4" class="px-2 py-3">
                                            <span class="rounded-md bg-amber-100 px-2 py-1 font-semibold text-amber-700">{{ $leave->leave_type ?? 'On Leave' }}</span>
                                            @if ($leave->reason)
                                                <span class="ml-1 text-[11px] text-slate-500">({{ \Illuminate\Support\Str::limit($leave->reason, 24) }})</span>
                                            @endif
                                        </td>
                                    @elseif ($rowType === 'weekend' && ! $hasAttendance)
                                        <td colspan="4" class="px-2 py-3"><span class="inline-flex items-center rounded-md bg-blue-200/80 px-2.5 py-1 text-xs font-bold text-blue-800 border border-blue-300/60">Weekend</span></td>
                                    @elseif ($rowType === 'weekend' && $hasAttendance)
                                        <td class="px-2 py-3">@if ($inTimeDisplay)<span class="rounded-md bg-emerald-50 px-2 py-1 font-semibold text-emerald-700">{{ $inTimeDisplay }}</span>@else<span class="text-slate-300">-</span>@endif</td>
                                        <td class="px-2 py-3">@if ($outTimeDisplay)<span class="rounded-md bg-indigo-50 px-2 py-1 font-semibold text-indigo-700">{{ $outTimeDisplay }}</span>@else<span class="text-slate-300">-</span>@endif</td>
                                        <td colspan="2" class="px-2 py-3"><span class="rounded-md bg-rose-100 px-2 py-1 font-semibold text-rose-700">Cannot add time for the weekend days</span></td>
                                    @elseif ($rowType === 'non_pay')
                                        <td colspan="4" class="px-2 py-3">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1.5 rounded-md bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-900 border border-amber-300">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-600"></span>
                                                    Non-Pay Day
                                                </span>
                                                <span class="text-[11px] font-medium text-amber-700">(Leave balance exhausted)</span>
                                            </div>
                                        </td>
                                    @elseif ($rowType === 'empty')
                                        <td colspan="4" class="px-2 py-3"><span class="rounded-md bg-slate-100 px-2 py-1 font-medium text-slate-500">Empty (Off)</span></td>
                                    @else
                                        <td class="px-2 py-3">@if ($inTimeDisplay)<span class="rounded-md bg-emerald-50 px-2 py-1 font-semibold text-emerald-700">{{ $inTimeDisplay }}</span>@else<span class="text-slate-300">-</span>@endif</td>
                                        <td class="px-2 py-3">@if ($outTimeDisplay)<span class="rounded-md bg-indigo-50 px-2 py-1 font-semibold text-indigo-700">{{ $outTimeDisplay }}</span>@else<span class="text-slate-300">-</span>@endif</td>
                                        <td class="px-2 py-3">@if ($att && $att->late_minutes > 0)<span class="rounded-md bg-rose-50 px-2 py-1 font-semibold text-rose-700">{{ $att->late_minutes }} mins</span>@elseif ($att && $att->in_time)<span class="font-semibold text-emerald-600">On Time</span>@else<span class="text-slate-300">-</span>@endif</td>
                                        <td class="px-2 py-3">@if ($att && $att->ot_minutes > 0)<span class="rounded-md bg-blue-50 px-2 py-1 font-semibold text-blue-700">{{ $att->ot_minutes }} mins</span>@else<span class="text-slate-300">-</span>@endif</td>
                                    @endif

                                    <td class="px-2 py-3 text-right">
                                        @if ($rowType === 'leave')
                                            <div class="flex items-center justify-end gap-1">
                                                <button type="button" class="delete-row-btn rounded-md p-1.5 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition" title="Delete leave record">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @elseif ($rowType === 'normal' || $rowType === 'empty' || $rowType === 'non_pay' || $rowType === 'weekend')
                                            <div class="flex items-center justify-end gap-1">
                                                <div class="view-mode flex items-center justify-end gap-1">
                                                    <button type="button" class="edit-btn rounded-md p-1.5 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition" title="Edit attendance">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </button>

                                                    <button type="button" class="mark-leave-btn rounded-md p-1.5 text-slate-400 hover:bg-amber-50 hover:text-amber-600 transition" title="Mark as a leave">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </button>

                                                    @if ($att && $att->id)
                                                        <button type="button" class="delete-row-btn rounded-md p-1.5 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition" title="Delete row">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <button type="button" disabled class="cursor-not-allowed rounded-md p-1.5 text-slate-300 opacity-40" title="No record to delete">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>

                                                <div class="edit-mode hidden items-center justify-end gap-1">
                                                    <input type="time" class="in-time-input w-[75px] rounded border border-slate-300 px-1 py-1 text-xs" value="{{ $inTimeDisplay ?? '' }}">
                                                    <input type="time" class="out-time-input w-[75px] rounded border border-slate-300 px-1 py-1 text-xs" value="{{ $outTimeDisplay ?? '' }}">
                                                    <button type="button" class="save-btn rounded bg-emerald-100 p-1.5 text-emerald-700 hover:bg-emerald-200" title="Save">✓</button>
                                                    <button type="button" class="cancel-btn rounded bg-slate-100 p-1.5 text-slate-600 hover:bg-slate-200" title="Cancel">✕</button>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-300 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex min-h-0 flex-1 flex-col items-center justify-center p-8 text-center">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">No Report Generated</h2>
                        <p class="mt-2 max-w-sm text-sm text-slate-500">Select an employee and month, then click Generate Report.</p>
                    </div>
                @endif
            </main>
        </div>
    </div>

    {{-- Mark as Leave Modal --}}
    <div id="markLeaveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 w-full max-w-md flex flex-col animate-[fadeIn_0.2s_ease-out] my-auto">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Mark as Leave</h3>
                    <p id="markLeaveSubtitle" class="text-xs text-slate-500 mt-0.5">Record employee leave</p>
                </div>
                <button type="button" onclick="closeMarkLeaveModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="markLeaveForm" class="p-5 space-y-4">
                <input type="hidden" id="modalLeaveEmployeeId" name="employee_id" value="">
                <input type="hidden" id="modalLeaveDate" name="start_date" value="">
                <input type="hidden" id="modalLeaveEndDate" name="end_date" value="">
                <input type="hidden" id="modalExistingAttendanceId" value="">

                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3 text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Employee:</span>
                        <span id="modalEmployeeNameText" class="font-bold text-slate-700"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Date:</span>
                        <span id="modalDateText" class="font-bold text-slate-700"></span>
                    </div>
                    <div id="attendanceWarningNotice" class="hidden mt-2 pt-2 border-t border-slate-200 text-amber-700">
                        ⚠️ Existing attendance on this date will be replaced by the leave record.
                    </div>
                </div>

                <div id="noLeaveBalanceNotice" class="{{ !empty($allLeaveBalancesUsed) ? '' : 'hidden' }} rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800 space-y-1">
                    <div class="flex items-center gap-1.5 font-bold">
                        <svg class="h-4 w-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Leave Balance Exhausted</span>
                    </div>
                    <p class="text-[11px] text-rose-700 leading-relaxed">
                        All leave balances have been used. A leave cannot be granted for this day; this day is considered a <strong>Non-Pay Day</strong>.
                    </p>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="modalLeaveType" class="block text-xs font-semibold text-slate-700">
                            Leave Type <span class="text-red-500">*</span>
                        </label>
                        <span id="modalBalanceDisplay" class="text-[11px] font-medium text-indigo-600"></span>
                    </div>
                    <select id="modalLeaveType" name="leave_type" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="casual" selected>Casual ({{ $leaveCounts['casual'] ?? 0 }} days)</option>
                        <option value="Annual">Annual ({{ $leaveCounts['Annual'] ?? 0 }} days)</option>
                        <option value="Medical">Medical ({{ $leaveCounts['Medical'] ?? 0 }} days)</option>
                    </select>
                </div>

                <div>
                    <label for="modalLeaveStatus" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div id="modalLeaveStatus" name="status" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">
                        <div value="approved" selected>Approved</div>
                    </div>
                </div>

                <div>
                    <label for="modalLeaveReason" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea id="modalLeaveReason" name="reason" rows="2" required placeholder="Reason for leave" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-700 focus:border-indigo-500 focus:ring-indigo-500">Marked as leave</textarea>
                </div>

                <div id="markLeaveError" class="hidden text-xs text-red-600 bg-red-50 p-2.5 rounded-lg border border-red-100"></div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeMarkLeaveModal()" class="rounded-lg border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="submit" id="submitMarkLeaveBtn" class="rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition">
                        Save Leave
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function closeMarkLeaveModal() {
            document.getElementById('markLeaveModal')?.classList.add('hidden');
        }

        async function fetchLeaveBalance(employeeId, leaveType) {
            const display = document.getElementById('modalBalanceDisplay');
            if (!display || !employeeId) return;
            display.textContent = 'Checking balance...';

            try {
                const res = await fetch(`{{ route('leaves.balance') }}?employee_id=${employeeId}&leave_type=${leaveType}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const data = await res.json();
                    if (data.balance) {
                        display.textContent = `Remaining: ${data.balance.remaining_days} / ${data.balance.max_days}d`;
                        return;
                    }
                }
            } catch (e) {}
            display.textContent = '';
        }

        document.getElementById('modalLeaveType')?.addEventListener('change', function () {
            const employeeId = document.getElementById('modalLeaveEmployeeId')?.value;
            if (employeeId) {
                fetchLeaveBalance(employeeId, this.value);
            }
        });

        document.getElementById('markLeaveForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const errorDiv = document.getElementById('markLeaveError');
            const submitBtn = document.getElementById('submitMarkLeaveBtn');
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}';
            const employeeId = document.getElementById('modalLeaveEmployeeId').value;
            const leaveType = document.getElementById('modalLeaveType').value;
            const startDate = document.getElementById('modalLeaveDate').value;
            const endDate = document.getElementById('modalLeaveEndDate').value || startDate;
            const reason = document.getElementById('modalLeaveReason').value;
            const status = document.getElementById('modalLeaveStatus').value;
            const existingAttendanceId = document.getElementById('modalExistingAttendanceId').value;

            try {
                if (existingAttendanceId) {
                    const delRes = await fetch(`{{ url('/attendance') }}/${existingAttendanceId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ _method: 'DELETE' }),
                    });
                    if (!delRes.ok) {
                        const delErr = await delRes.json().catch(() => ({}));
                        throw new Error(delErr.message || 'Unable to remove existing attendance record.');
                    }
                }

                const response = await fetch(`{{ route('leaves.store') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        employee_id: employeeId,
                        leave_type: leaveType,
                        start_date: startDate,
                        end_date: endDate,
                        reason: reason,
                        status: status,
                    }),
                });

                if (!response.ok) {
                    const data = await response.json().catch(() => ({}));
                    let msg = data.message || 'Unable to save leave.';
                    if (data.errors) {
                        msg = Object.values(data.errors).flat().join(' ');
                    }
                    throw new Error(msg);
                }

                window.location.reload();
            } catch (err) {
                errorDiv.textContent = err.message || 'Something went wrong. Please try again.';
                errorDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save Leave';
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeMarkLeaveModal();
            }
        });

        document.addEventListener('click', async function (event) {
            const row = event.target.closest('.attendance-row');
            if (!row) return;

            const viewMode = row.querySelector('.view-mode');
            const editMode = row.querySelector('.edit-mode');

            // Handle edit button
            if (event.target.closest('.edit-btn') && viewMode && editMode) {
                viewMode.classList.add('hidden');
                viewMode.classList.remove('flex');
                editMode.classList.remove('hidden');
                editMode.classList.add('flex');
            }

            // Handle cancel button
            if (event.target.closest('.cancel-btn') && viewMode && editMode) {
                editMode.classList.add('hidden');
                editMode.classList.remove('flex');
                viewMode.classList.remove('hidden');
                viewMode.classList.add('flex');
            }

            // Handle mark as leave button
            if (event.target.closest('.mark-leave-btn')) {
                const employeeId = row.dataset.employeeId;
                const employeeName = row.dataset.employeeName;
                const date = row.dataset.date;
                const dateDisplay = row.dataset.dateDisplay || date;
                const attendanceId = row.dataset.attendanceId;

                document.getElementById('modalLeaveEmployeeId').value = employeeId;
                document.getElementById('modalLeaveDate').value = date;
                document.getElementById('modalLeaveEndDate').value = date;
                document.getElementById('modalExistingAttendanceId').value = attendanceId || '';
                document.getElementById('modalEmployeeNameText').textContent = employeeName;
                document.getElementById('modalDateText').textContent = dateDisplay;
                document.getElementById('markLeaveSubtitle').textContent = `Record leave on ${dateDisplay}`;
                document.getElementById('markLeaveError').classList.add('hidden');
                document.getElementById('markLeaveError').textContent = '';

                const warning = document.getElementById('attendanceWarningNotice');
                if (attendanceId) {
                    warning.classList.remove('hidden');
                } else {
                    warning.classList.add('hidden');
                }

                fetchLeaveBalance(employeeId, document.getElementById('modalLeaveType').value);

                document.getElementById('markLeaveModal').classList.remove('hidden');
            }

            // Handle delete row button
            if (event.target.closest('.delete-row-btn')) {
                const attendanceId = row.dataset.attendanceId;
                const leaveId = row.dataset.leaveId;
                const dateDisplay = row.dataset.dateDisplay || row.dataset.date;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}';

                if (leaveId) {
                    if (!confirm(`Are you sure you want to delete the leave record for ${dateDisplay}?`)) {
                        return;
                    }

                    try {
                        const response = await fetch(`{{ url('/leaves') }}/${leaveId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ _method: 'DELETE' }),
                        });

                        if (!response.ok) {
                            const error = await response.json().catch(() => ({}));
                            throw new Error(error.message || 'Unable to delete leave.');
                        }
                        window.location.reload();
                    } catch (err) {
                        alert(err.message || 'Could not delete leave record.');
                    }
                    return;
                }

                if (attendanceId) {
                    if (!confirm(`Are you sure you want to delete the attendance record for ${dateDisplay}?`)) {
                        return;
                    }

                    try {
                        const response = await fetch(`{{ url('/attendance') }}/${attendanceId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ _method: 'DELETE' }),
                        });

                        if (!response.ok) {
                            const error = await response.json().catch(() => ({}));
                            throw new Error(error.message || 'Unable to delete attendance.');
                        }
                        window.location.reload();
                    } catch (err) {
                        alert(err.message || 'Could not delete attendance record.');
                    }
                    return;
                }

                alert('No attendance or leave record exists to delete for this day.');
            }

            // Handle save attendance button
            if (event.target.closest('.save-btn')) {
                const inInput = row.querySelector('.in-time-input');
                const outInput = row.querySelector('.out-time-input');
                const attendanceId = row.dataset.attendanceId;

                const payload = {
                    employee_id: row.dataset.employeeId,
                    date: row.dataset.date,
                    in_time: inInput.value || null,
                    out_time: outInput.value || null,
                };

                const url = attendanceId ? `{{ url('/attendance') }}/${attendanceId}` : `{{ route('attendance.store') }}`;
                if (attendanceId) payload._method = 'PUT';

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });

                    if (!response.ok) {
                        const error = await response.json().catch(() => ({}));
                        throw new Error(error.message || 'Unable to save attendance.');
                    }
                    window.location.reload();
                } catch (error) {
                    alert(error.message || 'Could not save this entry. Please try again.');
                }
            }
        });
    </script>
</x-layout>
