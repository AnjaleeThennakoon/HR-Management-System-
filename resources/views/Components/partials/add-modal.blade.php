<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 p-8 max-w-lg w-full" style="animation: fadeIn 0.15s ease-out;">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-900">Add Employee</h2>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('employees.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <x-form.field name="employee_id" label="Employee ID" />
                <x-form.field name="phone" label="Phone" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-form.field name="first_name" label="First Name" />
                <x-form.field name="last_name" label="Last Name" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select name="department_id" id="department_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="designation_id" class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                    <select name="designation_id" id="designation_id"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">No designation (optional)</option>
                        @foreach($designations as $designation)
                            <option value="{{ $designation->id }}" @selected(old('designation_id') == $designation->id)>
                                {{ $designation->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('designation_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-form.field name="date_of_birth" label="Date of Birth" type="date" />
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select name="gender" id="gender"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select gender</option>
                        <option value="Male" @selected(old('gender') == 'Male')>Male</option>
                        <option value="Female" @selected(old('gender') == 'Female')>Female</option>
                    </select>
                    @error('gender')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <x-form.field name="nic" label="NIC" />
            <x-form.field name="address" label="Address" />

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeAddModal()"
                        class="inline-flex items-center justify-center rounded-md bg-gray-100 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                    Cancel
                </button>
                <x-form.btn type="submit">Save Employee</x-form.btn>
            </div>
        </form>
    </div>
</div>
