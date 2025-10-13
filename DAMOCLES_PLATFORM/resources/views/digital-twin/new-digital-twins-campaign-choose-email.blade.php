<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twins campaign -->
            <a href="{{ route('digital-twins.defineAttachPrompt') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('digital-twins.index') }}">@lang('digital-twin.digitalTwinsCampaigns')</a></li>
                <li>/</li>
                <li><a href="{{ route('digital-twins.new') }}">@lang('digital-twin.new')</a></li>
                <li>/</li>
                <li><a href="{{ route('digital-twins.chooseEmail') }}">@lang('general.selectEmails')</a></li>
            </ul>
        </div>
    </x-slot>
    @php
        $submitRoute = route('digital-twins.simulation');
    @endphp

    @include('layouts.partials.emails-selection', [
        'Title' => __('general.selectEmailsExplanation'),
        'Finish' => __('general.next'),
        'submitRoute' => $submitRoute,
        'validatedData' => $validatedData,
        'emails' => $emails,
        'total' => 7,
        'active' => 4,
    ])

    <x-loading-screen />
</x-app-layout>

<!-- Error email modal -->
<x-modal name="error-email-modal" id="error-email-modal" title="Error user!" :show="false">
    <div class="p-4 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('general.chooseAnEmail')</p>
        <x-primary-button x-on:click="$dispatch('close')">@lang('general.close')</x-primary-button>
    </div>
</x-modal>

<script src="{{ asset('js/tabulationTable.js') }}"></script>
<script>
    // Tabulation
    document.addEventListener('DOMContentLoaded', function() {
        setupPagination(
            'email-table', // Table ID
            'Total emails' // Personalized text for the total label
        );
    });

    // Filter
    function setupTableFilter(emailFilterInput, clearFilterButton, rows) {
        emailFilterInput.addEventListener("input", function() {
            const emailFilterValue = this.value.toLowerCase().trim();

            clearFilterButton.style.display = this.value.trim() !== "" ? "block" : "none";

            rows.forEach(function(row) {
                const rowData = Array.from(row.cells).map(cell => cell.textContent.toLowerCase());
                const matchesFilter = rowData.some(data => data.includes(emailFilterValue));
                row.style.display = matchesFilter ? "" : "none";
            });
        });

        clearFilterButton.addEventListener("click", function() {
            emailFilterInput.value = "";
            clearFilterButton.style.display = "none";

            rows.forEach(function(row) {
                row.style.display = "";
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const emailFilterInput = document.getElementById("emailFilter");
        const clearFilterButton = document.getElementById("clear-email-filter");
        const rows = document.querySelectorAll("#email-table tbody tr");

        setupTableFilter(emailFilterInput, clearFilterButton, rows);
    });

    document.getElementById('saveEmailsForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const selectedUserCheckboxes = document.querySelectorAll('.email-checkbox:checked');
        if (selectedUserCheckboxes.length === 0) {
            const modalEvent = new CustomEvent('open-modal', {
                detail: 'error-email-modal'
            });
            window.dispatchEvent(modalEvent);
            return;
        }

        // Loading screen
        document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

        const selectedEmailsIds = Array.from(selectedUserCheckboxes).map(checkbox => checkbox.dataset.emailId);
        document.getElementById('emailsIdsInput').value = selectedEmailsIds;
        document.getElementById('saveEmailsForm').submit();
    });
</script>
