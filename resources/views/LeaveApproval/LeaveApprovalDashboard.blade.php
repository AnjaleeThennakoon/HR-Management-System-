<x-layout title="Leave Approvals - HR System">
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-6xl w-full">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Leave Approvals</h1>

        <div class="overflow-x-auto rounded-xl ring-1 ring-gray-100">
            <table class="w-full text-sm">
                <thead>
                <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase">
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Employee</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Remarks</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($leaves as $approval)
                <tr>
                    <td class="py-3 px-4">{{ $approval->id }}</td>
                    <td class="py-3 px-4">
                        {{ $approval->leave->employee->first_name ?? 'N/A' }}
                        {{ $approval->leave->employee->last_name ?? '' }}
                    </td>
                    <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                    @if($approval->status === 'pending') bg-yellow-50 text-yellow-700
                                    @elseif($approval->status === 'approved') bg-green-50 text-green-700
                                    @else bg-red-50 text-red-700 @endif">
                                    {{ ucfirst($approval->status) }}
                                </span>
                    </td>
                    <td class="py-3 px-4">{{ $approval->remarks }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 text-center text-gray-400">
                        No leave approvals yet
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
