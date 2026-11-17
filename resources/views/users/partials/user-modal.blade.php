<div id="userModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 animate-[fadeIn_0.2s_ease-out]">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100">
            <div>
                <h2 id="userModalTitle" class="text-lg font-bold text-gray-900">Add New User</h2>
                <p id="userModalDescription" class="text-xs text-gray-500 mt-0.5">Assign an employee to a login account with username and password.</p>
            </div>
            <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="userForm" action="{{ route('users.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="userMethod" value="PUT" disabled>
            <input type="hidden" name="_user_id" id="modalUserId" value="{{ old('_user_id') }}">
            <input type="hidden" name="_is_admin" id="modalIsAdmin" value="{{ old('_is_admin', '0') }}">

            <div class="space-y-4">
                {{-- Employee Dropdown (for employees) --}}
                <div id="employeeSelectContainer">
                    <label for="userName" class="block text-sm font-medium text-gray-700 mb-1">
                        Select Employee (ID & Name) <span class="text-red-500">*</span>
                    </label>
                    <select name="name" id="userName" required
                            class="w-full px-3 py-2 text-sm border @error('name') border-red-500 ring-1 ring-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white">
                        <option value="">— Select Employee —</option>
                        @if(isset($employees))
                            @foreach($employees as $emp)
                                <option value="{{ $emp->first_name }} {{ $emp->last_name }}"
                                        data-eid="{{ $emp->employee_id }}"
                                        data-firstname="{{ $emp->first_name }}"
                                        data-lastname="{{ $emp->last_name }}"
                                        @disabled(in_array($emp->employee_id, $assignedEmployeeIds ?? [], true) && old('employee_id') !== $emp->employee_id)
                                        {{ old('employee_id') === $emp->employee_id || old('name') === ($emp->first_name . ' ' . $emp->last_name) ? 'selected' : '' }}>
                                    {{ $emp->employee_id }} - {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <input type="hidden" name="employee_id" id="employeeId" value="{{ old('employee_id') }}">
                    @error('employee_id')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Read-only account identity shown while editing --}}
                <div id="accountIdentityContainer" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Account
                    </label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <span id="accountNameDisplay" class="block text-sm font-semibold text-gray-900 truncate"></span>
                            <span id="accountEmployeeIdDisplay" class="block text-xs text-gray-500"></span>
                        </div>
                        <span id="accountRoleDisplay" class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide"></span>
                    </div>
                </div>
                <input type="hidden" name="name" id="editUserName" disabled>

                {{-- Username --}}
                <div>
                    <label for="userEmail" class="block text-sm font-medium text-gray-700 mb-1">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="email" id="userEmail" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 text-sm border @error('email') border-red-500 ring-1 ring-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                           placeholder="e.g. john.doe or EMP001">
                    <p class="mt-1 text-xs text-gray-500">The username (or email) used to log in.</p>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Current Password (when editing the signed-in admin account) --}}
                <div id="currentPasswordContainer" class="hidden">
                    <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">
                        Current Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="current_password" id="currentPassword"
                           autocomplete="current-password" disabled
                           class="w-full px-3 py-2 text-sm border @error('current_password') border-red-500 ring-1 ring-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                           placeholder="Enter your current password">
                    @error('current_password')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Confirm your current password before setting a new one.</p>
                </div>

                {{-- Password --}}
                <div>
                    <label for="userPassword" class="block text-sm font-medium text-gray-700 mb-1">
                        <span id="passwordLabel">Password</span> <span id="passwordRequiredStar" class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="userPassword" minlength="8" required
                           autocomplete="new-password"
                           class="w-full px-3 py-2 text-sm border @error('password') border-red-500 ring-1 ring-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                           placeholder="Minimum 8 characters">
                    <p id="passwordHelp" class="mt-1 text-xs text-gray-500">Must be at least 8 characters long.</p>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" id="userSubmit"
                        class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 rounded-lg shadow-sm transition">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
