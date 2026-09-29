<div id="addModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 p-8 max-w-lg w-full">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-900">Add Employee</h2>

            <button type="button"
                    onclick="closeAddModal()"
                    class="text-gray-400 hover:text-gray-600 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>

            </button>
        </div>


        <form action="{{ route('employees.store') }}"
              method="POST"
              class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <x-form.field name="employee_id" label="Employee ID" required/>
                <x-form.field name="phone" label="Phone" required/>
            </div>


            <div class="grid grid-cols-2 gap-4">
                <x-form.field name="first_name" label="First Name" required/>
                <x-form.field name="last_name" label="Last Name" required/>
            </div>


            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label for="department_id"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Department
                    </label>

                    <select name="department_id"
                            id="department_id"
                            required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

                        <option value="">Select department</option>

                        @foreach($departments as $department)
                            <option value="{{ $department->id }}"
                                @selected(old('department_id') == $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('department_id')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>


                <div>
                    <label for="designation_id"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Designation
                    </label>

                    <select name="designation_id"
                            id="designation_id"
                            required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

                        <option value="">Select designation</option>

                        @foreach($designations as $designation)
                            <option value="{{ $designation->id }}"
                                @selected(old('designation_id') == $designation->id)>
                                {{ $designation->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('designation_id')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-form.field
                    name="date_of_birth"
                    label="Date of Birth"
                    type="date"
                    required
                />

                <div>
                    <label for="gender"
                           class="block text-sm font-medium text-gray-700 mb-1">
                        Gender
                    </label>

                    <select name="gender"
                            id="gender"
                            required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select gender</option>
                        <option value="Male"
                            @selected(old('gender') == 'Male')>
                            Male
                        </option>

                        <option value="Female"
                            @selected(old('gender') == 'Female')>
                            Female
                        </option>

                    </select>

                    @error('gender')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

            </div>


            <x-form.field
                name="nic"
                label="NIC"
                required
            />

            <x-form.field
                name="address"
                label="Address"
                required
            />


            <div class="flex justify-end gap-2 pt-4">

                <button type="button"
                        onclick="closeAddModal()"
                        class="inline-flex items-center justify-center rounded-md bg-gray-100 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                    Cancel
                </button>

                <x-form.btn
                    type="submit"
                    :class="$errors->any() ? 'bg-red-600 hover:bg-red-700' : ''"
                >
                    {{ $errors->any() ? 'Invalid - Try Again' : 'Save Employee' }}
                </x-form.btn>
                <script>
                    @if($errors->any())
                    document.addEventListener('DOMContentLoaded', function () {
                        const addModal = document.getElementById('addModal');
                        if (addModal) {
                            addModal.classList.remove('hidden');
                        }
                    });
                    @endif

                    function openAddModal() {
                        document.getElementById('addModal').classList.remove('hidden');
                    }

                    function closeAddModal() {
                        document.getElementById('addModal').classList.add('hidden');
                    }
                </script>

            </div>

        </form>

    </div>
</div>
