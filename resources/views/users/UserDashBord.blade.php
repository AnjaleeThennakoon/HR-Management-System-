<x-app-layout title="Users - HR System">
    <div class="p-6">
        <div class="bg-white border border-gray-200 rounded-xl p-6">

            <x-page-header title="Users & Employee Accounts" description="Manage login accounts, usernames, and passwords for employees">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </x-slot:icon>
            </x-page-header>

            <x-alert.success />

            {{-- Validation Error Alert --}}
            @if($errors->any())
                <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    <div class="flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Validation failed. Please correct the following errors:
                    </div>
                    <ul class="mt-2 list-inside list-disc pl-2 space-y-1 text-xs text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-page-toolbar filter-function="filterUsers()" placeholder="Search users by name or username..."
                            add-label="Add User" />

            {{-- Table --}}
            <div class="overflow-x-auto rounded-xl ring-1 ring-gray-100">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
                        <th class="py-3 px-4 font-semibold">#</th>
                        <th class="py-3 px-4 font-semibold">Employee Name</th>
                        <th class="py-3 px-4 font-semibold">Username</th>
                        <th class="py-3 px-4 font-semibold">Created Date</th>
                        <th class="py-3 px-4 font-semibold text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="usersTable" class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        @php
                            $isCurrentUser = auth()->id() === $user->id;
                        @endphp
                        <tr class="user-row hover:bg-gray-50/80 transition-colors">
                            <td class="py-3.5 px-4 text-gray-400 font-medium">{{ $user->id }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-semibold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="user-name text-gray-800 font-medium">{{ $user->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-gray-600 user-email font-mono text-xs">
                                {{ $user->email }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-500 text-xs">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <x-Form.editbutton :arguments="[$user->id, $user->name, $user->email, $isCurrentUser, $user->employee_id, false]" />
                                    <x-Form.deletebutton
                                        :action="route('users.destroy', $user->id)"
                                        confirmation-message="Are you sure you want to delete this user account?"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                    <p class="text-sm font-medium">No user accounts yet</p>
                                    <p class="text-xs">Click "Add User" to assign an employee a login account with username and password.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($users, 'links'))
                <div class="mt-5">{{ $users->links() }}</div>
            @endif

            <p id="noResults" class="hidden text-center text-sm text-gray-400 py-8">
                No users match your search.
            </p>

        </div>
    </div>

    @include('users.partials.user-modal')

    <script>
        function openAddModal() {
            const form = document.getElementById('userForm');
            form.reset();
            form.action = "{{ route('users.store') }}";
            document.getElementById('userMethod').disabled = true;

            const tempOpt = document.getElementById('tempUserOption');
            if (tempOpt) {
                tempOpt.remove();
            }

            document.getElementById('modalUserId').value = '';
            document.getElementById('modalIsAdmin').value = '0';

            // Show the employee selector for creating a new account.
            document.getElementById('employeeSelectContainer').classList.remove('hidden');
            document.getElementById('accountIdentityContainer').classList.add('hidden');
            document.getElementById('userName').disabled = false;
            document.getElementById('editUserName').disabled = true;
            document.getElementById('currentPasswordContainer').classList.add('hidden');
            document.getElementById('currentPassword').disabled = true;
            document.getElementById('currentPassword').required = false;

            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('employeeId').value = '';

            document.getElementById('userModalTitle').textContent = 'Add New User';
            document.getElementById('userModalDescription').textContent = 'Assign an employee to a login account with username and password.';
            document.getElementById('passwordLabel').textContent = 'Password';
            document.getElementById('passwordRequiredStar').classList.remove('hidden');

            const pwdInput = document.getElementById('userPassword');
            pwdInput.required = true;
            pwdInput.placeholder = 'Minimum 8 characters';
            document.getElementById('passwordHelp').textContent = 'Must be at least 8 characters long.';

            document.getElementById('userSubmit').textContent = 'Create User';
            document.getElementById('userModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        function openEditModal(id, name, email, isOwnAccount = false, employeeId = null, isAdminAccount = false) {
            const form = document.getElementById('userForm');
            form.action = `{{ url('/users') }}/${id}`;
            document.getElementById('userMethod').disabled = false;

            document.getElementById('modalUserId').value = id;
            document.getElementById('modalIsAdmin').value = isOwnAccount ? '1' : '0';
            document.getElementById('employeeId').value = employeeId ?? '';
            document.getElementById('employeeSelectContainer').classList.add('hidden');
            document.getElementById('accountIdentityContainer').classList.remove('hidden');
            document.getElementById('userName').disabled = true;
            document.getElementById('editUserName').disabled = false;
            document.getElementById('editUserName').value = name;
            document.getElementById('accountNameDisplay').textContent = name;
            document.getElementById('accountEmployeeIdDisplay').textContent = employeeId ? `Employee ID: ${employeeId}` : '';
            document.getElementById('accountRoleDisplay').textContent = isAdminAccount ? 'Admin' : 'Employee';
            document.getElementById('accountRoleDisplay').className = isAdminAccount
                ? 'inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-purple-100 text-purple-800'
                : 'inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-emerald-100 text-emerald-800';

            if (isOwnAccount) {
                document.getElementById('currentPasswordContainer').classList.remove('hidden');
                document.getElementById('currentPassword').disabled = false;
                document.getElementById('currentPassword').required = true;
                document.getElementById('userModalTitle').textContent = 'Edit Your Account';
                document.getElementById('userModalDescription').textContent = 'Enter your current password to confirm account changes.';
            } else {
                document.getElementById('currentPasswordContainer').classList.add('hidden');
                document.getElementById('currentPassword').disabled = true;
                document.getElementById('currentPassword').required = false;
                document.getElementById('userModalTitle').textContent = 'Edit User Account';
                document.getElementById('userModalDescription').textContent = 'Update this account username or set a new password.';
            }

            document.getElementById('userEmail').value = email;

            const pwdInput = document.getElementById('userPassword');
            pwdInput.value = '';
            pwdInput.required = isOwnAccount;
            pwdInput.placeholder = 'Leave blank to keep existing password';
            const currentPasswordInput = document.getElementById('currentPassword');
            currentPasswordInput.value = '';

            document.getElementById('passwordLabel').textContent = 'New Password (Optional)';
            document.getElementById('passwordRequiredStar').classList.add('hidden');
            document.getElementById('passwordHelp').textContent = 'Leave empty if you do not want to change the password.';
            if (isOwnAccount) {
                document.getElementById('passwordLabel').textContent = 'New Password';
                document.getElementById('passwordRequiredStar').classList.remove('hidden');
                document.getElementById('passwordHelp').textContent = 'Enter a new password of at least 8 characters.';
                pwdInput.placeholder = 'Enter your new password';
            }

            document.getElementById('userSubmit').textContent = 'Update User';

            document.getElementById('userModal').classList.remove('hidden');
        }

        // Auto-suggest username when employee is selected in add mode
        document.getElementById('userName').addEventListener('change', function () {
        const selectedOpt = this.options[this.selectedIndex];
        document.getElementById('employeeId').value = selectedOpt?.getAttribute('data-eid') ?? '';
        const isAddMode = document.getElementById('userMethod').disabled;
        const emailInput = document.getElementById('userEmail');
        if (isAddMode && !emailInput.value) {
            const eid = selectedOpt ? selectedOpt.getAttribute('data-eid') : null;
                if (eid) {
                    emailInput.value = eid.toLowerCase();
                }
            }
        });

        // Filter table rows by name or username
        function filterUsers() {
            const query = document.getElementById('searchInput').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.user-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.querySelector('.user-name').textContent.toLowerCase();
                const email = row.querySelector('.user-email').textContent.toLowerCase();
                const match = name.includes(query) || email.includes(query);

                row.style.display = match ? '' : 'none';
                if (match) {
                    visibleCount++;
                }
            });

            const noResults = document.getElementById('noResults');
            if (rows.length > 0) {
                noResults.classList.toggle('hidden', visibleCount > 0);
            }
        }

        // Automatically keep modal open and restore state if there are validation errors
        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('userModal');
            if (modal) {
                modal.classList.remove('hidden');

                @if(old('_method') === 'PUT' && old('_user_id'))
                    const id = "{{ old('_user_id') }}";
                    const isAdmin = "{{ old('_is_admin') }}" === '1';
                    document.getElementById('userForm').action = `{{ url('/users') }}/${id}`;
                    document.getElementById('userMethod').disabled = false;
                    document.getElementById('userSubmit').textContent = 'Update User';
                    document.getElementById('employeeSelectContainer').classList.add('hidden');
                    document.getElementById('userName').disabled = true;
                    document.getElementById('accountIdentityContainer').classList.remove('hidden');
                    document.getElementById('editUserName').disabled = false;
                    document.getElementById('editUserName').value = "{{ old('name') }}";
                    document.getElementById('accountNameDisplay').textContent = "{{ old('name') }}";
                    document.getElementById('accountEmployeeIdDisplay').textContent = "{{ old('employee_id') ? 'Employee ID: '.old('employee_id') : '' }}";
                    document.getElementById('employeeId').value = "{{ old('employee_id') }}";

                    if (isAdmin) {
                        document.getElementById('currentPasswordContainer').classList.remove('hidden');
                        document.getElementById('currentPassword').disabled = false;
                        document.getElementById('currentPassword').required = true;
                        document.getElementById('userPassword').required = true;
                        document.getElementById('passwordLabel').textContent = 'New Password';
                        document.getElementById('passwordRequiredStar').classList.remove('hidden');
                        document.getElementById('passwordHelp').textContent = 'Enter a new password of at least 8 characters.';
                        document.getElementById('userPassword').placeholder = 'Enter your new password';
                        document.getElementById('accountRoleDisplay').textContent = 'Admin';
                        document.getElementById('accountRoleDisplay').className = 'inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-purple-100 text-purple-800';
                        document.getElementById('userModalTitle').textContent = 'Edit Your Account';
                        document.getElementById('userModalDescription').textContent = 'Enter your current password to confirm account changes.';
                    } else {
                        document.getElementById('currentPasswordContainer').classList.add('hidden');
                        document.getElementById('currentPassword').disabled = true;
                        document.getElementById('currentPassword').required = false;
                        document.getElementById('accountRoleDisplay').textContent = 'Employee';
                        document.getElementById('accountRoleDisplay').className = 'inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-emerald-100 text-emerald-800';
                        document.getElementById('userPassword').required = false;
                        document.getElementById('passwordLabel').textContent = 'New Password (Optional)';
                        document.getElementById('passwordRequiredStar').classList.add('hidden');
                        document.getElementById('passwordHelp').textContent = 'Leave empty if you do not want to change the password.';
                        document.getElementById('userModalTitle').textContent = 'Edit User Account';
                        document.getElementById('userModalDescription').textContent = 'Update this account username or set a new password.';
                    }
                @endif
            }
        });
        @endif
    </script>
</x-app-layout>
