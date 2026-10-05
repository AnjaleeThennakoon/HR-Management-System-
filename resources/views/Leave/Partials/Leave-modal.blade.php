<div id="leaveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 w-full max-w-md animate-[fadeIn_0.2s_ease-out]">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h3 id="leaveModalTitle" class="text-lg font-semibold text-gray-900">Add New Leave</h3>
            <button type="button" onclick="closeAddModal()"
                    class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="leaveForm" method="POST" action="{{ route('leaves.store') }}" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="leaveMethod" value="PUT" disabled>
            <input type="hidden" name="_leave_id" id="leaveId" value="{{ old('_leave_id') }}">

            {{-- Employee --}}
            <div>
                <label for="leaveEmployee" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Employee <span class="text-red-500">*</span>
                </label>
                <select id="leaveEmployee" name="employee_id" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                            {{ $employee->first_name }} {{ $employee->last_name }} (ID: {{ $employee->id }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Leave Type --}}
            <div>
                <label for="leaveType" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Leave Type <span class="text-red-500">*</span>
                </label>
                <select id="leaveType" name="leave_type" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <option value="">Select Type</option>
                    <option value="Annual" @selected(old('leave_type') == 'Annual')>Annual</option>
                    <option value="Medical" @selected(old('leave_type') == 'Medical')>Medical</option>
                    <option value="casual" @selected(old('leave_type') == 'casual')>Casual</option>
                </select>
                @error('leave_type')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Start Date + End Date --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="leaveStartDate" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="leaveStartDate" name="start_date" required
                           value="{{ old('start_date') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    @error('start_date')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="leaveEndDate" class="block text-sm font-medium text-gray-700 mb-1.5">
                        End Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="leaveEndDate" name="end_date" required
                           value="{{ old('end_date') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    @error('end_date')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Reason --}}
            <div>
                <label for="leaveReason" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Reason <span class="text-red-500">*</span>
                </label>
                <textarea id="leaveReason" name="reason" rows="3" required
                          placeholder="Enter the reason for leave..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">{{ old('reason') }}</textarea>
                @error('reason')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="leaveStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="leaveStatus" name="status" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <option value="pending" @selected(old('status', 'pending') == 'pending')>Pending</option>
                    <option value="approved" @selected(old('status') == 'approved')>Approved</option>
                    <option value="rejected" @selected(old('status') == 'rejected')>Rejected</option>
                </select>
                @error('status')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" id="leaveSubmit"
                        class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg shadow-sm hover:bg-gray-800 hover:shadow-md active:scale-[0.98] transition-all duration-200">
                    Add Leave
                </button>
            </div>
        </form>
    </div>
</div>
