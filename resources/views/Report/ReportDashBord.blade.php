<x-layout title="Reports - HR System">
    <main class="mx-auto w-full max-w-7xl space-y-6">
        <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-black/5 sm:p-8">
            <div class="flex flex-col gap-5 border-b border-gray-100 pb-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">HR insights</p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Reports dashboard</h1>
                    <p class="mt-2 text-sm text-gray-500">A quick overview of your workforce and monthly activity.</p>
                    <span class="mt-3 inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">Preview data</span>
                </div>
                <div class="inline-flex w-fit items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700">
                    <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 3v3m8-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z" />
                    </svg>
                    {{ now()->format('F Y') }}
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-xl border border-gray-100 bg-white p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total employees</p>
                            <p class="mt-3 text-3xl font-bold text-gray-900">248</p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8-7.5a4 4 0 0 1 0 7.75M20 21v-2a4 4 0 0 0-3-3.87" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-gray-400">Across all departments</p>
                </article>

                <article class="rounded-xl border border-gray-100 bg-white p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Attendance rate</p>
                            <p class="mt-3 text-3xl font-bold text-gray-900">94.8<span class="text-xl">%</span></p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-gray-400">For the current month</p>
                </article>

                <article class="rounded-xl border border-gray-100 bg-white p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pending leave requests</p>
                            <p class="mt-3 text-3xl font-bold text-gray-900">12</p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 3v3m8-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Zm3 8h.01M12 13h.01M16 13h.01M8 17h.01M12 17h.01" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-gray-400">Awaiting review</p>
                </article>

                <article class="rounded-xl border border-gray-100 bg-white p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Monthly payroll</p>
                            <p class="mt-3 text-3xl font-bold text-gray-900">$184,250</p>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 4h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm3 10h3" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-gray-400">Current pay period</p>
                </article>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <article class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-black/5 sm:p-8 xl:col-span-2">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Attendance overview</h2>
                        <p class="mt-1 text-sm text-gray-500">Monthly attendance rate by week</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">This month</span>
                </div>

                <div class="mt-8 grid h-56 grid-cols-4 items-end gap-4 border-b border-l border-gray-100 px-3 sm:gap-8">
                    <div class="flex h-full flex-col items-center justify-end gap-3">
                        <div class="flex h-full w-full items-end justify-center">
                            <div class="w-full max-w-16 rounded-t-lg bg-indigo-200 transition-colors hover:bg-indigo-300" style="height: 72%"></div>
                        </div>
                        <span class="pb-3 text-xs font-medium text-gray-500">Week 1</span>
                    </div>
                    <div class="flex h-full flex-col items-center justify-end gap-3">
                        <div class="flex h-full w-full items-end justify-center">
                            <div class="w-full max-w-16 rounded-t-lg bg-indigo-300 transition-colors hover:bg-indigo-400" style="height: 84%"></div>
                        </div>
                        <span class="pb-3 text-xs font-medium text-gray-500">Week 2</span>
                    </div>
                    <div class="flex h-full flex-col items-center justify-end gap-3">
                        <div class="flex h-full w-full items-end justify-center">
                            <div class="w-full max-w-16 rounded-t-lg bg-indigo-400 transition-colors hover:bg-indigo-500" style="height: 78%"></div>
                        </div>
                        <span class="pb-3 text-xs font-medium text-gray-500">Week 3</span>
                    </div>
                    <div class="flex h-full flex-col items-center justify-end gap-3">
                        <div class="flex h-full w-full items-end justify-center">
                            <div class="w-full max-w-16 rounded-t-lg bg-indigo-600 transition-colors hover:bg-indigo-700" style="height: 95%"></div>
                        </div>
                        <span class="pb-3 text-xs font-medium text-gray-500">Week 4</span>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                    <span>0%</span>
                    <span>Attendance rate</span>
                    <span>100%</span>
                </div>
            </article>

            <article class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-black/5 sm:p-8">
                <h2 class="text-lg font-semibold text-gray-900">Employees by department</h2>
                <p class="mt-1 text-sm text-gray-500">Workforce distribution</p>

                <div class="mt-7 space-y-5">
                    <div>
                        <div class="mb-2 flex justify-between text-sm">
                            <span class="font-medium text-gray-700">Engineering</span>
                            <span class="text-gray-500">86</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-gray-100"><div class="h-2.5 rounded-full bg-indigo-600" style="width: 78%"></div></div>
                    </div>
                    <div>
                        <div class="mb-2 flex justify-between text-sm">
                            <span class="font-medium text-gray-700">Operations</span>
                            <span class="text-gray-500">64</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-gray-100"><div class="h-2.5 rounded-full bg-sky-500" style="width: 58%"></div></div>
                    </div>
                    <div>
                        <div class="mb-2 flex justify-between text-sm">
                            <span class="font-medium text-gray-700">Sales</span>
                            <span class="text-gray-500">52</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-gray-100"><div class="h-2.5 rounded-full bg-emerald-500" style="width: 47%"></div></div>
                    </div>
                    <div>
                        <div class="mb-2 flex justify-between text-sm">
                            <span class="font-medium text-gray-700">Human resources</span>
                            <span class="text-gray-500">46</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-gray-100"><div class="h-2.5 rounded-full bg-amber-500" style="width: 42%"></div></div>
                    </div>
                </div>
            </article>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-black/5 sm:p-8">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Recent reports</h2>
                    <p class="mt-1 text-sm text-gray-500">A snapshot of the latest HR activity.</p>
                </div>
                <span class="mt-2 text-xs text-gray-400 sm:mt-0">Preview data</span>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="w-full min-w-150 text-left text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400">
                            <th class="pb-3 pr-4 font-semibold">Report</th>
                            <th class="pb-3 pr-4 font-semibold">Period</th>
                            <th class="pb-3 pr-4 font-semibold">Last updated</th>
                            <th class="pb-3 text-right font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-4 pr-4 font-medium text-gray-800">Attendance summary</td>
                            <td class="py-4 pr-4 text-gray-500">{{ now()->format('F Y') }}</td>
                            <td class="py-4 pr-4 text-gray-500">Today</td>
                            <td class="py-4 text-right"><span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">Ready</span></td>
                        </tr>
                        <tr>
                            <td class="py-4 pr-4 font-medium text-gray-800">Department headcount</td>
                            <td class="py-4 pr-4 text-gray-500">Current</td>
                            <td class="py-4 pr-4 text-gray-500">Yesterday</td>
                            <td class="py-4 text-right"><span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">Ready</span></td>
                        </tr>
                        <tr>
                            <td class="py-4 pr-4 font-medium text-gray-800">Payroll overview</td>
                            <td class="py-4 pr-4 text-gray-500">{{ now()->format('F Y') }}</td>
                            <td class="py-4 pr-4 text-gray-500">2 days ago</td>
                            <td class="py-4 text-right"><span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">In review</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</x-layout>
