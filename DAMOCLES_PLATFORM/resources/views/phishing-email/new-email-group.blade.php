<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to Emails -->
            <a href="{{ route('email-groups.index') }}" class="cursor-pointer">
                <x-primary-button>
                    Back
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('email-groups.index') }}">@lang('email.groups.emailGroups')</a></li>
                <li>/</li>
                <li><a href="{{ route('email-groups.new') }}">@lang('email.groups.new')</a></li>
            </ul>
        </div>
    </x-slot>

    <div class="pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white sm:overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">
                <div class="space-y-4">
                    <p class="font-semibold text-xl">@lang('email.groups.creation')</p>
                    <p class="font-semibold text-sm">@lang('general.mandatoryField')</p>
                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('email.groups.name')
                        </p>
                        <x-input-label for="name" class="text-md block font-medium text-sky-700 pb-2"
                            :value="__('email.groups.newEmailGroup.value.name')" />
                        <input type="text" id="name" name="name"
                            class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full sm:w-2/3"
                            required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $submitRoute = route('email-groups.save');
    @endphp
    <div class="mt-[-3rem]">
        @include('layouts.partials.emails-selection', [
            'Title' => __('general.selectEmails'),
            'Finish' => __('email.groups.create'),
            'submitRoute' => $submitRoute,
            'validatedData' => null,
            'emails' => $emails,
            'total' => 0,
            'active' => 0,
        ])
    </div>
    <x-loading-screen />
</x-app-layout>

<!-- Error modal -->
<!-- Error data modal -->
<x-modal name="error-data-modal" id="error-data-modal" title="Error data" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('general.errorData')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
    </div>
</x-modal>

<!-- Error email modal -->
<x-modal name="error-email-modal" id="error-email-modal" title="Error user!" :show="false">
    <div class="p-4 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('general.chooseMoreEmails')</p>
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


    document.addEventListener('DOMContentLoaded', function() {
        const saveEmailsForm = document.getElementById('saveEmailsForm');

        const nameInput = document.getElementById('name');

        saveEmailsForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const nameInputValue = nameInput.value.trim();
            const selectedUserCheckboxes = document.querySelectorAll('.email-checkbox:checked');

            if (nameInputValue === '') {
                showErrorModal();
                return;
            }
            if (selectedUserCheckboxes.length < 2) {
                const modalEvent = new CustomEvent('open-modal', {
                    detail: 'error-email-modal'
                });
                window.dispatchEvent(modalEvent);
                return;
            }

            const validatedDataInput = document.querySelector('input[name="validatedData"]');
            validatedDataInput.value = JSON.stringify({
                name: nameInputValue
            });

            const selectedEmailsIds = Array.from(selectedUserCheckboxes).map(checkbox => checkbox
                .dataset.emailId);

            document.getElementById('emailsIdsInput').value = selectedEmailsIds;

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

            this.submit();
        });

        function showErrorModal() {
            const errorModal = new CustomEvent('open-modal', {
                detail: 'error-data-modal'
            });
            window.dispatchEvent(errorModal);
        }

    });
</script>
