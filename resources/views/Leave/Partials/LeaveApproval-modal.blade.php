<div id="approvalModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 w-full max-w-md flex flex-col animate-[fadeIn_0.2s_ease-out] my-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Update Leave Approval</h3>
            <button type="button" onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="approvalForm" method="POST" action="{{ route('leave-approval.store') }}" class="p-5 space-y-4 overflow-y-auto flex-1">
            @csrf
            <input type="hidden" name="leave_id" id="approvalLeaveId">
            <input type="hidden" name="employee_id" id="approvalEmployeeId">
            <input type="hidden" name="status" id="approvalStatus">

            {{-- Leave ID --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Leave #</label>
                <input type="text" id="approvalLeaveDisplay" readonly
                       class="w-full px-3 py-2 text-sm border border-gray-100 bg-gray-50 rounded-lg text-gray-600">
            </div>

            {{-- Remarks --}}
            <div>
                <label for="approvalRemarks" class="block text-sm font-medium text-gray-700 mb-1.5">Remarks</label>
                <textarea id="approvalRemarks" name="remarks" rows="4"
                          placeholder="Optional remarks..."
                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"></textarea>
                @error('remarks')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </form>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-2 p-5 border-t border-gray-100 bg-white rounded-b-2xl">
            <button type="button" onclick="closeApprovalModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition">
                Cancel
            </button>
            <button type="button" onclick="submitApproval('rejected')"
                    class="px-4 py-2 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-all duration-200">
                Reject
            </button>
            <button type="button" onclick="submitApproval('approved')"
                    class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 active:scale-[0.98] rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                Approve
            </button>
        </div>
    </div>
</div>

<script>
    function submitApproval(status) {
        document.getElementById('approvalStatus').value = status;
        document.getElementById('approvalForm').submit();
    }
</script>
