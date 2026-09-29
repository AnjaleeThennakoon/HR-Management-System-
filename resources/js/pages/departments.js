const departmentForm = document.getElementById('departmentForm');

if (departmentForm) {
    const departmentStoreAction = departmentForm.action;
    const departmentModal = document.getElementById('departmentModal');
    const departmentName = document.getElementById('departmentName');
    const departmentMethod = document.getElementById('departmentMethod');
    const departmentModalTitle = document.getElementById('departmentModalTitle');
    const departmentSubmit = document.getElementById('departmentSubmit');

    window.openAddModal = function () {
        departmentForm.action = departmentStoreAction;
        departmentMethod.disabled = true;
        departmentName.value = '';
        departmentModalTitle.textContent = 'Add New Department';
        departmentSubmit.textContent = 'Add Department';
        departmentModal.classList.remove('hidden');
    };

    window.closeAddModal = function () {
        departmentModal.classList.add('hidden');
    };

    window.openEditModal = function (id, name) {
        departmentForm.action = '/departments/' + id;
        departmentMethod.disabled = false;
        departmentName.value = name;
        departmentModalTitle.textContent = 'Edit Department';
        departmentSubmit.textContent = 'Update Department';
        departmentModal.classList.remove('hidden');
    };

    window.filterDepartments = function () {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.dept-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.querySelector('.dept-name').textContent.toLowerCase();
            const matches = name.includes(query);
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
        }
    });
}
