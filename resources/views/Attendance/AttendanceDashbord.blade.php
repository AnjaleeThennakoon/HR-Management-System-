<x-layout title="Attendance - HR System">
    <div class="w-full h-[calc(100vh-65px)] flex flex-col p-4 overflow-hidden bg-slate-50/50">

        {{-- Top Header --}}
        <div class="flex items-center justify-between mb-3 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-md shadow-indigo-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Attendance Dashboard</h1>
                    <p class="text-[11px] text-slate-500 font-medium tracking-wide">Manage and track employee daily attendance</p>
                </div>
            </div>

            <a href="/dashboard" class="inline-flex items-center gap-1.5 text-xs text-slate-600 hover:text-indigo-600 font-bold transition bg-white px-3 py-2 rounded-lg shadow-sm ring-1 ring-slate-200 hover:ring-indigo-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-3 flex items-center gap-2 p-3 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-xl ring-1 ring-emerald-100 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Main Layout: Left/Right --}}
        <div class="flex flex-col lg:flex-row gap-4 h-full min-h-0 overflow-hidden">

            {{-- ============== LEFT SIDEBAR ============== --}}
            <aside class="w-full lg:w-[320px] flex-shrink-0 flex flex-col gap-3 h-full overflow-y-auto custom-scrollbar">

                {{-- CSV Upload Section --}}
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/60 overflow-hidden flex-shrink-0">
                    @include('Attendance.CsvUpload')
                </div>

                {{-- Actions & Filter Card --}}
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200/60 p-4 flex flex-col gap-3 flex-shrink-0">
                    <a href="{{ route('attendance.monthly-report') }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg transition-colors border border-indigo-100 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        View Monthly Report
                    </a>

                    <div class="relative w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <input type="text" id="searchInput" onkeyup="filterAttendances()" placeholder="Search attendance..." class="w-full pl-9 pr-3 py-2 text-xs font-semibold border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-slate-50 text-slate-700 placeholder-slate-400">
                    </div>

                    <button type="button" onclick="openAddModal()" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-slate-800 to-slate-900 text-white text-xs font-bold rounded-lg shadow-md hover:from-slate-700 hover:to-slate-800 transition-all whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Attendance
                    </button>
                </div>
            </aside>

            {{-- ============== RIGHT CONTENT: TABLE ============== --}}
            <main class="w-full flex-1 min-w-0 bg-white rounded-xl shadow-sm ring-1 ring-slate-200/60 flex flex-col overflow-hidden">

                {{-- Table Header (Fixed) --}}
                <div class="overflow-x-auto flex-shrink-0 border-b border-slate-100 bg-slate-50/50">
                    <table class="w-full text-xs text-left table-fixed">
                        <thead class="text-[10px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th scope="col" class="py-2.5 px-4 font-extrabold w-[8%]">#</th>
                            <th scope="col" class="py-2.5 px-3 font-extrabold w-[30%]">Employee</th>
                            <th scope="col" class="py-2.5 px-3 font-extrabold w-[18%]">Date</th>
                            <th scope="col" class="py-2.5 px-3 font-extrabold w-[15%]">In Time</th>
                            <th scope="col" class="py-2.5 px-3 font-extrabold w-[15%]">Out Time</th>
                            <th scope="col" class="py-2.5 px-3 font-extrabold text-right w-[14%]">Actions</th>
                        </tr>
                        </thead>
                    </table>
                </div>

                {{-- Table Body (Scrollable) --}}
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <table class="w-full text-xs text-left table-fixed">
                        <tbody class="divide-y divide-slate-50">
                        @forelse($attendances as $attendance)
                            <tr class="attendance-row hover:bg-indigo-50/30 transition-colors group">
                                <td class="py-2 px-4 text-slate-400 font-bold view-cell w-[8%]">{{ $attendance->id }}</td>
                                <td class="py-2 px-3 view-cell w-[30%]">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex h-7 w-7 items-center justify-center rounded-md bg-indigo-100 text-indigo-700 font-bold text-[10px] shadow-sm flex-shrink-0">
                                            {{ strtoupper(substr($attendance->employee->first_name ?? '', 0, 1).substr($attendance->employee->last_name ?? '', 0, 1)) ?: 'NA' }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <span class="attendance-employee text-slate-700 font-bold truncate block">
                                                {{ $attendance->employee ? $attendance->employee->first_name.' '.$attendance->employee->last_name : 'N/A' }}
                                            </span>
                                            <p class="text-[10px] text-slate-400 font-semibold truncate">ID: {{ $attendance->employee->employee_id ?? $attendance->employee_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 px-3 view-cell w-[18%]">
                                    <span class="attendance-date inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                        {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 view-cell w-[15%]">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        {{ $attendance->in_time }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 view-cell w-[15%]">
                                    @if($attendance->out_time)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                            {{ $attendance->out_time }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            Not marked
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-right edit-controls w-[14%]">
                                    <div class="inline-flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button"
                                                onclick="openEditModal(
                                                    {{ $attendance->id }},
                                                    {{ $attendance->employee_id }},
                                                    '{{ $attendance->date }}',
                                                    '{{ $attendance->in_time }}',
                                                    '{{ $attendance->out_time ?? '' }}'
                                                )"
                                                class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                            </svg>
                                        </button>

                                        <div class="scale-90">
                                            <x-Form.deletebutton
                                                :action="route('attendance.destroy', $attendance->id)"
                                                confirmation-message="Are you sure you want to delete this record?"
                                            />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2 text-slate-400">
                                        <div class="h-12 w-12 bg-slate-100 rounded-full flex items-center justify-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-600">No attendance records yet</p>
                                        <p class="text-xs text-slate-400">Click "Add Attendance" or upload a CSV to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <p id="noResults" class="hidden text-center text-sm font-semibold text-slate-400 py-8">
                        No attendance records match your search.
                    </p>
                </div>
            </main>
        </div>
    </div>

    @include('Attendance.attendance-modal', ['employees' => $employees])

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.96) translateY(4px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>

    <script>
        function openAddModal() {
            const form = document.getElementById('attendanceForm');
            form.reset();
            form.action = "{{ route('attendance.store') }}";

            document.getElementById('attendanceMethod').value = '';
            document.getElementById('attendanceEditAction').value = '';

            document.getElementById('attendanceModalTitle').textContent = 'Add New Attendance';
            document.getElementById('attendanceSubmit').textContent = 'Add Attendance';
            document.getElementById('attendanceModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('attendanceModal').classList.add('hidden');
        }

        function openEditModal(id, employeeId, date, inTime, outTime) {
            const form = document.getElementById('attendanceForm');
            form.action = '/attendance/' + id;

            document.getElementById('attendanceMethod').value = 'PUT';
            document.getElementById('attendanceEditAction').value = '/attendance/' + id;

            document.getElementById('attendanceEmployee').value = employeeId;

            document.getElementById('attendanceDate').value = date;
            document.getElementById('attendanceInTime').value = inTime;
            document.getElementById('attendanceOutTime').value = outTime ?? '';
            document.getElementById('attendanceModalTitle').textContent = 'Edit Attendance';
            document.getElementById('attendanceSubmit').textContent = 'Update Attendance';
            document.getElementById('attendanceModal').classList.remove('hidden');
        }

        function filterAttendances() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.attendance-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const employee = row.querySelector('.attendance-employee').textContent.toLowerCase();
                const date = row.querySelector('.attendance-date').textContent.toLowerCase();
                const match = employee.includes(query) || date.includes(query);
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            document.getElementById('noResults').classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }

        function toggleErrorDetails() {
            const detailsList = document.getElementById('errorDetailsList');
            const toggleText = document.getElementById('errorDetailsToggleText');

            if (!detailsList || !toggleText) return;

            if (detailsList.classList.contains('hidden')) {
                detailsList.classList.remove('hidden');
                toggleText.textContent = 'Hide details';
            } else {
                detailsList.classList.add('hidden');
                toggleText.textContent = 'See details';
            }
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeAddModal();
        });

        @if($errors->has('employee_id') || $errors->has('date') || $errors->has('in_time') || $errors->has('out_time'))
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('attendanceModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        });
        @endif
    </script>
</x-layout>
