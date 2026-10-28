<div id="leaveModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 w-full max-w-md max-h-[90vh] flex flex-col animate-[fadeIn_0.2s_ease-out] my-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100 flex-shrink-0">
            <h3 id="leaveModalTitle" class="text-lg font-semibold text-gray-900">Add New Leave</h3>
            <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="leaveForm" method="POST" action="{{ route('leaves.store') }}" class="p-5 space-y-4 overflow-y-auto flex-1">
            @csrf
            <input type="hidden" name="_method" id="leaveMethod" value="PUT" disabled>
            <input type="hidden" name="_leave_id" id="edit_leave_id" value="{{ old('_leave_id') }}">

            {{-- Approval Information (Approved At & Approved User) --}}
            <div id="leaveApprovalSection" class="p-3.5 bg-gray-50/80 rounded-xl border border-gray-100 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Approval Details</span>
                    <span id="leaveStatusBadge" class="text-xs px-2.5 py-0.5 rounded-full font-medium bg-yellow-50 text-yellow-700">Pending</span>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div>
                        <label for="leaveApprovedAt" class="block text-xs font-medium text-gray-500 mb-1">Approved At</label>
                        <input type="text" id="leaveApprovedAt" readonly value="-" placeholder="Not yet approved"
                               class="w-full px-2.5 py-1.5 text-xs border border-gray-200 bg-white rounded-lg text-gray-700 font-medium cursor-default">
                    </div>
                    <div>
                        <label for="leaveApprovedUser" class="block text-xs font-medium text-gray-500 mb-1">Approved User</label>
                        <input type="text" id="leaveApprovedUser" readonly value="-" placeholder="-"
                               class="w-full px-2.5 py-1.5 text-xs border border-gray-200 bg-white rounded-lg text-gray-700 font-medium cursor-default">
                    </div>
                </div>
            </div>

            {{-- Employee --}}
            <div>
                <label for="leaveEmployee" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Employee <span class="text-red-500">*</span>
                </label>
                <select id="leaveEmployee" name="employee_id" required
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                            {{ $employee->first_name }} {{ $employee->last_name }} (ID: {{ $employee->id }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Balances Overview --}}
            <div id="employeeBalancesOverview" class="hidden p-3 rounded-xl border border-gray-100 bg-gray-50/70 space-y-2">
                <div class="flex items-center justify-between text-xs text-gray-500 font-medium">
                    <span>Available Balances:</span>
                    <span id="overviewLoading" class="hidden text-indigo-600 animate-pulse text-[11px]">Loading...</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="selectLeaveType('Annual')" class="bg-white rounded-lg p-2.5 border border-gray-200 text-center hover:border-indigo-400 transition">
                        <div class="text-[11px] font-semibold text-gray-500 uppercase">Annual</div>
                        <div class="text-sm font-bold mt-0.5"><span id="availAnnual">0</span>/<span id="maxAnnual">0</span>d</div>
                    </button>
                    <button type="button" onclick="selectLeaveType('Medical')" class="bg-white rounded-lg p-2.5 border border-gray-200 text-center hover:border-indigo-400 transition">
                        <div class="text-[11px] font-semibold text-gray-500 uppercase">Medical</div>
                        <div class="text-sm font-bold mt-0.5"><span id="availMedical">0</span>/<span id="maxMedical">0</span>d</div>
                    </button>
                    <button type="button" onclick="selectLeaveType('casual')" class="bg-white rounded-lg p-2.5 border border-gray-200 text-center hover:border-indigo-400 transition">
                        <div class="text-[11px] font-semibold text-gray-500 uppercase">Casual</div>
                        <div class="text-sm font-bold mt-0.5"><span id="availCasual">0</span>/<span id="maxCasual">0</span>d</div>
                    </button>
                </div>
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
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">Select Type</option>
                    <option value="Annual" @selected(old('leave_type') == 'Annual')>Annual ({{ $leaveCounts['Annual'] ?? 0 }} days)</option>
                    <option value="Medical" @selected(old('leave_type') == 'Medical')>Medical ({{ $leaveCounts['Medical'] ?? 0 }} days)</option>
                    <option value="casual" @selected(old('leave_type') == 'casual')>Casual ({{ $leaveCounts['casual'] ?? 0 }} days)</option>
                </select>
                @error('leave_type')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror

                {{-- Live Balance Feedback --}}
                <div id="leaveBalanceCard" class="hidden mt-2 p-2.5 rounded-lg text-xs border border-indigo-100 bg-indigo-50/60 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Max: <strong id="balanceMaxDays" class="text-gray-900">0</strong>d</span>
                        <span class="text-gray-600">Used: <strong id="balanceUsedDays" class="text-gray-900">0</strong>d</span>
                        <span class="text-indigo-700 font-semibold">Remaining: <span id="balanceRemainingDays">0</span>d</span>
                    </div>
                </div>
            </div>

            {{-- Dates & Leave Count --}}
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="leaveStartDate" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="leaveStartDate" name="start_date" required value="{{ old('start_date') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-indigo-500 transition">
                        @error('start_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="leaveEndDate" class="block text-sm font-medium text-gray-700 mb-1.5">End Date</label>
                        <input type="date" id="leaveEndDate" name="end_date" value="{{ old('end_date') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-indigo-500 transition">
                        @error('end_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Leave Count update display --}}
                <div class="bg-gray-50/70 rounded-xl p-3 border border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="block text-xs font-medium text-gray-500">Leave Count</span>
                        <span id="leaveDaysCountText" class="text-sm font-semibold text-gray-800">1 day</span>
                    </div>
                    <input type="hidden" id="leaveDaysCount" value="1">
                    <span id="leaveDaysCountBadge" class="text-xs px-2.5 py-1 rounded-full font-semibold bg-indigo-50 text-indigo-700">
                        Duration: <span id="leaveDaysCountDisplay">1 day</span>
                    </span>
                </div>
            </div>

            {{-- Reason --}}
            <div>
                <label for="leaveReason" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Reason <span class="text-red-500">*</span>
                </label>
                <textarea id="leaveReason" name="reason" rows="3" required placeholder="Enter reason for leave..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-indigo-500 transition">{{ old('reason') }}</textarea>
                @error('reason')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </form>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-2 p-5 border-t border-gray-100 bg-white rounded-b-2xl flex-shrink-0">
            <button type="button" onclick="closeAddModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition">
                Cancel
            </button>
            <button type="submit" form="leaveForm" id="leaveSubmit"
                    class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-800 active:scale-[0.98] transition-all">
                Add Leave
            </button>
        </div>
    </div>
</div>
