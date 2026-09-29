<x-layout title="Designations - HR System">
    <div class="w-full max-w-7xl rounded-2xl bg-white p-5 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200 sm:p-7 lg:p-8">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20.25 14.15v4.073a2.25 2.25 0 01-1.35 2.07A48.108 48.108 0 0112 21.75a48.108 48.108 0 01-6.9-.457 2.25 2.25 0 01-1.35-2.07V14.15M18.75 6.75a2.25 2.25 0 00-2.25-2.25H7.5A2.25 2.25 0 005.25 6.75v9.75a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25V6.75zM12 12.75a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Designations</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage your organization's designations</p>
                    <p class="mt-2 text-xs font-medium text-slate-400">{{ $designations->total() }} designations</p>
                </div>
            </div>

            <a href="/dashboard"
               class="inline-flex w-fit items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-slate-500 transition hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div role="status" class="mb-5 flex items-center gap-2 rounded-xl bg-emerald-50 p-3 text-sm text-emerald-800 ring-1 ring-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
            <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                <p class="font-semibold">Please correct the following errors and try again:</p>
                <ul class="mt-2 list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Toolbar: Search + Add Button --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-sm">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="search" id="searchInput" oninput="filterDesignations()" placeholder="Search designations..."
                       aria-label="Search designations"
                       class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
            </div>

            <button onclick="openAddModal()"
                    type="button" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-indigo-200 sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Designation
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-xl ring-1 ring-slate-200">
            <table class="w-full min-w-[52rem] text-left text-sm">
                <thead>
                <tr class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <th class="px-4 py-3.5 font-semibold">#</th>
                    <th class="px-4 py-3.5 font-semibold">Designation</th>
                    <th class="px-4 py-3.5 font-semibold">Reports to</th>
                    <th class="px-4 py-3.5 font-semibold">Level</th>
                    <th class="px-4 py-3.5 text-right font-semibold">Actions</th>
                </tr>
                </thead>
                <tbody id="designationsTable" class="divide-y divide-gray-100">
                @forelse($designations as $designation)
                    <tr class="desig-row transition-colors hover:bg-slate-50/80">
                        <td class="px-4 py-4 font-medium text-slate-400">{{ $designation->id }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-xs font-bold text-indigo-700">
                                    {{ strtoupper(substr($designation->name, 0, 2)) }}
                                </div>
                                <span class="desig-name font-semibold text-slate-800">{{ $designation->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="desig-upper inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                {{ $designation->upperLevel->name ?? '-' }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            <span class="desig-level inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700">
                                L{{ $designation->level }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                <x-Form.editbutton :designation="$designation" />
                                <x-Form.deletebutton :designation="$designation" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M20.25 14.15v4.073a2.25 2.25 0 01-1.35 2.07A48.108 48.108 0 0112 21.75a48.108 48.108 0 01-6.9-.457 2.25 2.25 0 01-1.35-2.07V14.15M18.75 6.75a2.25 2.25 0 00-2.25-2.25H7.5A2.25 2.25 0 005.25 6.75v9.75a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25V6.75zM12 12.75a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"/>
                                </svg>
                                <p class="text-sm font-medium">No designations yet</p>
                                <p class="text-xs">Click "Add Designation" to create your first one.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">{{ $designations->links() }}</div>

        <p id="noResults" role="status" class="hidden py-8 text-center text-sm text-slate-500">
            No designations match your search.
        </p>

    </div>

    @include('Designation.partials.designation-modal')

    <script>
        function openAddModal() {
            const form = document.getElementById('designationForm');
            form.action = '{{ route('designations.store') }}';
            document.getElementById('designationMethod').disabled = true
            document.getElementById('designationName').value = '';
            document.getElementById('designationUpperLevel').value = '';
            document.getElementById('designationModalTitle').textContent = 'Add New Designation';
            document.getElementById('designationSubmit').textContent = 'Add Designation';
            const modal = document.getElementById('designationModal');
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
            document.getElementById('designationName').focus();
        }

        function closeAddModal() {
            const modal = document.getElementById('designationModal');
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        }

        function openEditModal(id, name, upperLevel) {
            const form = document.getElementById('designationForm');
            form.action = '/designations/' + id;
            document.getElementById('designationMethod').disabled = false;
            document.getElementById('designationName').value = name;
            document.getElementById('designationUpperLevel').value = upperLevel ?? '';
            document.getElementById('designationModalTitle').textContent = 'Edit Designation';
            document.getElementById('designationSubmit').textContent = 'Update Designation';
            const modal = document.getElementById('designationModal');
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
            document.getElementById('designationName').focus();
        }

        function filterDesignations() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.desig-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.querySelector('.desig-name').textContent.toLowerCase();
                const upper = row.querySelector('.desig-upper').textContent.toLowerCase();
                const match = name.includes(query) || upper.includes(query);
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            document.getElementById('noResults').classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAddModal();
            }
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(4px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
    </style>

</x-layout>
