@include('Employee.partials.add-modal')
@include('Employee.partials.edit-modal')

<script>
    function openEditModal(id, employee, departmentId, designationId) {
        const form = document.getElementById('editForm');
        form.action = `{{ url('/employees') }}/${id}`;

        const fields = [
            'employee_id',
            'phone',
            'first_name',
            'last_name',
            'date_of_birth',
            'gender',
            'nic',
            'address',
            'salary',
            'shift_in',
            'shift_out',
        ];

        fields.forEach((field) => {
            document.getElementById(`edit_${field}`).value = employee[field] ?? '';
        });

        document.getElementById('edit_department_id').value = departmentId ?? '';
        document.getElementById('edit_designation_id').value = designationId ?? '';
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
