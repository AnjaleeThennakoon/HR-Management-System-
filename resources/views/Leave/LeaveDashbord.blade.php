<x-layout title="Leaves - HR System">
    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-black/5 p-8 max-w-6xl w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6 pb-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Leaves</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Manage employee leave requests</p>
                </div>
            </div>

            <a href="/dashboard"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-5 flex items-center gap-2 p-3 bg-green-50 text-green-700 text-sm rounded-lg ring-1 ring-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Toolbar: Search + Add Button --}}
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="relative flex-1 max-w-xs">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" id="searchInput" onkeyup="filterLeaves()" placeholder="Search by employee or type..."
                       class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            </div>

            <button onclick="openAddModal()"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-gray-800 hover:shadow-md active:scale-[0.98] transition-all duration-200 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Leave
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-xl ring-1 ring-gray-100">
            <table class="w-full text-sm">
                <thead>
                <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
                    <th class="py-3 px-4 font-semibold">#</th>
                    <th class="py-3 px-4 font-semibold">Employee</th>
                    <th class="py-3 px-4 font-semibold">Type</th>
                    <th class="py-3 px-4 font-semibold">Start Date</th>
                    <th class="py-3 px-4 font-semibold">End Date</th>
                    <th class="py-3 px-4 font-semibold">Status</th>
                    <th class="py-3 px-4 font-semibold text-right">Actions</th>
                </tr>
                </thead>
                <tbody id="leavesTable" class="divide-y divide-gray-100">
                @forelse($leaves as $leave)
                    <tr class="leave-row hover:bg-gray-50/80 transition-colors">
                        <td class="py-3.5 px-4 text-gray-400 font-medium">{{ $leave->id }}</td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-semibold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($leave->employee->first_name ?? '', 0, 1).substr($leave->employee->last_name ?? '', 0, 1)) ?: 'NA' }}
                                </div>
                                <div>
                                    <span class="leave-employee text-gray-800 font-medium">
                                        {{ $leave->employee ? $leave->employee->first_name.' '.$leave->employee->last_name : 'N/A' }}
                                    </span>
                                    <p class="text-xs text-gray-400">ID: {{ $leave->employee_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="leave-type inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                {{ $leave->leave_type }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="leave-start inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="leave-end inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-50 text-yellow-700',
                                    'approved' => 'bg-green-50 text-green-700',
                                    'rejected' => 'bg-red-50 text-red-700',
                                ];
                            @endphp
                            <span class="leave-status inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$leave->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                <button type="button"
                                        onclick="openEditModal(
                                            {{ $leave->id }},
                                            {{ $leave->employee_id }},
                                            '{{ $leave->leave_type }}',
                                            '{{ $leave->start_date }}',
                                            '{{ $leave->end_date }}',
                                            '{{ addslashes($leave->reason) }}',
                                            '{{ $leave->status }}'
                                        )"
                                        class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                </button>

                                <x-Form.deletebutton
                                    :action="route('leaves.destroy', $leave->id)"
                                    confirmation-message="Are you sure you want to delete this leave record?"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                </svg>
                                <p class="text-sm font-medium">No leave records yet</p>
                                <p class="text-xs">Click "Add Leave" to create your first one.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <p id="noResults" class="hidden text-center text-sm text-gray-400 py-8">
            No leave records match your search.
        </p>

        {{-- 👇 Test එකට ඕන hidden input එක --}}
        <input type="hidden" id="edit_leave_id">
    </div>

    {{-- Modal --}}
    @include('Leave.partials.leave-modal', ['employees' => $employees])

    <script>
        function openAddModal() {
            const form = document.getElementById('leaveForm');
            form.reset();
            form.action = "{{ route('leaves.store') }}";
            document.getElementById('leaveMethod').disabled = true;
            document.getElementById('leaveModalTitle').textContent = 'Add New Leave';
            document.getElementById('leaveSubmit').textContent = 'Add Leave';
            document.getElementById('edit_leave_id').value = '';
            document.getElementById('leaveModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('leaveModal').classList.add('hidden');
        }

        function openEditModal(id, employeeId, leaveType, startDate, endDate, reason, status) {
            const form = document.getElementById('leaveForm');
            form.action = '/leaves/' + id;
            document.getElementById('leaveMethod').disabled = false;
            document.getElementById('leaveEmployee').value = employeeId;
            document.getElementById('leaveType').value = leaveType;
            document.getElementById('leaveStartDate').value = startDate;
            document.getElementById('leaveEndDate').value = endDate;
            document.getElementById('leaveReason').value = reason;
            document.getElementById('leaveStatus').value = status;
            document.getElementById('edit_leave_id').value = id;
            document.getElementById('leaveModalTitle').textContent = 'Edit Leave';
            document.getElementById('leaveSubmit').textContent = 'Update Leave';
            document.getElementById('leaveModal').classList.remove('hidden');
        }

        function filterLeaves() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.leave-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const employee = row.querySelector('.leave-employee').textContent.toLowerCase();
                const type = row.querySelector('.leave-type').textContent.toLowerCase();
                const status = row.querySelector('.leave-status').textContent.toLowerCase();
                const match = employee.includes(query) || type.includes(query) || status.includes(query);
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            document.getElementById('noResults').classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeAddModal();
        });

        {{-- Auto-open modal on validation errors --}}
        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('leaveModal');
            if (modal) {
                modal.classList.remove('hidden');
                @if(old('_method') === 'PUT')
                document.getElementById('leaveForm').action = '/leaves/' + {{ old('_leave_id', 0) }};
                document.getElementById('leaveMethod').disabled = false;
                document.getElementById('leaveModalTitle').textContent = 'Edit Leave';
                document.getElementById('leaveSubmit').textContent = 'Update Leave';
                @endif
            }
        });
        @endif
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.96) translateY(4px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
</x-layout>
