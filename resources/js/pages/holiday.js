document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('holidayForm');
    if (!form) {
        // Not on the holidays page, nothing to wire up.
        return;
    }

    const methodInput = document.getElementById('holidayMethod');
    const nameInput = document.getElementById('holidayName');
    const dateInput = document.getElementById('holidayDate');
    const typeInput = document.getElementById('holidayType');
    const modal = document.getElementById('holidayModal');
    const modalTitle = document.getElementById('holidayModalTitle');
    const submitBtn = document.getElementById('holidaySubmit');
    const searchInput = document.getElementById('searchInput');
    const noResults = document.getElementById('noResults');

    window.openAddModal = function () {
        form.reset();
        form.action = form.dataset.storeAction;
        methodInput.disabled = true;
        modalTitle.textContent = 'Add New Holiday';
        submitBtn.textContent = 'Add Holiday';
        modal.classList.remove('hidden');
    };

    window.closeAddModal = function () {
        modal.classList.add('hidden');
    };

    window.openEditModal = function (id, name, date, type) {
        form.action = form.dataset.updateBase + '/' + id;
        methodInput.disabled = false;
        nameInput.value = name;
        dateInput.value = date;
        typeInput.value = type;
        modalTitle.textContent = 'Edit Holiday';
        submitBtn.textContent = 'Update Holiday';
        modal.classList.remove('hidden');
    };

    window.filterHolidays = function () {
        const query = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('.holiday-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.querySelector('.holiday-name').textContent.toLowerCase();
            const match = name.includes(query);
            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        if (noResults) {
            noResults.classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
        }
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            window.closeAddModal();
        }
    });
});
