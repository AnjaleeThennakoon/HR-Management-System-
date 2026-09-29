const designationForm = document.getElementById('designationForm');

if (designationForm) {
    const designationStoreAction = designationForm.action;
    const designationModal = document.getElementById('designationModal');
    const designationMethod = document.getElementById('designationMethod');
    const designationName = document.getElementById('designationName');
    const designationUpperLevel = document.getElementById('designationUpperLevel');
    const designationModalTitle = document.getElementById('designationModalTitle');
    const designationSubmit = document.getElementById('designationSubmit');

    window.openAddModal = function () {
        designationForm.action = designationStoreAction;
        designationMethod.disabled = true;
        designationName.value = '';
        designationUpperLevel.value = '';
        designationModalTitle.textContent = 'Add New Designation';
        designationSubmit.textContent = 'Add Designation';
        designationModal.classList.remove('hidden');
        designationModal.setAttribute('aria-hidden', 'false');
        designationName.focus();
    };

    window.closeAddModal = function () {
        designationModal.classList.add('hidden');
        designationModal.setAttribute('aria-hidden', 'true');
    };

    window.openEditModal = function (id, name, upperLevel) {
        designationForm.action = '/designations/' + id;
        designationMethod.disabled = false;
        designationName.value = name;
        designationUpperLevel.value = upperLevel ?? '';
        designationModalTitle.textContent = 'Edit Designation';
        designationSubmit.textContent = 'Update Designation';
        designationModal.classList.remove('hidden');
        designationModal.setAttribute('aria-hidden', 'false');
        designationName.focus();
    };

    window.filterDesignations = function () {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.desig-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.querySelector('.desig-name').textContent.toLowerCase();
            const upperLevel = row.querySelector('.desig-upper').textContent.toLowerCase();
            const matches = name.includes(query) || upperLevel.includes(query);
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
