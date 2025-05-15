document.addEventListener('DOMContentLoaded', function () {
    const rowsPerPage = 5;
    const table = document.querySelector('table tbody');
    const rows = Array.from(table.querySelectorAll('tr'));
    const totalPages = Math.ceil(rows.length / rowsPerPage);

    const paginationWrapper = document.createElement('div');
    paginationWrapper.classList.add('d-flex', 'justify-content-center', 'mt-3');
    paginationWrapper.id = 'pagination-controls';

    function renderTablePage(page) {
        table.innerHTML = '';
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        rows.slice(start, end).forEach(row => table.appendChild(row));

        renderPaginationControls(page);
    }

    function renderPaginationControls(currentPage) {
        paginationWrapper.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.className = `btn btn-sm mx-1 ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}`;
            btn.textContent = i;
            btn.addEventListener('click', () => renderTablePage(i));
            paginationWrapper.appendChild(btn);
        }
    }

    // Initial render
    renderTablePage(1);

    // Add pagination to DOM after the table
    const tableContainer = document.querySelector('.table-responsive');
    tableContainer.parentNode.appendChild(paginationWrapper);
});
