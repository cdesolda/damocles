// Live Search Functionality
const filterInput = document.getElementById('filter');
const clearFilterButton = document.getElementById("clear-filter");

const tableRows = document.querySelectorAll('#questionnaire-summary tbody tr');
const noResultsMessage = document.getElementById('no-results');

function resetSearch() {
    filterInput.value = "";
    clearFilterButton.style.display = "none";
    filterInput.dispatchEvent(new Event('input'));
}

if (filterInput && clearFilterButton) {
    filterInput.addEventListener('input', function () {
        const filterValue = filterInput.value.toLowerCase();

        clearFilterButton.style.display = this.value.trim() !== "" ? "block" : "none";

        let matchesFound = false;

        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const userText = Array.from(cells).slice(0, 1).map(cell => cell.textContent
                .toLowerCase()).join(' ');

            if (userText.includes(filterValue)) {
                row.style.display = '';
                matchesFound = true;
            } else {
                row.style.display = 'none';
            }
        });

        noResultsMessage.style.visibility = matchesFound ? 'hidden' : 'visible';
        noResultsMessage.style.display = matchesFound ? 'none' : 'table-row';
    });

    // Clear Filter Button Functionality
    clearFilterButton.addEventListener("click", resetSearch);

}

//Filter Questionnaires
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.questionnaire-button');

    buttons.forEach(button => {
        const questionnaireId = button.getAttribute('data-questionnaire-id');
        const chart = document.querySelector(`#chart-${questionnaireId}`);
        const tableColumns = document.querySelectorAll(`.questionnaire-${questionnaireId}`);

        // Initially, buttons should be blue and charts/columns should be visible
        button.classList.add('bg-sky-800', 'text-white');
        chart.style.display = 'block';
        tableColumns.forEach(col => col.style.display = 'table-cell');

        button.addEventListener('click', function () {
            toggleSelection(this, questionnaireId);
            toggleVisibility(chart, tableColumns);
        });
    });

    // Toggle the button's color
    function toggleSelection(button, questionnaireId) {
        if (button.classList.contains('bg-sky-800')) {
            button.classList.remove('bg-sky-800', 'text-white');
            button.classList.add('bg-white', 'text-sky-800');
        } else {
            button.classList.remove('bg-white', 'text-sky-800');
            button.classList.add('bg-sky-800', 'text-white');
        }
    }

    // Toggle visibility of the chart and table columns
    function toggleVisibility(chart, tableColumns) {
        if (chart.style.display === 'none') {
            chart.style.display = 'block';
            tableColumns.forEach(col => col.style.display = 'table-cell');
        } else {
            chart.style.display = 'none';
            tableColumns.forEach(col => col.style.display = 'none');
        }
    }
});

// Toggle the dropdown for scores
document.querySelectorAll('.toggle-scales').forEach(button => {
    button.addEventListener('click', function () {
        const questionnaireId = button.getAttribute('data-id');
        const scalesList = document.getElementById(`scales-list-${questionnaireId}`);

        scalesList.classList.toggle('hidden');

        const isHidden = scalesList.classList.contains('hidden');
        button.innerHTML = isHidden ? '&#9660;' : '&#9650;';
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll(".tab-btn");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach((tab, index) => {
        tab.addEventListener("click", () => {
            // Remove active styles from all tabs
            tabs.forEach(t => t.classList.remove("active-tab", "text-sky-700",
                "border-sky-700"));
            // Hide all contents
            contents.forEach(content => content.classList.add("hidden"));

            // Add active styles to the clicked tab
            tab.classList.add("active-tab", "text-sky-700", "border-sky-700");
            // Show the corresponding content
            contents[index].classList.remove("hidden");
        });
    });
});