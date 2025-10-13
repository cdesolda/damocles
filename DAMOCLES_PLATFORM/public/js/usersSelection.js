// Tabulation
document.addEventListener('DOMContentLoaded', function () {
    setupPagination(
        'user-table', // Table ID
        'Total users' // Personalized text for the total label
    );
});

document.addEventListener('DOMContentLoaded', function () {
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const selectAllButton = document.getElementById('selectAllButton');
    // const selectAllMaleButton = document.getElementById('selectAllMaleButton');
    // const selectAllFemaleButton = document.getElementById('selectAllFemaleButton');
    // const selectAllOtherButton = document.getElementById('selectAllOtherButton');
    const ageFromInput = document.getElementById('ageFrom');
    const ageToInput = document.getElementById('ageTo');

    let allUsersSelected = false;
    let allMalesSelected = false;
    let allFemalesSelected = false;
    let allOthersSelected = false;

    function updateSelectedUsersCount() {
        const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
        document.getElementById('totalSelectedUsers').textContent = selectedCount;
    }

    // Utility function to calculate age
    function calculateAge(dateString) {
        if (!dateString) return NaN; // Return NaN if dateString is empty

        // Convert dd/mm/yyyy to yyyy-mm-dd
        const parts = dateString.split('/');
        const formattedDateString = `${parts[2]}-${parts[1]}-${parts[0]}`;

        const birthDate = new Date(formattedDateString);
        const today = new Date();

        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    // Utility function to toggle button styles
    function toggleButtonStyles(button, isActive) {
        if (isActive) {
            button.classList.remove('bg-white', 'text-sky-700');
            button.classList.add('bg-sky-800', 'text-white');
        } else {
            button.classList.remove('bg-sky-800', 'text-white');
            button.classList.add('bg-white', 'text-sky-700');
        }
    }

    function updateSelectAllButton() {
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const allChecked = Array.from(userCheckboxes).every(checkbox => checkbox.checked);
        selectAllButton.textContent = allChecked ? "Deselect all" : "Select all";
        toggleButtonStyles(selectAllButton, allChecked);
    }

    if (selectAllButton) {
        selectAllButton.addEventListener('click', function () {
            allUsersSelected = !allUsersSelected;
            allMalesSelected = allUsersSelected;
            allFemalesSelected = allUsersSelected;
            allOthersSelected = allUsersSelected;

            userCheckboxes.forEach(function (checkbox) {
                checkbox.checked = allUsersSelected;
            });

            selectAllButton.textContent = allUsersSelected ? "Deselect all" : "Select all";
            toggleButtonStyles(selectAllButton, allUsersSelected);

            toggleButtonStyles(selectAllMaleButton, allMalesSelected);
            toggleButtonStyles(selectAllFemaleButton, allFemalesSelected);
            toggleButtonStyles(selectAllOtherButton, allOthersSelected);

            updateSelectedUsersCount();
        });
    }

    // if (selectAllMaleButton) {
    //     selectAllMaleButton.addEventListener('click', function () {
    //         allMalesSelected = !allMalesSelected;
    //         userCheckboxes.forEach(function (checkbox) {
    //             if (checkbox.dataset.gender === 'Male') {
    //                 checkbox.checked = allMalesSelected;
    //             }
    //         });
    //         toggleButtonStyles(selectAllMaleButton, allMalesSelected);
    //         updateSelectAllButton();
    //         updateSelectedUsersCount();
    //     });
    // }

    // if (selectAllFemaleButton) {
    //     selectAllFemaleButton.addEventListener('click', function () {
    //         allFemalesSelected = !allFemalesSelected;
    //         userCheckboxes.forEach(function (checkbox) {
    //             if (checkbox.dataset.gender === 'Female') {
    //                 checkbox.checked = allFemalesSelected;
    //             }
    //         });
    //         toggleButtonStyles(selectAllFemaleButton, allFemalesSelected);
    //         updateSelectAllButton();
    //         updateSelectedUsersCount();
    //     });
    // }

    // if (selectAllOtherButton) {
    //     selectAllOtherButton.addEventListener('click', function () {
    //         allOthersSelected = !allOthersSelected;
    //         userCheckboxes.forEach(function (checkbox) {
    //             if (checkbox.dataset.gender === 'Other') {
    //                 checkbox.checked = allOthersSelected;
    //             }
    //         });
    //         toggleButtonStyles(selectAllOtherButton, allOthersSelected);
    //         updateSelectAllButton();
    //         updateSelectedUsersCount();
    //     });
    // }

    function updateSelectedUsersByAge() {
        const ageFrom = parseInt(ageFromInput.value, 10);
        const ageTo = parseInt(ageToInput.value, 10);

        userCheckboxes.forEach(function (checkbox) {
            const dob = checkbox.closest('tr').querySelector('td:nth-child(4)').textContent.trim();
            const age = calculateAge(dob);

            checkbox.checked = (!isNaN(ageFrom) && age >= ageFrom) && (isNaN(ageTo) || age <= ageTo);
        });

        updateSelectedUsersCount();
    }

    userCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateSelectAllButton();
            updateSelectedUsersCount();
        });
    });

    ageFromInput.addEventListener('input', updateSelectedUsersByAge);
    ageToInput.addEventListener('input', updateSelectedUsersByAge);
});

// Filter
function setupTableFilter(userFilterInput, clearFilterButton, rows) {
    userFilterInput.addEventListener("input", function () {
        const userFilterValue = this.value.toLowerCase().trim();

        clearFilterButton.style.display = this.value.trim() !== "" ? "block" : "none";

        rows.forEach(function (row) {
            const rowData = Array.from(row.cells).map(cell => cell.textContent.toLowerCase());
            const matchesFilter = rowData.some(data => data.includes(userFilterValue));
            row.style.display = matchesFilter ? "" : "none";
        });
    });

    clearFilterButton.addEventListener("click", function () {
        userFilterInput.value = "";
        clearFilterButton.style.display = "none";

        rows.forEach(function (row) {
            row.style.display = "";
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const userFilterInput = document.getElementById("userFilter");
    const clearFilterButton = document.getElementById("clear-user-filter");
    const rows = document.querySelectorAll("#user-table tbody tr");

    setupTableFilter(userFilterInput, clearFilterButton, rows);
});
