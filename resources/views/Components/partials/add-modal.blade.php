<div id="addModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full animate-[fadeIn_0.15s_ease-out]">

        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-gray-900">Add New Department</h2>
            <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('departments.store') }}" method="POST">
            @csrf

            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Department Name</label>
                <input type="text" name="name" required
                       placeholder="e.g. Human Resources"
                       class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            </div>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeAddModal()"
                        class="flex-1 h-11 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 h-11 bg-indigo-600 text-white font-semibold rounded-lg shadow-sm hover:bg-indigo-500 hover:shadow-md active:scale-[0.98] transition-all">
                    Add Department
                </button>
            </div>

        </form>
    </div>
</div>
