<x-layout title="Departments - HR System">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl w-full">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Departments</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your departments</p>
            </div>

            <a href="/dashboard" class="text-sm text-gray-600 hover:text-gray-900">
                ← Back to Dashboard
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 text-sm rounded-md">
                {{ session('success') }}
            </div>
        @endif

        {{-- Add Button --}}
        <div class="mb-6">
            <button onclick="openAddModal()"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500 transition">
                + Add Department
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                <tr class="border-b border-gray-200 text-left text-gray-600">
                    <th class="py-2 px-4">#</th>
                    <th class="py-2 px-4">Department Name</th>
                    <th class="py-2 px-4 text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($departments as $dept)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-500">{{ $dept['id'] }}</td>
                        <td class="py-3 px-4 text-gray-800 font-medium">{{ $dept['name'] }}</td>
                        <td class="py-3 px-4 text-right space-x-2">

                            {{-- Edit Button --}}
                            <button onclick="openEditModal({{ $dept['id'] }}, '{{ $dept['name'] }}')"
                                    class="text-indigo-600 hover:underline text-xs font-medium">
                                Edit
                            </button>

                            {{-- Delete Form --}}
                            <form action="{{ route('departments.destroy', $dept['id']) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this department?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-xs font-medium">
                                    Delete
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- ==================== ADD MODAL ==================== --}}
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">

            <h2 class="text-lg font-bold text-gray-800 mb-4">Add New Department</h2>

            <form action="{{ route('departments.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                    <input type="text" name="name" required
                           placeholder="e.g. Human Resources"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 h-10 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-500 transition">
                        Add Department
                    </button>
                    <button type="button" onclick="closeAddModal()"
                            class="flex-1 h-10 bg-gray-200 text-gray-700 font-semibold rounded-md hover:bg-gray-300 transition">
                        Cancel
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ==================== EDIT MODAL ==================== --}}
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">

            <h2 class="text-lg font-bold text-gray-800 mb-4">Edit Department</h2>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                    <input type="text" name="name" id="editName" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                            class="flex-1 h-10 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-500 transition">
                        Update
                    </button>
                    <button type="button" onclick="closeEditModal()"
                            class="flex-1 h-10 bg-gray-200 text-gray-700 font-semibold rounded-md hover:bg-gray-300 transition">
                        Cancel
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- JavaScript for Modals --}}
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
    </script>

</x-layout>
