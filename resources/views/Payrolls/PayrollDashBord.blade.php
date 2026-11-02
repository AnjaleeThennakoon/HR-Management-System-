<x-layout title="Monthly Salary - HR System">
    <div class="mx-auto w-full max-w-7xl rounded-2xl bg-white p-6 shadow-lg ring-1 ring-black/5 sm:p-8 space-y-6">

        {{-- Header Section (Consistent with HR System style) --}}
        <div class="flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9.75h19.5m-16.5 3h3m-3 3h3m-3 3h3m6-6h3m-3 3h3m-3 3h3M3.75 4.5h16.5A1.5 1.5 0 0121.75 6v12a1.5 1.5 0 01-1.5 1.5H3.75A1.5 1.5 0 012.25 18V6a1.5 1.5 0 011.5-1.5z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Monthly Salary</h1>
                    <p class="mt-1 text-sm text-gray-500">Review generated payroll records and salary breakdowns.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="/dashboard"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-gray-900 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Dashboard
                </a>

                <div class="rounded-xl bg-indigo-50 px-5 py-3">
                    <p class="text-xs font-medium uppercase tracking-wide text-indigo-600">Payroll records</p>
                    <p class="mt-1 text-2xl font-bold text-indigo-900">{{ $payrolls->total() }}</p>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        <x-alert.success />

        @if (session('error'))
            <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Simple Toolbar --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex flex-1 flex-col sm:flex-row items-stretch sm:items-center gap-2">
                {{-- Search --}}
                <div class="relative max-w-xs flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" id="salarySearchInput" onkeyup="filterSalaryTable()" placeholder="Search records..."
                           class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>

                {{-- Month --}}
                <select id="monthFilter" onchange="filterSalaryTable()"
                        class="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">All Months</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ str_pad((string)$m, 2, '0', STR_PAD_LEFT) }}">
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>

                {{-- Year --}}
                <select id="yearFilter" onchange="filterSalaryTable()"
                        class="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">All Years</option>
                    @for($y = date('Y') - 3; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                <button type="button" onclick="openGenerateModal()"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-gray-800 active:scale-[0.98] transition-all whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Process Payment
                </button>
            </div>
        </div>

        {{-- Clean Table --}}
        <div class="overflow-x-auto rounded-xl ring-1 ring-gray-100">
            <table class="w-full text-left text-sm">
                <thead>
                <tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <th class="whitespace-nowrap px-4 py-3 font-semibold">Employee</th>
                    <th class="whitespace-nowrap px-4 py-3 font-semibold">Pay period</th>
                    <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">Basic salary</th>
                    <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">Allowances</th>
                    <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">Deductions</th>
                    <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">Net salary</th>
                    <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
                </thead>

                <tbody id="salaryTableBody" class="divide-y divide-gray-100">
                @forelse($payrolls as $payroll)
                    @php
                        $employee = $payroll->employee;
                        $firstName = $employee?->first_name ?? 'Employee';
                        $lastName = $employee?->last_name ?? '';
                        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1)) ?: 'EM';
                        $departmentName = $employee?->department?->name ?? 'General';
                        $designationName = $employee?->designation?->name ?? 'Staff';
                        $code = $employee?->employee_id ?? $payroll->employee_id;
                        $formattedMonth = str_pad((string) $payroll->month, 2, '0', STR_PAD_LEFT);
                        $monthName = date('F', mktime(0, 0, 0, (int)$payroll->month, 1));
                        $grossSalary = (float)$payroll->basic_salary + (float)$payroll->allowances;
                    @endphp

                    <tr class="salary-row transition-colors hover:bg-gray-50/80"
                        data-search="{{ strtolower($firstName . ' ' . $lastName . ' ' . $code . ' ' . $monthName . ' ' . $payroll->year . ' ' . $payroll->net_salary) }}"
                        data-month="{{ $formattedMonth }}"
                        data-year="{{ $payroll->year }}"
                        data-employee-name="{{ $firstName }} {{ $lastName }}"
                        data-employee-id="{{ $code }}"
                        data-initials="{{ $initials }}"
                        data-department="{{ $departmentName }}"
                        data-designation="{{ $designationName }}"
                        data-period="{{ $monthName }} {{ $payroll->year }}"
                        data-basic="{{ number_format((float) $payroll->basic_salary, 2) }}"
                        data-allowances="{{ number_format((float) $payroll->allowances, 2) }}"
                        data-deductions="{{ number_format((float) $payroll->deductions, 2) }}"
                        data-gross="{{ number_format((float) $grossSalary, 2) }}"
                        data-net="{{ number_format((float) $payroll->net_salary, 2) }}">

                        {{-- Employee --}}
                        <td class="whitespace-nowrap px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-semibold text-xs flex-shrink-0">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <span class="font-medium text-gray-800">{{ $firstName }} {{ $lastName }}</span>
                                    <p class="text-xs text-gray-400">ID: #{{ $code }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Pay Period --}}
                        <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                            {{ $formattedMonth }}/{{ $payroll->year }}
                        </td>

                        {{-- Basic Salary --}}
                        <td class="whitespace-nowrap px-4 py-4 text-right text-gray-600">
                            {{ number_format((float) $payroll->basic_salary, 2) }}
                        </td>

                        {{-- Allowances --}}
                        <td class="whitespace-nowrap px-4 py-4 text-right text-gray-600">
                            {{ number_format((float) $payroll->allowances, 2) }}
                        </td>

                        {{-- Deductions --}}
                        <td class="whitespace-nowrap px-4 py-4 text-right text-gray-600">
                            {{ number_format((float) $payroll->deductions, 2) }}
                        </td>

                        {{-- Net Salary --}}
                        <td class="whitespace-nowrap px-4 py-4 text-right font-semibold text-gray-900">
                            {{ number_format((float) $payroll->net_salary, 2) }}
                        </td>

                        {{-- Actions --}}
                        <td class="whitespace-nowrap px-4 py-4 text-right">
                            <button type="button" onclick="openPayslipFromRow(this)"
                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                                View Slip
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-14 text-center">
                            <div class="mx-auto flex max-w-sm flex-col items-center gap-2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9.75h19.5m-16.5 3h3m-3 3h3m-3 3h3m6-6h3m-3 3h3m-3 3h3M3.75 4.5h16.5A1.5 1.5 0 0121.75 6v12a1.5 1.5 0 01-1.5 1.5H3.75A1.5 1.5 0 012.25 18V6a1.5 1.5 0 011.5-1.5z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-600">No payroll records yet</p>
                                <p class="text-xs">Generated monthly payroll records will appear here.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse

                <tr id="noSalaryResultsRow" class="hidden">
                    <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">
                        No matching records found.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($payrolls->hasPages())
            <div class="mt-6">
                {{ $payrolls->links() }}
            </div>
        @endif

    </div>

    {{-- Modals --}}
    @include('Payrolls.Partials.Payroll-modal')

    {{-- Scripts --}}
    <script>
        function openGenerateModal() {
            const modal = document.getElementById('generateSalaryModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeGenerateModal() {
            const modal = document.getElementById('generateSalaryModal');
            if (modal) modal.classList.add('hidden');
        }

        @if ($errors->has('month'))
            openGenerateModal();
        @endif

        function openPayslipModal(data) {
            const modal = document.getElementById('payslipModal');
            if (!modal) return;

            document.getElementById('payslipEmployeeName').textContent = data.employeeName || 'Employee';
            document.getElementById('payslipEmployeeId').textContent = '#' + (data.employeeId || '');
            document.getElementById('payslipDepartment').textContent = data.department || 'General';
            document.getElementById('payslipDesignation').textContent = data.designation || 'Staff';
            document.getElementById('payslipAvatar').textContent = data.initials || 'EM';
            document.getElementById('payslipPeriodBadge').textContent = data.period || '';

            document.getElementById('payslipBasicSalary').textContent = data.basic || '0.00';
            document.getElementById('payslipAllowances').textContent = data.allowances || '0.00';
            document.getElementById('payslipGrossSalary').textContent = data.gross || '0.00';

            document.getElementById('payslipDeductions').textContent = data.deductions || '0.00';
            document.getElementById('payslipTotalDeductions').textContent = data.deductions || '0.00';

            document.getElementById('payslipNetSalary').textContent = 'Rs. ' + (data.net || '0.00');

            modal.classList.remove('hidden');
        }

        function closePayslipModal() {
            const modal = document.getElementById('payslipModal');
            if (modal) modal.classList.add('hidden');
        }

        function openPayslipFromRow(btn) {
            const row = btn.closest('.salary-row');
            if (!row) return;

            openPayslipModal({
                employeeName: row.dataset.employeeName,
                employeeId: row.dataset.employeeId,
                initials: row.dataset.initials,
                department: row.dataset.department,
                designation: row.dataset.designation,
                period: row.dataset.period,
                basic: row.dataset.basic,
                allowances: row.dataset.allowances,
                deductions: row.dataset.deductions,
                gross: row.dataset.gross,
                net: row.dataset.net
            });
        }

        function printPayslipSlip() {
            window.print();
        }

        function filterSalaryTable() {
            const query = document.getElementById('salarySearchInput').value.toLowerCase().trim();
            const month = document.getElementById('monthFilter').value;
            const year = document.getElementById('yearFilter').value;
            const rows = document.querySelectorAll('.salary-row');
            let visibleCount = 0;

            rows.forEach(function (row) {
                const searchMatch = !query || (row.dataset.search && row.dataset.search.includes(query));
                const monthMatch = !month || row.dataset.month === month;
                const yearMatch = !year || row.dataset.year === year;

                if (searchMatch && monthMatch && yearMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noResults = document.getElementById('noSalaryResultsRow');
            if (noResults) {
                noResults.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
            }
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closePayslipModal();
                closeGenerateModal();
            }
        });
    </script>

    <style>
        #printablePayslip {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.97); }
            to { opacity: 1; transform: scale(1); }
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #payslipModal, #payslipModal * {
                visibility: visible;
            }
            #payslipModal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white !important;
                padding: 0 !important;
            }
            #payslipModal button {
                display: none !important;
            }
            #payslipModal > div > :not(#printablePayslip) {
                display: none !important;
            }
            #payslipModal > div {
                width: 100% !important;
                max-width: none !important;
                max-height: none !important;
                display: block !important;
                margin: 0 !important;
                border: 0 !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                overflow: visible !important;
            }
            #printablePayslip {
                max-height: none !important;
                overflow: visible !important;
                padding: 0 !important;
            }
        }
    </style>
</x-layout>
