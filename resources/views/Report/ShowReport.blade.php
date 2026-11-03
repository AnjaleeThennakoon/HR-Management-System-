<x-layout title="Attendance Report Details - HR System">
    <main class="mx-auto w-full max-w-4xl space-y-6 p-4 sm:p-6">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
            <div class="border-b border-slate-200 pb-5">
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Attendance report</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">{{ $first_name }} {{ $last_name }}</h1>
                <p class="mt-2 text-sm text-slate-500">Employee ID: #{{ $employee_id }}</p>
            </div>

            <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-slate-500">Attendance date</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $attendance_date }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Sending date</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $sending_date ?? 'Not available' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">In time</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $in_time ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Out time</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $out_time ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Work hours</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $work_hours ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Status</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $status }}</dd>
                </div>
            </dl>
        </section>
    </main>
</x-layout>
