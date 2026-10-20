<x-layout title="Employees - HR System">
    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-black/5 p-8 max-w-6xl w-full">

        <x-page-header title="Employees" description="Manage your organization's employees">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
            </x-slot:icon>
        </x-page-header>
        <x-alert.success />



        <x-page-toolbar filter-function="filterEmployees()" placeholder="Search employees..."
                        add-label="Add Employee" />

        {{-- Table --}}
        <div class="overflow-x-auto rounded-xl ring-1 ring-gray-100">
            <table class="w-full text-sm">
                <thead>
                <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
                    <th class="py-3 px-4 font-semibold">#</th>
                    <th class="py-3 px-4 font-semibold whitespace-nowrap">Employee ID</th>
                    <th class="py-3 px-4 font-semibold">Name</th>
                    <th class="py-3 px-4 font-semibold">Department</th>
                    <th class="py-3 px-4 font-semibold">Designation</th>
                    <th class="py-3 px-4 font-semibold whitespace-nowrap">Phone</th>
                    <th class="py-3 px-4 font-semibold whitespace-nowrap">Date of Birth</th>
                    <th class="py-3 px-4 font-semibold">NIC</th>
                    <th class="py-3 px-4 font-semibold">Gender</th>
                    <th class="py-3 px-4 font-semibold">Salary</th>
                    <th class="py-3 px-4 font-semibold">Shift in </th>
                    <th class="py-3 px-4 font-semibold">Shift out </th>
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
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-semibold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}
                                </div>
                                <span
                                    class="emp-name text-gray-800 font-medium">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span
                                class="emp-department inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $emp->department->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span
                                class="emp-designation inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                {{ $emp->designation->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-gray-600">{{ $emp->phone }}</td>
                        <td class="py-3.5 px-4 text-gray-600">{{ $emp->date_of_birth ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-gray-600">{{ $emp->nic ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-gray-600">{{ $emp->gender ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-gray-600">{{ $emp->salary ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-gray-600">{{ $emp->shift_in ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-gray-600">{{ $emp->shift_out ?? '-' }}</td>

                        <td class="py-3.5 px-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                <x-Form.editbutton
                                    :arguments="[$emp->id, $emp, $emp->department_id, $emp->designation_id]"/>
                                <x-Form.deletebutton
                                    :action="route('employees.destroy', $emp->id)"
                                    confirmation-message="Are you sure you want to delete this employee?"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
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

    @include('components.partials.add-modal')
    @include('components.partials.edit-modal')


</x-layout>
