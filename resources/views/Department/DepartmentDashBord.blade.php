<x-layout title="Departments - HR System">
    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-black/5 p-8 max-w-4xl w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6 pb-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Departments</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Manage your organization's departments</p>
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
                <input type="text" id="searchInput" onkeyup="filterDepartments()" placeholder="Search departments..."
                       class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
            </div>

            <button onclick="openAddModal()"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-gray-800 hover:shadow-md active:scale-[0.98] transition-all duration-200 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Department
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-xl ring-1 ring-gray-100">
            <table class="w-full text-sm">
                <thead>
                <tr class="bg-gray-50 text-left text-gray-500 text-xs uppercase tracking-wide">
                    <th class="py-3 px-4 font-semibold">#</th>
                    <th class="py-3 px-4 font-semibold">Department Name</th>
                    <th class="py-3 px-4 font-semibold text-right">Actions</th>
                </tr>
                </thead>
                <tbody id="departmentsTable" class="divide-y divide-gray-100">
                @forelse($departments as $dept)
                    <tr class="dept-row hover:bg-gray-50/80 transition-colors">
                        <td class="py-3.5 px-4 text-gray-400 font-medium">{{ $dept['id'] }}</td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 font-semibold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($dept['name'], 0, 2)) }}
                                </div>
                                <span class="dept-name text-gray-800 font-medium">{{ $dept['name'] }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                {{-- Edit Button --}}
                                <button onclick="openEditModal({{ $dept['id'] }}, '{{ $dept['name'] }}')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-indigo-600 hover:bg-indigo-50 rounded-md text-xs font-semibold transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                    Edit
                                </button>

                                {{-- Delete Form --}}
                                <form action="{{ route('departments.destroy', $dept['id']) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this department?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-red-600 hover:bg-red-50 rounded-md text-xs font-semibold transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75" />
                                </svg>
                                <p class="text-sm font-medium">No departments yet</p>
                                <p class="text-xs">Click "Add Department" to create your first one.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <p id="noResults" class="hidden text-center text-sm text-gray-400 py-8">
            No departments match your search.
        </p>

    </div>

    {{-- ==================== ADD MODAL ==================== --}}

    @include('Department.partials.add-modal')
    @include('Department.partials.edit-modal')

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id, name) {
            document.getElementById('editName').value = name;
            document.getElementById('editForm').action = '/departments/' + id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function filterDepartments() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.dept-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.querySelector('.dept-name').textContent.toLowerCase();
                const match = name.includes(query);
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

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.96) translateY(4px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>

</x-layout>
