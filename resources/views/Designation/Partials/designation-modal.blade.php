<div id="designationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 animate-[fadeIn_0.2s_ease-out]">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100">
            <h2 id="designationModalTitle" class="text-lg font-bold text-gray-900">Add New Designation</h2>
            <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="designationForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="designationMethod" value="PUT" disabled>

            <div class="space-y-4">
                {{-- Name --}}
                <div>
                    <label for="designationName" class="block text-sm font-medium text-gray-700 mb-1">
                        Designation Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="designationName" required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                           placeholder="e.g. Software Engineer">
                </div>

                {{-- Upper Level --}}
                <div>
                    <label for="designationUpperLevel" class="block text-sm font-medium text-gray-700 mb-1">
                        Upper Level
                    </label>
                    <select name="upper_level" id="designationUpperLevel"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white">
                        <option value="">— None (Top Level) —</option>
                        @foreach($designations as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" id="designationSubmit"
                        class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 rounded-lg shadow-sm transition">
                    Add Designation
                </button>
            </div>
        </form>
    </div>
</div>
