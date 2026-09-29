const employeeAddModal = document.getElementById('addModal');

if (employeeAddModal) {
    const employeeEditModal = document.getElementById('editModal');

    window.openAddModal = function () {
        employeeAddModal.classList.remove('hidden');
    };

    window.closeAddModal = function () {
        employeeAddModal.classList.add('hidden');
    };

    window.openEditModal = function (id, employee, departmentId, designationId) {
        document.getElementById('editForm').action = '/employees/' + id;
        document.getElementById('edit_employee_id').value = employee.employee_id;
        document.getElementById('edit_first_name').value = employee.first_name;
        document.getElementById('edit_last_name').value = employee.last_name;
        document.getElementById('edit_date_of_birth').value = employee.date_of_birth;
        document.getElementById('edit_gender').value = employee.gender;
        document.getElementById('edit_nic').value = employee.nic;
        document.getElementById('edit_phone').value = employee.phone;
        document.getElementById('edit_address').value = employee.address;
        document.getElementById('edit_department_id').value = departmentId;
        document.getElementById('edit_designation_id').value = designationId;
        employeeEditModal.classList.remove('hidden');
    };

    window.closeEditModal = function () {
        employeeEditModal.classList.add('hidden');
    };

    window.filterEmployees = function () {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.emp-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.querySelector('.emp-name').textContent.toLowerCase();
            const department = row.querySelector('.emp-department').textContent.toLowerCase();
            const designation = row.querySelector('.emp-designation').textContent.toLowerCase();
            const matches = name.includes(query) || department.includes(query) || designation.includes(query);
            row.style.display = matches ? '' : 'none';
            if (matches) {
                visibleCount++;
            }
        });

        document.getElementById('noResults').classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
    };

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            window.closeAddModal();
            window.closeEditModal();
        }
    });
}
