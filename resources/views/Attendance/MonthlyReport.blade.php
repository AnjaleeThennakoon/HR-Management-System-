<x-layout title="Attendance Report - HR System">
    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-black/5 p-8 max-w-6xl w-full">

        <div class="flex items-center justify-between mb-6 pb-5 border-b border-gray-100">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Attendance Report</h1>
            <a href="{{ route('attendance.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Back to Attendance</a>
        </div>

        {{-- Form --}}
        <form method="GET" action="{{ route('attendance.monthly-report') }}" class="flex flex-col md:flex-row gap-4 mb-6 bg-gray-50 p-4 rounded-xl ring-1 ring-gray-100">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Month</label>
                <input type="month" name="month" value="{{ $selectedMonth }}" required
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-indigo-500">
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Employee</label>
                <select name="employee_id" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-indigo-500">
                    <option value="">Choose Employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @selected($selectedEmployeeId == $emp->id)>
                            {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800">
                    View Report
                </button>
            </div>
        </form>

        @if($employee)
            <div class="mb-6 p-4 bg-indigo-50 rounded-xl ring-1 ring-indigo-100 flex items-center gap-4">
                <div>
                    <h3 class="font-bold text-gray-800">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                    <p class="text-sm text-gray-600">
                        Shift: <span class="font-semibold">{{ $employee->shift_in ?? 'N/A' }}</span> to <span class="font-semibold">{{ $employee->shift_out ?? 'N/A' }}</span>
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl ring-1 ring-gray-100">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
                        <th class="py-3 px-4 font-semibold">Date</th>
                        <th class="py-3 px-4 font-semibold">In Time</th>
                        <th class="py-3 px-4 font-semibold">Out Time</th>
                        <th class="py-3 px-4 font-semibold">Late</th>
                        <th class="py-3 px-4 font-semibold">OT</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($attendances as $att)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-3.5 px-4 font-medium text-gray-800">{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                            <td class="py-3.5 px-4">{{ $att->in_time ?? '-' }}</td>
                            <td class="py-3.5 px-4">{{ $att->out_time ?? '-' }}</td>
                            <td class="py-3.5 px-4">
                                @if($att->late_minutes > 0)
                                    <span class="text-red-600 font-semibold">{{ $att->late_minutes }} mins</span>
                                @else
                                    <span class="text-green-600 font-semibold">On Time</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($att->ot_minutes > 0)
                                    <span class="text-indigo-600 font-semibold">{{ $att->ot_minutes }} mins</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-gray-400">No records found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layout>
