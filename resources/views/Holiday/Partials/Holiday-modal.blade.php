<div id="holidayModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full animate-[fadeIn_0.15s_ease-out]">

        <div class="flex items-center justify-between mb-5">
            <h2 id="holidayModalTitle" class="text-lg font-bold text-gray-900">Add New Holiday</h2>
            <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="holidayForm" action="{{ route('holidays.store') }}" method="POST"
              data-store-action="{{ route('holidays.store') }}" data-update-base="{{ url('/holidays') }}">
            @csrf
            <input type="hidden" name="_method" id="holidayMethod" value="PUT" disabled>

            <div class="mb-4">
                <label for="holidayName" class="block text-sm font-medium text-gray-700 mb-1.5">Holiday Name</label>
                <input type="text" name="name" id="holidayName" value="{{ old('name') }}" required
                       placeholder="e.g. Independence Day"
                       class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="holidayDate" class="block text-sm font-medium text-gray-700 mb-1.5">Date</label>
                <input type="date" name="date" id="holidayDate" value="{{ old('date') }}" required
                       class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                @error('date')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-2">
                <label for="holidayType" class="block text-sm font-medium text-gray-700 mb-1.5">Type</label>
                <select name="type" id="holidayType" required
                        class="w-full border border-gray-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    <option value="">Select type</option>
                    <option value="public" @selected(old('type') == 'public')>Public</option>
                    <option value="special" @selected(old('type') == 'special')>Special</option>
                </select>
                @error('type')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeAddModal()"
                        class="flex-1 h-11 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </button>
                <button id="holidaySubmit" type="submit"
                        class="flex-1 h-11 bg-indigo-600 text-white font-semibold rounded-lg shadow-sm hover:bg-indigo-500 hover:shadow-md active:scale-[0.98] transition-all">
                    Add Holiday
                </button>
            </div>

        </form>
    </div>
</div>
