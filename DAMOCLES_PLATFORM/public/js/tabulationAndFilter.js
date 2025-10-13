function initializeTabulationAndFilter({
    elements,
    elementContainers,
    prevButton,
    nextButton,
    indicator,
    paginationControls,
    filterInput,
    clearFilterButton,
    noResultsMessage,
    nameClass,
    additionalFilters = [],
    customLabel,
}) {
    let currentIndex = 0;
    let filteredElements = [...elements];

    // function toggleNavigationButtons() {
    //     prevButton.style.display =
    //         filteredElements.length > 1 && currentIndex > 0 ? "block" : "none";
    //     nextButton.style.display =
    //         filteredElements.length > 1 &&
    //         currentIndex < filteredElements.length - 1
    //             ? "block"
    //             : "none";
    // }
    function toggleNavigationButtons() {
        const isPrevDisabled = !(filteredElements.length > 1 && currentIndex > 0);
        const isNextDisabled = !(
            filteredElements.length > 1 &&
            currentIndex < filteredElements.length - 1
        );

        prevButton.disabled = isPrevDisabled;
        nextButton.disabled = isNextDisabled;

        prevButton.classList.toggle("disabled", isPrevDisabled);
        nextButton.classList.toggle("disabled", isNextDisabled);

        // Add/Remove opacity and cursor classes when disabled/enabled
        if (isPrevDisabled) {
            prevButton.classList.add("opacity-50", "cursor-not-allowed");
        } else {
            prevButton.classList.remove("opacity-50", "cursor-not-allowed");
        }

        if (isNextDisabled) {
            nextButton.classList.add("opacity-50", "cursor-not-allowed");
        } else {
            nextButton.classList.remove("opacity-50", "cursor-not-allowed");
        }
    }

    function showElement(index) {
        elementContainers.forEach((container, idx) => {
            container.style.display = "none";
        });

        if (filteredElements.length > 0) {
            const elementToShow = filteredElements[index];
            const targetIndex = elements.indexOf(elementToShow);
            if (targetIndex !== -1) {
                elementContainers[targetIndex].style.display = "block";
            }

            indicator.textContent = `${customLabel}: ${index + 1} / ${filteredElements.length
                }`;
            toggleNavigationButtons();
        }
    }

    function resetSearch() {
        filterInput.value = "";
        clearFilterButton.style.display = "none";
        filteredElements = [...elements];
        currentIndex = 0;
        showElement(currentIndex);
        paginationControls.style.display =
            filteredElements.length > 1 ? "flex" : "none";
        noResultsMessage.style.display = "none";
    }

    prevButton.addEventListener("click", function () {
        if (currentIndex > 0) {
            currentIndex--;
            showElement(currentIndex);
        }
    });

    nextButton.addEventListener("click", function () {
        if (currentIndex < filteredElements.length - 1) {
            currentIndex++;
            showElement(currentIndex);
        }
    });

    showElement(currentIndex);

    if (filterInput && clearFilterButton) {
        filterInput.addEventListener("input", function () {
            const filterValue = this.value.toLowerCase().trim();
            clearFilterButton.style.display =
                filterValue !== "" ? "block" : "none";

            filteredElements = elements.filter((_, index) => {
                const container = elementContainers[index];
                const name = container
                    .querySelector(`.${nameClass}`)
                    .textContent.toLowerCase();
                const matchesAdditionalFilters = additionalFilters.every(
                    (filterClass) => {
                        const element = container.querySelector(
                            `.${filterClass}`
                        );
                        return (
                            element &&
                            element.textContent
                                .toLowerCase()
                                .includes(filterValue)
                        );
                    }
                );

                return name.includes(filterValue) || matchesAdditionalFilters;
            });

            // if (filteredElements.length > 0) {
            //     currentIndex = 0;
            //     showElement(currentIndex);
            //     paginationControls.style.display = "flex";
            //     noResultsMessage.style.display = "none";
            // } else {
            //     noResultsMessage.style.display = "block";
            //     paginationControls.style.display = "none";
            //     elementContainers.forEach(
            //         (container) => (container.style.display = "none")
            //     );
            // }
            if (filteredElements.length > 0) {
                currentIndex = 0;
                showElement(currentIndex);
                paginationControls.style.display = "flex";
                noResultsMessage.style.display = "none";
            } else {
                noResultsMessage.style.display = "block";
                paginationControls.style.display = "flex"; // Keep controls visible
                prevButton.disabled = true;
                nextButton.disabled = true;

                // Add the disabled classes for opacity and cursor
                prevButton.classList.add("disabled", "opacity-50", "cursor-not-allowed");
                nextButton.classList.add("disabled", "opacity-50", "cursor-not-allowed");

                elementContainers.forEach(
                    (container) => (container.style.display = "none")
                );
            }

        });

        clearFilterButton.addEventListener("click", resetSearch);
        filterInput.dispatchEvent(new Event("input"));
    }
}
