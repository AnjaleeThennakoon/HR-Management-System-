<div id="leaveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 w-full max-w-md max-h-[90vh] flex flex-col animate-[fadeIn_0.2s_ease-out] my-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100 flex-shrink-0">
            <h3 id="leaveModalTitle" class="text-lg font-semibold text-gray-900">Add New Leave</h3>
            <button type="button" onclick="closeAddModal()"
                    class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form (scrollable body) --}}
        <form id="leaveForm" method="POST" action="{{ route('leaves.store') }}" class="p-5 space-y-4 overflow-y-auto flex-1">
            @csrf
            <input type="hidden" name="_method" id="leaveMethod" value="PUT" disabled>
            <input type="hidden" name="_leave_id" id="edit_leave_id" value="{{ old('_leave_id') }}">

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

            {{-- Employee Balances Overview --}}
            <div id="employeeBalancesOverview" class="hidden p-3 rounded-xl border border-gray-100 bg-gray-50/70 space-y-2">
                <div class="flex items-center justify-between text-xs text-gray-500 font-medium">
                    <span>Available Leave Balances:</span>
                    <span id="overviewLoading" class="hidden text-indigo-600 animate-pulse text-[11px]">Loading balances...</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    {{-- Annual --}}
                    <button type="button" onclick="selectLeaveType('Annual')"
                            class="leave-type-card bg-white rounded-lg p-2.5 border border-gray-200/70 text-center hover:border-indigo-400 hover:shadow-xs transition group">
                        <div class="text-[11px] font-semibold text-gray-500 group-hover:text-indigo-600 uppercase tracking-wider">Annual</div>
                        <div class="text-sm font-bold text-gray-900 mt-0.5">
                            <span id="availAnnual">0</span>
                            <span class="text-[10px] font-normal text-gray-400">/<span id="maxAnnual">0</span>d</span>
                        </div>
                    </button>

                    {{-- Medical --}}
                    <button type="button" onclick="selectLeaveType('Medical')"
                            class="leave-type-card bg-white rounded-lg p-2.5 border border-gray-200/70 text-center hover:border-indigo-400 hover:shadow-xs transition group">
                        <div class="text-[11px] font-semibold text-gray-500 group-hover:text-indigo-600 uppercase tracking-wider">Medical</div>
                        <div class="text-sm font-bold text-gray-900 mt-0.5">
                            <span id="availMedical">0</span>
                            <span class="text-[10px] font-normal text-gray-400">/<span id="maxMedical">0</span>d</span>
                        </div>
                    </button>

                    {{-- Casual --}}
                    <button type="button" onclick="selectLeaveType('casual')"
                            class="leave-type-card bg-white rounded-lg p-2.5 border border-gray-200/70 text-center hover:border-indigo-400 hover:shadow-xs transition group">
                        <div class="text-[11px] font-semibold text-gray-500 group-hover:text-indigo-600 uppercase tracking-wider">Casual</div>
                        <div class="text-sm font-bold text-gray-900 mt-0.5">
                            <span id="availCasual">0</span>
                            <span class="text-[10px] font-normal text-gray-400">/<span id="maxCasual">0</span>d</span>
                        </div>
                    </button>
                </div>
                <p class="text-[10px] text-gray-400 text-center">Click a card above to quickly select that leave type</p>
            </div>

            {{-- Leave Type --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="leaveType" class="block text-sm font-medium text-gray-700">
                        Leave Type <span class="text-red-500">*</span>
                    </label>
                    <span id="leaveQuotaBadge" class="hidden text-xs px-2 py-0.5 rounded-full font-medium bg-gray-100 text-gray-600">
                        Allowed: <span id="leaveQuotaCount">0</span> days/yr
                    </span>
                </div>
                <select id="leaveType" name="leave_type" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <option value="">Select Type</option>
                    <option value="Annual" @selected(old('leave_type') == 'Annual')>
                        Annual ({{ $leaveCounts['Annual'] ?? 0 }} days)
                    </option>
                    <option value="Medical" @selected(old('leave_type') == 'Medical')>
                        Medical ({{ $leaveCounts['Medical'] ?? 0 }} days)
                    </option>
                    <option value="casual" @selected(old('leave_type') == 'casual')>
                        Casual ({{ $leaveCounts['casual'] ?? 0 }} days)
                    </option>
                </select>
                @error('leave_type')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror

                {{-- Live Balance Feedback --}}
                <div id="leaveBalanceCard" class="hidden mt-2 p-2.5 rounded-lg text-xs border border-indigo-100 bg-indigo-50/60 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Allowance: <strong id="balanceMaxDays" class="text-gray-900">0</strong>d</span>
                        <span class="text-gray-600">Used: <strong id="balanceUsedDays" class="text-gray-900">0</strong>d</span>
                        <span class="text-indigo-700 font-semibold">Remaining: <span id="balanceRemainingDays">0</span>d</span>
                    </div>
                </div>
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
                        End Date
                    </label>
                    <input type="date" id="leaveEndDate" name="end_date"
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
        </form>

        {{-- Sticky Footer (form එකෙන් එළියේ, ඒත් form එකට සම්බන්ධයි) --}}
        <div class="flex items-center justify-end gap-2 p-5 border-t border-gray-100 flex-shrink-0 bg-white rounded-b-2xl">
            <button type="button" onclick="closeAddModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition">
                Cancel
            </button>
            <button type="submit" form="leaveForm" id="leaveSubmit"
                    class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg shadow-sm hover:bg-gray-800 hover:shadow-md active:scale-[0.98] transition-all duration-200">
                Add Leave
            </button>
        </div>
    </div>
</div>
