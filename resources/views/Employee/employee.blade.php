<x-layout title="Employees - HR System">
    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-black/5 p-8 max-w-6xl w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6 pb-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Employees</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Manage your organization's employees</p>
                </div>
            </div>

            <a href="/dashboard" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-5 flex items-center gap-2 p-3 bg-green-50 text-green-700 text-sm rounded-lg ring-1 ring-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif



        {{-- Toolbar: Search + Add Button --}}
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="relative flex-1 max-w-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" id="searchInput" onkeyup="filterEmployees()" placeholder="Search employees..."
                       class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            </div>

            <button onclick="openAddModal()"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-gray-800 hover:shadow-md active:scale-[0.98] transition-all duration-200 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Employee
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-xl ring-1 ring-gray-100">
            <table class="w-full text-sm">
                <thead>
                <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
                    <th class="py-3 px-4 font-semibold">#</th>
                    <th class="py-3 px-4 font-semibold">Employee ID</th>
                    <th class="py-3 px-4 font-semibold">Name</th>
                    <th class="py-3 px-4 font-semibold">Department</th>
                    <th class="py-3 px-4 font-semibold">Designation</th>
                    <th class="py-3 px-4 font-semibold">Phone</th>
                    <th class="py-3 px-4 font-semibold text-right">Actions</th>
                </tr>
                </thead>
                <tbody id="employeesTable" class="divide-y divide-gray-100">
                @forelse($employees as $emp)
                    <tr class="emp-row hover:bg-gray-50/80 transition-colors">
                        <td class="py-3.5 px-4 text-gray-400 font-medium">{{ $emp->id }}</td>
                        <td class="py-3.5 px-4 text-gray-600">{{ $emp->employee_id }}</td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-semibold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}
                                </div>
                                <span class="emp-name text-gray-800 font-medium">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="emp-department inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $emp->department->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="emp-designation inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                {{ $emp->designation->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-gray-600">{{ $emp->phone }}</td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                <x-Form.editbutton :emp="$emp" />
                                <x-Form.deletebutton :emp="$emp" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <p class="text-sm font-medium">No employees yet</p>
                                <p class="text-xs">Click "Add Employee" to create your first one.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <p id="noResults" class="hidden text-center text-sm text-gray-400 py-8">
            No employees match your search.
        </p>

    </div>

    @include('Components.partials.add-modal')
    @include('Components.partials.edit-modal')

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id, emp, departmentId, designationId) {
            document.getElementById('editForm').action = '/employees/' + id;
            document.getElementById('edit_employee_id').value = emp.employee_id;
            document.getElementById('edit_first_name').value = emp.first_name;
            document.getElementById('edit_last_name').value = emp.last_name;
            document.getElementById('edit_date_of_birth').value = emp.date_of_birth;
            document.getElementById('edit_gender').value = emp.gender;
            document.getElementById('edit_nic').value = emp.nic;
            document.getElementById('edit_phone').value = emp.phone;
            document.getElementById('edit_address').value = emp.address;
            document.getElementById('edit_department_id').value = departmentId;
            document.getElementById('edit_designation_id').value = designationId;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function filterEmployees() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.emp-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.querySelector('.emp-name').textContent.toLowerCase();
                const dept = row.querySelector('.emp-department').textContent.toLowerCase();
                const desig = row.querySelector('.emp-designation').textContent.toLowerCase();
                const match = name.includes(query) || dept.includes(query) || desig.includes(query);
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            document.getElementById('noResults').classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }

        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeEditModal();
            }
        });
    </script>

</x-layout>
