<x-layout title="Monthly Attendance Report - HR System">
    <div class="w-full max-w-[1500px] mx-auto flex flex-col">

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-4 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Monthly Attendance Dashboard</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Track employee attendance, holidays, and leaves</p>
                </div>
            </div>

            <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 font-medium transition bg-white px-3.5 py-1.5 rounded-lg shadow-sm ring-1 ring-gray-100 hover:ring-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Attendance
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-4">

            {{-- ============== LEFT SIDEBAR ============== --}}
            <aside class="space-y-4">

                <form method="GET" action="{{ route('attendance.monthly-report') }}" id="attendanceFilterForm">

                    {{-- Filters Card --}}
                    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">
                        <div class="bg-indigo-50 px-4 py-3 border-b border-indigo-100">
                            <h2 class="text-indigo-700 font-semibold text-xs flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                                </svg>
                                Report Filters
                            </h2>
                        </div>

                        <div class="p-4 space-y-3">
                            {{-- Month & Year --}}
                            <div>
                                <label for="filter_month" class="block text-xs font-semibold text-gray-500 mb-1">
                                    Month & Year
                                </label>

                                <input
                                    type="month"
                                    name="month"
                                    id="filter_month"
                                    value="{{ request('month', now()->format('Y-m')) }}"
                                    class="w-full text-xs border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 py-1.5 px-2"
                                >
                            </div>
                            {{-- Employee --}}
                            <div>
                                <label for="employee_select" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Employee</label>
                                <select name="employee_id" id="employee_select" class="w-full text-sm border-gray-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 py-2 px-3" onchange="updateShiftTimes(this)">
                                    <option value="">Choose Employee</option>
                                    @foreach ($employees ?? [] as $emp)
                                        <option value="{{ $emp->id }}"
                                                data-first-name="{{ $emp->first_name }}"
                                                data-last-name="{{ $emp->last_name }}"
                                                data-shift-in="{{ $emp->shift_in ? substr($emp->shift_in, 0, 5) : '08:00' }}"
                                                data-shift-out="{{ $emp->shift_out ? substr($emp->shift_out, 0, 5) : '17:00' }}"
                                            @selected($selectedEmployeeId == $emp->id)>
                                            {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                View Report
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Shift Info Card --}}
                <div id="shift_display_card" class="rounded-xl bg-indigo-50 ring-1 ring-indigo-100 p-4 {{ $employee ? '' : 'hidden' }}">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white ring-1 ring-indigo-200 font-bold text-xs text-indigo-700">
                            <span id="shift_emp_initials">
                                {{ $employee ? strtoupper(substr($employee->first_name, 0, 1).substr($employee->last_name, 0, 1)) : '--' }}
                            </span>
                        </div>
                        <div>
                            <h3 id="shift_emp_name" class="font-bold text-sm text-gray-800">
                                {{ $employee ? $employee->first_name . ' ' . $employee->last_name : 'Selected Employee' }}
                            </h3>
                            <p class="text-xs text-indigo-500">
                                Shift <span id="shift_in_display">{{ $employee && $employee->shift_in ? substr($employee->shift_in, 0, 5) : '08:00' }}</span>
                                &ndash;
                                <span id="shift_out_display">{{ $employee && $employee->shift_out ? substr($employee->shift_out, 0, 5) : '17:00' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-indigo-100">
                        <div class="bg-white rounded-lg px-3 py-2 ring-1 ring-indigo-100">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">Shift In</p>
                            <p id="shift_in_time" class="text-sm font-bold text-gray-800 mt-0.5">{{ $employee && $employee->shift_in ? substr($employee->shift_in, 0, 5) : '08:00' }}</p>
                        </div>
                        <div class="bg-white rounded-lg px-3 py-2 ring-1 ring-indigo-100">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">Shift Out</p>
                            <p id="shift_out_time" class="text-sm font-bold text-gray-800 mt-0.5">{{ $employee && $employee->shift_out ? substr($employee->shift_out, 0, 5) : '17:00' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Monthly Summary Totals Card --}}
            </aside>

            {{-- ============== RIGHT CONTENT: TABLE ============== --}}
            <main class="flex flex-col">
                @if ($employee)
                    @php
                        $year = (int) ($selectedYear ?? date('Y'));
                        $month = (int) ($selectedMonthNum ?? date('n'));
                        $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
                        $totalDays = $startOfMonth->endOfMonth()->day;
                    @endphp

                    <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">

                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                            <div>
                                <h2 class="font-bold text-gray-900 text-sm">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                    <span class="text-gray-400 font-normal">&middot; {{ \Carbon\Carbon::create(null, $month)->format('F') }} {{ $year }}</span>
                                </h2>
                            </div>
                            <div class="hidden sm:flex items-center gap-2 text-[11px]">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 font-medium">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> On Time
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-red-50 text-red-600 font-medium">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span> Late
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-medium">
                                    <span class="h-1.5 w-1.5 rounded-full bg-indigo-400"></span> OT
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-[11px] leading-4 text-left">
                                <thead class="bg-gray-50 text-[9px] uppercase tracking-wide text-gray-400 border-b border-gray-100">
                                <tr>
                                    <th scope="col" class="py-1 px-3 font-semibold">Date</th>
                                    <th scope="col" class="py-1 px-2 font-semibold">In Time</th>
                                    <th scope="col" class="py-1 px-2 font-semibold">Out Time</th>
                                    <th scope="col" class="py-1 px-2 font-semibold">Late Time</th>
                                    <th scope="col" class="py-1 px-2 font-semibold">Over Time</th>
                                    <th scope="col" class="py-1 px-2 font-semibold text-right">Edit</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 bg-white">
                                @for ($day = 1; $day <= $totalDays; $day++)
                                    @php
                                        $dateObj = \Carbon\Carbon::createFromDate($year, $month, $day);
                                        $dateStr = $dateObj->toDateString();
                                        $formattedDate = $dateObj->format('d M');
                                        $isWeekend = $dateObj->isWeekend();

                                        $holiday = ($holidays ?? collect())->first(function ($h) use ($dateStr) {
                                            return \Carbon\Carbon::parse($h->date)->toDateString() === $dateStr;
                                        });

                                        $leave = ($leaves ?? collect())->first(function ($l) use ($dateStr) {
                                            $lStart = \Carbon\Carbon::parse($l->start_date)->toDateString();
                                            $lEnd = \Carbon\Carbon::parse($l->end_date ?? $l->start_date)->toDateString();
                                            return $dateStr >= $lStart && $dateStr <= $lEnd;
                                        });

                                        $att = $attendances[$dateStr] ?? null;
                                        $rowType = $holiday ? 'holiday' : ($leave ? 'leave' : (($isWeekend && !$att) ? 'weekend' : 'normal'));
                                    @endphp

                                    <tr
                                        class="attendance-row {{ $rowType === 'holiday' ? 'bg-rose-50/40' : ($rowType === 'leave' ? 'bg-amber-50/40' : ($rowType === 'weekend' ? 'bg-gray-50/40' : 'hover:bg-indigo-50/20')) }} transition-colors"
                                        data-date="{{ $dateStr }}"
                                        data-employee-id="{{ $employee->id }}"
                                        data-attendance-id="{{ $att->id ?? '' }}"
                                        data-in-time="{{ $att->in_time ?? '' }}"
                                        data-out-time="{{ $att->out_time ?? '' }}"
                                    >
                                        <td class="py-1 px-3 font-medium text-gray-700 whitespace-nowrap view-cell">
                                            {{ $formattedDate }} <span class="text-[9px] text-gray-400">({{ $dateObj->format('D') }})</span>
                                        </td>

                                        @if ($rowType === 'holiday')
                                            <td colspan="4" class="py-1 px-2 view-cell">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="inline-flex items-center px-1.5 py-0 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-600">Holiday</span>
                                                    <span class="text-[10px] text-rose-700">{{ $holiday->name ?? $holiday->title ?? 'Public Holiday' }}</span>
                                                </div>
                                            </td>
                                        @elseif ($rowType === 'leave')
                                            <td colspan="4" class="py-1 px-2 view-cell">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="inline-flex items-center px-1.5 py-0 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700">On Leave</span>
                                                    <span class="text-[10px] text-amber-700">
                                                            {{ $leave->leave_type ?? 'Approved Leave' }}
                                                        @if(!empty($leave->reason))
                                                            <span class="text-amber-600/80">({{ \Illuminate\Support\Str::limit($leave->reason, 30) }})</span>
                                                        @endif
                                                        </span>
                                                </div>
                                            </td>
                                        @elseif ($rowType === 'weekend')
                                            <td colspan="4" class="py-1 px-2 text-[10px] italic text-gray-400 view-cell">
                                                Weekend (Off Day)
                                            </td>
                                        @else
                                            <td class="py-1 px-2 view-cell">
                                                @if ($att && $att->in_time)
                                                    <span class="inline-flex items-center px-1.5 py-0 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-600">{{ $att->in_time }}</span>
                                                @else
                                                    <span class="text-[10px] text-gray-300">-</span>
                                                @endif
                                            </td>
                                            <td class="py-1 px-2 view-cell">
                                                @if ($att && $att->out_time)
                                                    <span class="inline-flex items-center px-1.5 py-0 rounded-full text-[10px] font-medium bg-indigo-50 text-indigo-600">{{ $att->out_time }}</span>
                                                @else
                                                    <span class="text-[10px] text-gray-300">-</span>
                                                @endif
                                            </td>
                                            <td class="py-1 px-2 view-cell">
                                                @if ($att && $att->late_minutes > 0)
                                                    <span class="inline-flex items-center px-1.5 py-0 rounded-full text-[10px] font-semibold bg-red-50 text-red-500">{{ $att->late_minutes }} mins</span>
                                                @elseif ($att && $att->in_time)
                                                    <span class="text-[10px] font-medium text-emerald-500">On Time</span>
                                                @else
                                                    <span class="text-[10px] text-gray-300">-</span>
                                                @endif
                                            </td>
                                            <td class="py-1 px-2 view-cell">
                                                @if ($att && $att->ot_minutes > 0)
                                                    <span class="inline-flex items-center px-1.5 py-0 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-600">{{ $att->ot_minutes }} mins</span>
                                                @else
                                                    <span class="text-[10px] text-gray-300">-</span>
                                                @endif
                                            </td>
                                        @endif

                                        {{-- Edit cell: in/out time inputs, hidden until edit mode --}}
                                        <td class="py-1 px-2 text-right edit-controls">
                                            <div class="view-mode flex justify-end">
                                                <button type="button" class="edit-btn text-gray-400 hover:text-indigo-600 transition" title="Edit this day">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="edit-mode hidden items-center justify-end gap-1.5">
                                                <input type="time" class="in-time-input w-[78px] text-[10px] border-gray-200 rounded-md py-0.5 px-1 focus:border-indigo-500 focus:ring-indigo-500" value="{{ $att->in_time ?? '' }}" placeholder="In">
                                                <input type="time" class="out-time-input w-[78px] text-[10px] border-gray-200 rounded-md py-0.5 px-1 focus:border-indigo-500 focus:ring-indigo-500" value="{{ $att->out_time ?? '' }}" placeholder="Out">
                                                <button type="button" class="save-btn text-emerald-600 hover:text-emerald-700" title="Save">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                </button>
                                                <button type="button" class="cancel-btn text-gray-400 hover:text-gray-600" title="Cancel">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center bg-white rounded-xl border border-dashed border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-700">Select an Employee</h3>
                        <p class="text-xs text-gray-400 mt-1 max-w-sm">Please select an employee and month from the filter sidebar to view the detailed monthly attendance report.</p>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <script>
        function updateShiftTimes(selectElement) {
            const shiftCard = document.getElementById('shift_display_card');
            const shiftInEl = document.getElementById('shift_in_time');
            const shiftOutEl = document.getElementById('shift_out_time');
            const shiftInDisplay = document.getElementById('shift_in_display');
            const shiftOutDisplay = document.getElementById('shift_out_display');
            const empNameEl = document.getElementById('shift_emp_name');
            const empInitialsEl = document.getElementById('shift_emp_initials');

            if (!selectElement.value) {
                if (shiftCard) shiftCard.classList.add('hidden');
                return;
            }

            const opt = selectElement.options[selectElement.selectedIndex];
            const firstName = opt.getAttribute('data-first-name') || '';
            const lastName = opt.getAttribute('data-last-name') || '';
            const shiftIn = opt.getAttribute('data-shift-in') || '08:00';
            const shiftOut = opt.getAttribute('data-shift-out') || '17:00';

            if (shiftInEl) shiftInEl.textContent = shiftIn;
            if (shiftOutEl) shiftOutEl.textContent = shiftOut;
            if (shiftInDisplay) shiftInDisplay.textContent = shiftIn;
            if (shiftOutDisplay) shiftOutDisplay.textContent = shiftOut;
            if (empNameEl) empNameEl.textContent = firstName + ' ' + lastName;
            if (empInitialsEl) {
                const initials = ((firstName.charAt(0) || '') + (lastName.charAt(0) || '')).toUpperCase();
                empInitialsEl.textContent = initials || '--';
            }

            if (shiftCard) shiftCard.classList.remove('hidden');
        }

          document.addEventListener('click', function (e) {
            const row = e.target.closest('.attendance-row');
            if (!row) return;
            const editControls = row.querySelector('.edit-controls');
            const viewMode = editControls.querySelector('.view-mode');
            const editMode = editControls.querySelector('.edit-mode');

            if (e.target.closest('.edit-btn')) {
                viewMode.classList.add('hidden');
                editMode.classList.remove('hidden');
                editMode.classList.add('flex');
            }

            if (e.target.closest('.cancel-btn')) {
                editMode.classList.add('hidden');
                editMode.classList.remove('flex');
                viewMode.classList.remove('hidden');
            }

            if (e.target.closest('.save-btn')) {
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

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                })
                    .then((res) => {
                        if (!res.ok) return res.json().then((err) => { throw err; });
                        return res.json().catch(() => ({}));
                    })
                    .then(() => {
                        window.location.reload();
                    })
                    .catch(() => {
                        alert('Could not save this entry. Please try again.');
                    });
            }
        });
    </script>
</x-layout>
