<div id="attendanceModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 w-full max-w-md animate-[fadeIn_0.2s_ease-out]">

        {{-- Header --}}
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h3 id="attendanceModalTitle" class="text-lg font-semibold text-gray-900">
                {{ $errors->any() && old('_method') === 'PUT' ? 'Edit Attendance' : 'Add New Attendance' }}
            </h3>
            <button type="button" onclick="closeAddModal()"
                    class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="attendanceForm" method="POST"
              action="{{ $errors->any() && old('_edit_action') ? old('_edit_action') : route('attendance.store') }}"
              class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="attendanceMethod" value="PUT"
                   @if(!($errors->any() && old('_method') === 'PUT')) disabled @endif>

            {{-- 👇 Hidden field එකක් — edit කරද්දී URL එක මතක තියාගන්න --}}
            <input type="hidden" name="_edit_action" id="attendanceEditAction"
                   value="{{ old('_edit_action') }}">

            {{-- Global error message --}}
            @if($errors->any() && !$errors->has('employee_id') && !$errors->has('date') && !$errors->has('in_time') && !$errors->has('out_time'))
                <div class="flex items-start gap-2 p-3 bg-red-50 text-red-700 text-sm rounded-lg ring-1 ring-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Employee --}}
            <div>
                <label for="attendanceEmployee" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Employee <span class="text-red-500">*</span>
                </label>
                <select id="attendanceEmployee" name="employee_id" required
                        class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                               @error('employee_id') border-red-300 bg-red-50/30 @else border-gray-200 @enderror">
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                            {{ $employee->first_name }} {{ $employee->last_name }} (ID: {{ $employee->id }})
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Date --}}
            <div>
                <label for="attendanceDate" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Date <span class="text-red-500">*</span>
                </label>
                <input type="date" id="attendanceDate" name="date" required
                       value="{{ old('date') }}"
                       class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                              @error('date') border-red-300 bg-red-50/30 @else border-gray-200 @enderror">
                @error('date')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- In Time + Out Time --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="attendanceInTime" class="block text-sm font-medium text-gray-700 mb-1.5">
                        In Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" id="attendanceInTime" name="in_time" required
                           value="{{ old('in_time') }}"
                           class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                                  @error('in_time') border-red-300 bg-red-50/30 @else border-gray-200 @enderror">
                    @error('in_time')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="attendanceOutTime" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Out Time
                    </label>
                    <input type="time" id="attendanceOutTime" name="out_time"
                           value="{{ old('out_time') }}"
                           class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                                  @error('out_time') border-red-300 bg-red-50/30 @else border-gray-200 @enderror">
                    @error('out_time')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" id="attendanceSubmit"
                        class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg shadow-sm hover:bg-gray-800 hover:shadow-md active:scale-[0.98] transition-all duration-200">
                    {{ $errors->any() && old('_method') === 'PUT' ? 'Update Attendance' : 'Add Attendance' }}
                </button>
            </div>
        </form>
    </div>
</div>
