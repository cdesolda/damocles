document.addEventListener('DOMContentLoaded', function () {
    /**
     * Set up pagination for a table
     * @param {string} tableId          - The ID of the table element
     * @param {string} totalLabelPrefix - The prefix for the total count label (e.g., "Total users", "Total campaigns")
     */
    function setupPagination(tableId, totalLabelPrefix) {
        const rowsPerPageSelect = document.getElementById('rowsPerPage');
        const pageIndicator = document.getElementById('pageIndicator');
        const totalItemsLabel = document.getElementById('totalRows');

        // Se l'elemento non esiste, interrompe l'esecuzione della funzione
        if (!rowsPerPageSelect || !pageIndicator || !totalItemsLabel) {
            return;
        }

        let rowsPerPage = parseInt(rowsPerPageSelect.value);
        let currentPage = 1;
        const table = document.getElementById(tableId).getElementsByTagName('tbody')[0];
        const totalRows = table.getElementsByTagName('tr').length;
        let totalPages = Math.ceil(totalRows / rowsPerPage);

        if (totalRows <= rowsPerPage) {
            pageIndicator.style.display = 'none';
            rowsPerPageSelect.style.display = 'none';
        }

        function updateTable() {
            for (let i = 0; i < totalRows; i++) {
                table.rows[i].style.display = (i >= (currentPage - 1) * rowsPerPage && i < currentPage * rowsPerPage) ? '' : 'none';
            }
            totalPages = Math.ceil(totalRows / rowsPerPage);
            pageIndicator.innerText = `${currentPage} / ${totalPages}`;
            totalItemsLabel.innerText = `${totalLabelPrefix}: ${totalRows}`;
            document.getElementById('prevPage').classList.toggle('hidden', currentPage === 1);
            document.getElementById('nextPage').classList.toggle('hidden', currentPage === totalPages);
        }

        document.getElementById('prevPage').addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });

        document.getElementById('nextPage').addEventListener('click', function () {
            if (currentPage < totalPages) {
                currentPage++;
                updateTable();
            }
        });

        rowsPerPageSelect.addEventListener('change', function () {
            rowsPerPage = parseInt(this.value);
            currentPage = 1;
            updateTable();
        });

        // Initialize table display
        updateTable();
    }

    // Export globally for usage
    window.setupPagination = setupPagination;
});
