{{-- ==============================================================================
     MONTHLY SALARY MODALS (Clean & Simple Theme)
============================================================================== --}}

{{-- 1. DETAILED PAYSLIP MODAL --}}
<div id="payslipModal"
     class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm overflow-y-auto">
    <div class="relative my-3 flex max-h-[78vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/10">
        <div class="screen-only flex justify-end border-b border-slate-100 px-5 py-3">
            <button type="button" onclick="closePayslipModal()" aria-label="Close salary slip"
                    class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="printablePayslip" class="max-h-[calc(78vh-8rem)] overflow-y-auto bg-white text-slate-800">
            <header class="relative overflow-hidden bg-teal-900 px-7 py-7 text-white sm:px-10">
                <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-white/5"></div>
                <div class="absolute -bottom-24 right-20 h-56 w-56 rounded-full bg-teal-700/50"></div>
                <div class="relative flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-teal-100">HR System · Payroll Statement</p>
                        <h2 class="mt-2 text-3xl font-extrabold uppercase tracking-wide sm:text-4xl">Salary Slip</h2>
                        <p class="mt-3 text-sm text-teal-100">Pay period: <span id="payslipPeriodBadge" class="font-semibold text-white">Monthly Compensation</span></p>
                    </div>
                    <div class="rounded-xl border border-white/20 bg-white/10 px-5 py-4 sm:min-w-44">
                        <p class="text-lg font-extrabold uppercase tracking-wider text-white">HR System</p>
                        <p class="mt-1 text-xs text-teal-100">Monthly Payroll</p>
                    </div>
                </div>
            </header>

            <div class="space-y-7 px-6 py-7 sm:px-10">
                <section class="grid gap-5 rounded-xl border border-slate-200 bg-slate-50 p-5 sm:grid-cols-2">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-teal-800">Employee</p>
                        <div class="mt-3 flex items-center gap-3">
                            <div id="payslipAvatar" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-teal-800 text-sm font-bold text-white">EMP</div>
                            <div>
                                <h3 id="payslipEmployeeName" class="text-base font-bold text-slate-900">Employee Name</h3>
                                <p class="mt-0.5 text-xs text-slate-500">Employee ID <span id="payslipEmployeeId" class="font-semibold text-slate-700">#000</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 sm:border-l sm:border-slate-200 sm:pl-6">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500">Department</p>
                            <p id="payslipDepartment" class="mt-2 text-sm font-semibold text-slate-800">Department</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500">Designation</p>
                            <p id="payslipDesignation" class="mt-2 text-sm font-semibold text-slate-800">Designation</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h3 class="mb-3 text-sm font-extrabold uppercase tracking-wide text-teal-900">Income Details</h3>
                    <div class="overflow-hidden rounded-lg border border-slate-200">
                        <table class="w-full text-sm">
                            <thead class="bg-teal-700 text-left text-[10px] font-bold uppercase tracking-wider text-white">
                                <tr>
                                    <th class="w-16 px-4 py-3 text-center">No.</th>
                                    <th class="px-4 py-3">Information</th>
                                    <th class="w-40 px-4 py-3 text-right">Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <tr class="bg-white">
                                    <td class="px-4 py-3 text-center text-slate-500">1</td>
                                    <td class="px-4 py-3 font-medium">Basic Salary</td>
                                    <td id="payslipBasicSalary" class="px-4 py-3 text-right font-semibold">0.00</td>
                                </tr>
                                <tr class="bg-slate-50">
                                    <td class="px-4 py-3 text-center text-slate-500">2</td>
                                    <td class="px-4 py-3 font-medium">Allowances / Overtime</td>
                                    <td id="payslipAllowances" class="px-4 py-3 text-right font-semibold text-teal-800">0.00</td>
                                </tr>
                                <tr class="bg-teal-50 font-bold text-teal-950">
                                    <td colspan="2" class="px-4 py-3 text-right uppercase tracking-wide">Total Income</td>
                                    <td id="payslipGrossSalary" class="px-4 py-3 text-right">0.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section>
                    <h3 class="mb-3 text-sm font-extrabold uppercase tracking-wide text-teal-900">Deduction Details</h3>
                    <div class="overflow-hidden rounded-lg border border-slate-200">
                        <table class="w-full text-sm">
                            <thead class="bg-teal-700 text-left text-[10px] font-bold uppercase tracking-wider text-white">
                                <tr>
                                    <th class="w-16 px-4 py-3 text-center">No.</th>
                                    <th class="px-4 py-3">Information</th>
                                    <th class="w-40 px-4 py-3 text-right">Amount (Rs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white">
                                    <td class="px-4 py-3 text-center text-slate-500">1</td>
                                    <td class="px-4 py-3 font-medium">Late / No-Pay Deductions</td>
                                    <td id="payslipDeductions" class="px-4 py-3 text-right font-semibold text-rose-700">0.00</td>
                                </tr>
                                <tr class="bg-slate-100 font-bold text-slate-800">
                                    <td colspan="2" class="px-4 py-3 text-right uppercase tracking-wide">Total Deductions</td>
                                    <td id="payslipTotalDeductions" class="px-4 py-3 text-right text-rose-700">0.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="grid gap-0 overflow-hidden rounded-lg bg-teal-900 text-white sm:grid-cols-[1fr_auto]">
                    <div class="px-5 py-4 text-sm font-extrabold uppercase tracking-wide sm:text-base">Net Salary Received</div>
                    <div id="payslipNetSalary" class="border-t border-white/20 px-5 py-4 text-right text-lg font-extrabold sm:border-l sm:border-t-0">Rs. 0.00</div>
                </section>

                <p class="border-t border-slate-200 pt-4 text-xs text-slate-500">This salary slip was generated electronically by HR System.</p>
            </div>
        </div>

        <div class="screen-only flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4">
            <button type="button" onclick="closePayslipModal()"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                Close
            </button>
            <button type="button" onclick="printPayslipSlip()"
                    class="inline-flex items-center gap-2 rounded-lg bg-teal-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18h12v3H6v-3zM6.72 13.829c-.24-1.07-.642-2.115-1.2-3.089m0 0a9 9 0 0113.96 0m-13.96 0A9.01 9.01 0 003 12c0 2.5 1.01 4.76 2.64 6.38M17.28 10.74c.558.974.96 2.019 1.2 3.089" />
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
                        <option value="" disabled @selected(old('employee_id') === null)>Select an employee...</option>
                    @foreach ($formEmployees as $emp)
                        <option value="{{ $emp->id }}" @selected(old('employee_id') == $emp->id)>
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
                            <option value="{{ $m }}" @selected((int) old('month', date('n')) === $m)>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('month')
                        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="modal_year" class="block text-xs font-semibold uppercase tracking-wide text-gray-600 mb-1.5">
                        Year <span class="text-red-500">*</span>
                    </label>
                    <select name="year" id="modal_year" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" @selected((int) old('year', date('Y')) === $y)>
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
