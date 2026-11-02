{{-- ==============================================================================
     MONTHLY SALARY MODALS (Clean & Simple Theme)
============================================================================== --}}

{{-- 1. DETAILED PAYSLIP MODAL --}}
<div id="payslipModal"
     class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl ring-1 ring-black/5 overflow-hidden my-8 animate-[fadeIn_0.2s_ease-out]">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Salary Slip</h3>
                    <p id="payslipPeriodBadge" class="text-xs text-gray-500">Monthly Compensation Details</p>
                </div>
            </div>

            <button type="button" onclick="closePayslipModal()"
                    class="rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Slip Content --}}
        <div id="printablePayslip" class="p-6 space-y-5 bg-white text-gray-800">

            {{-- Employee Info Summary --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-gray-50 border border-gray-100">
                <div class="flex items-center gap-3">
                    <div id="payslipAvatar" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-semibold text-sm">
                        EMP
                    </div>
                    <div>
                        <h4 id="payslipEmployeeName" class="text-sm font-semibold text-gray-900">Employee Name</h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            <span id="payslipEmployeeId" class="font-medium text-gray-700">#000</span>
                            <span class="mx-1">•</span>
                            <span id="payslipDepartment">Department</span>
                            <span class="mx-1">•</span>
                            <span id="payslipDesignation">Designation</span>
                        </p>
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                        Processed
                    </span>
                </div>
            </div>

            {{-- Breakdown Columns --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Earnings --}}
                <div class="rounded-xl border border-gray-200 p-4 bg-white flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-2.5 border-b border-gray-100">
                            <h5 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Earnings</h5>
                            <span class="text-xs text-emerald-600 font-medium">+ Credit</span>
                        </div>
                        <div class="mt-3 space-y-2.5 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Basic Salary</span>
                                <span id="payslipBasicSalary" class="font-medium text-gray-900">0.00</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Allowances / OT</span>
                                <span id="payslipAllowances" class="font-medium text-emerald-600">0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between font-semibold text-sm text-gray-900">
                        <span>Total Earnings</span>
                        <span id="payslipGrossSalary">0.00</span>
                    </div>
                </div>

                {{-- Deductions --}}
                <div class="rounded-xl border border-gray-200 p-4 bg-white flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-2.5 border-b border-gray-100">
                            <h5 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Deductions</h5>
                            <span class="text-xs text-rose-600 font-medium">- Debit</span>
                        </div>
                        <div class="mt-3 space-y-2.5 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Late & No-Pay</span>
                                <span id="payslipDeductions" class="font-medium text-rose-600">0.00</span>
                            </div>
                            <div class="flex justify-between text-gray-400 text-xs">
                                <span>Other Deductions</span>
                                <span>0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between font-semibold text-sm text-gray-900">
                        <span>Total Deductions</span>
                        <span id="payslipTotalDeductions" class="text-rose-600">0.00</span>
                    </div>
                </div>

            </div>

            {{-- Net Salary Card --}}
            <div class="rounded-xl bg-gray-50 border border-gray-200 p-4 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Take-Home Amount</span>
                    <p class="text-sm font-medium text-gray-800">Net Payable Salary</p>
                </div>
                <div class="text-right">
                    <span id="payslipNetSalary" class="text-xl font-bold text-gray-900">Rs. 0.00</span>
                </div>
            </div>

            <p class="text-center text-xs text-gray-400 pt-1">
                Computer-generated official salary slip.
            </p>

        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <button type="button" onclick="closePayslipModal()"
                    class="px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                Close
            </button>

            <button type="button" onclick="printPayslipSlip()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.07-.642-2.115-1.2-3.089m0 0a9 9 0 0113.96 0m-13.96 0A9.01 9.01 0 003 12c0 2.5 1.01 4.76 2.64 6.38M17.28 10.74c.558.974.96 2.019 1.2 3.089m0 0A9.01 9.01 0 0121 12c0-2.5-1.01-4.76-2.64-6.38" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18h12v3H6v-3zM8 14h.01M12 14h.01M16 14h.01" />
                </svg>
                Print Slip
            </button>
        </div>

    </div>
</div>


{{-- 2. GENERATE MONTHLY SALARY MODAL --}}
<div id="generateSalaryModal"
     class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl ring-1 ring-black/5 overflow-hidden my-8 animate-[fadeIn_0.2s_ease-out]">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Process Monthly Payment</h3>
                    <p class="text-xs text-gray-500">Calculate and save salary payment to database</p>
                </div>
            </div>

            <button type="button" onclick="closeGenerateModal()"
                    class="rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form Content --}}
        <form method="POST" action="{{ route('payrolls.store') }}" class="p-6 space-y-4">
            @csrf

            {{-- Employee --}}
            <div>
                <label for="modal_employee_id" class="block text-xs font-semibold uppercase tracking-wide text-gray-600 mb-1.5">
                    Employee <span class="text-red-500">*</span>
                </label>
                @php
                    $formEmployees = \App\Models\Employee::orderBy('first_name')->get();
                @endphp
                <select name="employee_id" id="modal_employee_id" required
                        class="w-full rounded-lg border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                    <option value="" disabled selected>Select an employee...</option>
                    @foreach ($formEmployees as $emp)
                        <option value="{{ $emp->id }}">
                            {{ $emp->first_name }} {{ $emp->last_name }} (#{{ $emp->employee_id ?? $emp->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Month & Year --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="modal_month" class="block text-xs font-semibold uppercase tracking-wide text-gray-600 mb-1.5">
                        Month <span class="text-red-500">*</span>
                    </label>
                    <select name="month" id="modal_month" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" @selected(date('n') == $m)>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="modal_year" class="block text-xs font-semibold uppercase tracking-wide text-gray-600 mb-1.5">
                        Year <span class="text-red-500">*</span>
                    </label>
                    <select name="year" id="modal_year" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" @selected(date('Y') == $y)>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            {{-- Subtle Note --}}
            <div class="rounded-lg bg-gray-50 border border-gray-100 p-3 text-xs text-gray-600">
                <span class="font-medium text-gray-800">Notice:</span> Overtime allowances and late/no-pay deductions are automatically evaluated from finalized attendance.
            </div>

            {{-- Footer Buttons --}}
            <div class="pt-3 flex items-center justify-end gap-2 border-t border-gray-100">
                <button type="button" onclick="closeGenerateModal()"
                        class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition">
                    Process Payment
                </button>
            </div>

        </form>

    </div>
</div>
