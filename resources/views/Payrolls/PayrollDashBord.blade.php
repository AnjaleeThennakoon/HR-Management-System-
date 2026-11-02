<x-layout title="Payroll - HR System">
    <div class="mx-auto w-full max-w-7xl rounded-2xl bg-white p-6 shadow-lg ring-1 ring-black/5 sm:p-8">
        <div class="mb-8 flex flex-col gap-4 border-b border-gray-100 pb-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9.75h19.5m-16.5 3h3m-3 3h3m-3 3h3m6-6h3m-3 3h3m-3 3h3M3.75 4.5h16.5A1.5 1.5 0 0121.75 6v12a1.5 1.5 0 01-1.5 1.5H3.75A1.5 1.5 0 012.25 18V6a1.5 1.5 0 011.5-1.5z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Monthly Payroll</h1>
                    <p class="mt-1 text-sm text-gray-500">Review generated payroll records and salary breakdowns.</p>
                </div>
            </div>

            <div class="rounded-xl bg-indigo-50 px-5 py-3">
                <p class="text-xs font-medium uppercase tracking-wide text-indigo-600">Payroll records</p>
                <p class="mt-1 text-2xl font-bold text-indigo-900">{{ $payrolls->total() }}</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl ring-1 ring-gray-100">
            <table class="w-full text-left text-sm">
                <thead>
                <tr class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <th class="whitespace-nowrap px-4 py-3 font-semibold">Employee ID</th>
                    <th class="whitespace-nowrap px-4 py-3 font-semibold">Pay period</th>
                    <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">Basic salary</th>
                    <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">Allowances</th>
                    <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">Deductions</th>
                    <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">Net salary</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($payrolls as $payroll)
                    <tr class="transition-colors hover:bg-gray-50/80">
                        <td class="whitespace-nowrap px-4 py-4 font-medium text-gray-800">#{{ $payroll->employee_id }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-gray-600">{{ str_pad((string) $payroll->month, 2, '0', STR_PAD_LEFT) }}/{{ $payroll->year }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-right text-gray-600">{{ number_format((float) $payroll->basic_salary, 2) }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-right text-gray-600">{{ number_format((float) $payroll->allowances, 2) }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-right text-gray-600">{{ number_format((float) $payroll->deductions, 2) }}</td>
                        <td class="whitespace-nowrap px-4 py-4 text-right font-semibold text-gray-900">{{ number_format((float) $payroll->net_salary, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-14 text-center">
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
                </tbody>
            </table>
        </div>

        @if ($payrolls->hasPages())
            <div class="mt-6">
                {{ $payrolls->links() }}
            </div>
        @endif
    </div>
</x-layout>
