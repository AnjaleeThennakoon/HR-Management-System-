<x-layout title="Monthly Attendance Report - HR System">
    <div class="w-full h-[calc(100vh-65px)] flex flex-col p-4 overflow-hidden bg-slate-50/50">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-3 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-md shadow-indigo-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Monthly Attendance</h1>
                    <p class="text-[11px] text-slate-500 font-medium tracking-wide">Track daily attendance, late hours & overtime</p>
                </div>
            </div>

            <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-600 hover:text-indigo-600 font-bold transition bg-white px-3 py-2 rounded-lg shadow-sm ring-1 ring-slate-200 hover:ring-indigo-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Attendance
            </a>
        </div>

        {{-- Main Layout --}}
        <div class="flex flex-col lg:flex-row gap-4 h-full min-h-0">

            {{-- Left Sidebar --}}
            <aside class="w-full lg:w-[280px] flex-shrink-0 flex flex-col gap-3 h-full overflow-y-auto custom-scrollbar">

                <form method="GET" action="{{ route('attendance.monthly-report') }}" class="flex-shrink-0">
                    <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200/60 overflow-hidden">
                        <div class="bg-indigo-50/50 px-4 py-2.5 border-b border-indigo-100/50">
                            <h2 class="text-indigo-700 font-bold text-[10px] uppercase tracking-widest flex items-center gap-1.5">
                                Filters
                            </h2>
                        </div>
                        <div class="p-3.5 space-y-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Month & Year</label>
                                <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}" class="w-full text-xs font-semibold border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 py-2 px-3 text-slate-700">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Employee</label>
                                <select name="employee_id" id="employee_select" class="w-full text-xs font-semibold border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 py-2 px-3 text-slate-700" onchange="updateShiftTimes(this)">
                                    <option value="">Select Employee</option>
                                    @foreach ($employees ?? [] as $emp)
                                        <option value="{{ $emp->id }}"
                                                data-first-name="{{ $emp->first_name }}"
                                                data-last-name="{{ $emp->last_name }}"
                                                data-shift-in="{{ $emp->shift_in ? substr($emp->shift_in, 0, 5) : '08:00' }}"
                                                data-shift-out="{{ $emp->shift_out ? substr($emp->shift_out, 0, 5) : '17:00' }}"
                                            @selected($selectedEmployeeId == $emp->id)>
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs font-bold rounded-lg shadow-md shadow-indigo-200 transition-all">
                                Generate Report
                            </button>
                        </div>
                    </div>
                </form>

                @if($employee)
                    <div id="shift_display_card" class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200/60 p-3.5 flex-shrink-0 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-full -z-10"></div>
                        <div class="flex items-center gap-3 relative z-10">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 font-black text-sm text-indigo-700 shadow-inner">
                                <span id="shift_emp_initials">{{ strtoupper(substr($employee->first_name, 0, 1).substr($employee->last_name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h3 id="shift_emp_name" class="font-bold text-sm text-slate-800 line-clamp-1">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                                <p class="text-[10px] font-semibold text-indigo-500 uppercase tracking-wide">Employee Profile</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-3 relative z-10">
                            <div class="bg-slate-50 rounded-lg p-2 text-center border border-slate-100">
                                <p class="text-[9px] uppercase text-slate-400 font-bold tracking-widest mb-0.5">Shift In</p>
                                <p id="shift_in_time" class="text-sm font-extrabold text-slate-700">{{ $employee->shift_in ? substr($employee->shift_in, 0, 5) : '08:00' }}</p>
                            </div>
                            <div class="bg-slate-50 rounded-lg p-2 text-center border border-slate-100">
                                <p class="text-[9px] uppercase text-slate-400 font-bold tracking-widest mb-0.5">Shift Out</p>
                                <p id="shift_out_time" class="text-sm font-extrabold text-slate-700">{{ $employee->shift_out ? substr($employee->shift_out, 0, 5) : '17:00' }}</p>
                            </div>
                        </div>
                    </div>

                    <div id="totals_display_card" class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200/60 p-3.5 flex-shrink-0">
                        <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                            Monthly Totals
                        </h4>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-gradient-to-br from-rose-50 to-red-50 rounded-lg p-2.5 text-center border border-red-100">
                                <p class="text-[9px] uppercase text-red-500 font-bold tracking-widest mb-0.5">Late</p>
                                <p class="text-base font-black text-red-600">{{ number_format($totalLateMinutes ?? 0) }}<span class="text-[10px] font-semibold ml-0.5">m</span></p>
                            </div>
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-2.5 text-center border border-blue-100">
                                <p class="text-[9px] uppercase text-blue-500 font-bold tracking-widest mb-0.5">OT</p>
                                <p class="text-base font-black text-blue-600">{{ number_format($totalOtMinutes ?? 0) }}<span class="text-[10px] font-semibold ml-0.5">m</span></p>
                            </div>
                        </div>
                    </div>
                @endif
            </aside>

            {{-- Right Main Content (Table) --}}
            <main class="w-full flex-1 min-w-0 bg-white rounded-xl shadow-sm ring-1 ring-slate-200/60 flex flex-col overflow-hidden">
                @if ($employee)
                    @php
                        $year = (int) ($selectedYear ?? date('Y'));
                        $month = (int) ($selectedMonthNum ?? date('n'));
                        $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
                        $totalDays = $startOfMonth->endOfMonth()->day;
                    @endphp

                    <div class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-200/60 flex items-center justify-between flex-shrink-0">
                        <h2 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            {{ \Carbon\Carbon::create(null, $month)->format('F') }} {{ $year }}
                        </h2>
                        <div class="hidden sm:flex items-center gap-2 text-[10px] font-bold">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> On Time
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-rose-50 text-rose-700 border border-rose-200/50">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Late
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200/50">
                                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span> OT
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto flex-shrink-0 border-b border-slate-100">
                        <table class="w-full text-xs text-left table-fixed">
                            <thead class="bg-white text-[10px] uppercase tracking-wider text-slate-400">
                            <tr>
                                <th scope="col" class="py-2 px-3 font-extrabold w-[15%]">Date</th>
                                <th scope="col" class="py-2 px-2 font-extrabold w-[15%]">In Time</th>
                                <th scope="col" class="py-2 px-2 font-extrabold w-[15%]">Out Time</th>
                                <th scope="col" class="py-2 px-2 font-extrabold w-[20%]">Late Time</th>
                                <th scope="col" class="py-2 px-2 font-extrabold w-[20%]">Over Time</th>
                                <th scope="col" class="py-2 px-2 font-extrabold text-right w-[15%]">Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <table class="w-full text-xs text-left table-fixed">
                            <tbody class="divide-y divide-slate-50">
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

                                <tr class="attendance-row {{ $rowType === 'holiday' ? 'bg-rose-50/30' : ($rowType === 'leave' ? 'bg-amber-50/30' : ($rowType === 'weekend' ? 'bg-slate-50/50' : 'hover:bg-indigo-50/30')) }} transition-colors group">
                                    <td class="py-1.5 px-3 font-bold text-slate-700 whitespace-nowrap view-cell w-[15%]">
                                        {{ $formattedDate }} <span class="text-[9px] text-slate-400 font-semibold">({{ $dateObj->format('D') }})</span>
                                    </td>

                                    @if ($rowType === 'holiday')
                                        <td colspan="4" class="py-1.5 px-2 view-cell">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                                {{ $holiday->name ?? 'Public Holiday' }}
                                            </span>
                                        </td>
                                    @elseif ($rowType === 'leave')
                                        <td colspan="4" class="py-1.5 px-2 view-cell">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                                {{ $leave->leave_type ?? 'On Leave' }}
                                            </span>
                                        </td>
                                    @elseif ($rowType === 'weekend')
                                        <td colspan="4" class="py-1.5 px-2 text-[10px] font-bold text-slate-400 italic view-cell">
                                            Weekend (Off)
                                        </td>
                                    @else
                                        <td class="py-1.5 px-2 view-cell w-[15%]">
                                            @if ($att && $att->in_time)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $att->in_time }}</span>
                                            @else
                                                <span class="text-slate-300">-</span>
                                            @endif
                                        </td>
                                        <td class="py-1.5 px-2 view-cell w-[15%]">
                                            @if ($att && $att->out_time)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $att->out_time }}</span>
                                            @else
                                                <span class="text-slate-300">-</span>
                                            @endif
                                        </td>
                                        <td class="py-1.5 px-2 view-cell w-[20%]">
                                            @if ($att && $att->late_minutes > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100">{{ $att->late_minutes }} mins</span>
                                            @elseif ($att && $att->in_time)
                                                <span class="text-[10px] font-extrabold text-emerald-500">On Time</span>
                                            @else
                                                <span class="text-slate-300">-</span>
                                            @endif
                                        </td>
                                        <td class="py-1.5 px-2 view-cell w-[20%]">
                                            @if ($att && $att->ot_minutes > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">{{ $att->ot_minutes }} mins</span>
                                            @else
                                                <span class="text-slate-300">-</span>
                                            @endif
                                        </td>
                                    @endif

                                    <td class="py-1.5 px-2 text-right edit-controls w-[15%]">
                                        <div class="view-mode flex justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button" class="edit-btn p-1 rounded text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </button>
                                        </div>
                                        <div class="edit-mode hidden items-center justify-end gap-1">
                                            <input type="time" class="in-time-input w-[60px] text-[10px] border-slate-200 rounded py-0.5 px-1 focus:border-indigo-500 focus:ring-0" value="{{ $att->in_time ?? '' }}">
                                            <input type="time" class="out-time-input w-[60px] text-[10px] border-slate-200 rounded py-0.5 px-1 focus:border-indigo-500 focus:ring-0" value="{{ $att->out_time ?? '' }}">
                                            <button type="button" class="save-btn p-1 rounded bg-emerald-100 text-emerald-600 hover:bg-emerald-200" title="Save">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            </button>
                                            <button type="button" class="cancel-btn p-1 rounded bg-slate-100 text-slate-500 hover:bg-slate-200" title="Cancel">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-6">
                        <div class="h-20 w-20 bg-indigo-50 rounded-full flex items-center justify-center mb-4 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-800">No Report Generated</h3>
                        <p class="text-sm text-slate-500 mt-1 max-w-sm">Select an employee and month from the filter panel on the left to generate their detailed attendance report.</p>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>

    <script>
        function updateShiftTimes(selectElement) {
            const shiftCard = document.getElementById('shift_display_card');
            const totalsCard = document.getElementById('totals_display_card');
            const shiftInEl = document.getElementById('shift_in_time');
            const shiftOutEl = document.getElementById('shift_out_time');
            const empNameEl = document.getElementById('shift_emp_name');
            const empInitialsEl = document.getElementById('shift_emp_initials');

            if (!selectElement.value) {
                if (shiftCard) shiftCard.style.display = 'none';
                if (totalsCard) totalsCard.style.display = 'none';
                return;
            }

            const opt = selectElement.options[selectElement.selectedIndex];
            const firstName = opt.getAttribute('data-first-name') || '';
            const lastName = opt.getAttribute('data-last-name') || '';
            const shiftIn = opt.getAttribute('data-shift-in') || '08:00';
            const shiftOut = opt.getAttribute('data-shift-out') || '17:00';

            if (shiftInEl) shiftInEl.textContent = shiftIn;
            if (shiftOutEl) shiftOutEl.textContent = shiftOut;
            if (empNameEl) empNameEl.textContent = firstName + ' ' + lastName;
            if (empInitialsEl) {
                const initials = ((firstName.charAt(0) || '') + (lastName.charAt(0) || '')).toUpperCase();
                empInitialsEl.textContent = initials || '--';
            }

            if (shiftCard) shiftCard.style.display = 'block';
            if (totalsCard) totalsCard.style.display = 'block';
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

                if (attendanceId) { payload._method = 'PUT'; }

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
                    .then(() => window.location.reload())
                    .catch(() => alert('Could not save this entry. Please try again.'));
            }
        });
    </script>
</x-layout>
