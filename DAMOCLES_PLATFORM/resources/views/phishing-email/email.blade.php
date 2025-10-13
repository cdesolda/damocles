<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('emails.index') }}">@lang('email.emails')</a></li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8 text-sky-900">

                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 sm:justify-between items-center mb-6">
                    <p class="font-semibold text-xl w-full">@lang('email.emails')</p>

                    <div class="flex flex-col md:flex-row w-full justify-end items-center gap-4">
                        <div>
                            @if ($emails->count() > 0)
                                <!-- Filter -->
                                <div class="flex flex-row w-80 gap-3 justify-end items-center relative">
                                    <label for="filter"
                                        class="block text-sm font-bold text-sky-700">@lang('general.search')</label>
                                    <input type="text" id="filter" name="filter"
                                        class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                                        placeholder="Enter email subject">
                                    <button id="clear-filter"
                                        class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 0a10 10 0 0 1 7.071 2.929A10 10 0 0 1 20 10a10 10 0 0 1-2.929 7.071A10 10 0 0 1 10 20a10 10 0 0 1-7.071-2.929A10 10 0 0 1 0 10a10 10 0 0 1 2.929-7.071A10 10 0 0 1 10 0zm3.536 5.05a.5.5 0 0 1 .708.708L10.707 10l3.536 3.536a.5.5 0 0 1-.708.708L10 10.707l-3.536 3.536a.5.5 0 1 1-.708-.708L9.293 10 5.757 6.464a.5.5 0 0 1 .708-.708L10 9.293l3.536-3.536z" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Email group -->
                        <div>
                            <a href="{{ route('email-groups.index') }}" class="cursor-pointer">
                                <x-primary-button>
                                    @lang('email.groups.showEmailGroups')
                                </x-primary-button>
                            </a>
                        </div>

                        <!-- Add new email -->
                        <div>
                            <a href="{{ route('emails.new') }}" class="cursor-pointer">
                                <x-primary-button>
                                    @lang('email.new')
                                </x-primary-button>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                @if ($emails->count() > 0)
                    <table id="emails-table" class="min-w-full text-left">
                        <thead class="bg-gray-100">
                            <tr class="border-b-2 border-gray-300">
                                <th class="py-2 px-4">
                                    <button id="delete-selected-btn" style="display: none;" data-id=""
                                        x-data=""
                                        @click="$dispatch('open-modal', 'delete-emails-modal', { id: $el.getAttribute('data-id') })">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-5" fill="none"
                                            viewBox="0 0 32 32" stroke="red" stroke-width="1.0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M28 6 L6 6 8 30 24 30 26 6 4 6 M16 12 L16 24 M21 12 L20 24 M11 12 L12 24 M12 6 L13 2 19 2 20 6" />
                                        </svg>
                                    </button>
                                </th>
                                <th class="py-2 px-4">@lang('email.subject')</th>
                                <th class="py-2 px-4">@lang('general.updatedAt')</th>
                                <th class="py-2 px-4"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($emails as $email)
                                <tr class="hover:bg-gray-100 border-b border-gray-200 email-row">
                                    <td class="py-2 px-4">
                                        <input id="{{ $email->id }}" type="checkbox" class="email-checkbox"
                                            data-email-id="{{ $email->id }}">
                                    </td>
                                    <td class="w-2/4 py-2 px-4 cursor-pointer"
                                        onclick="handleRowClick(event, '{{ route('emails.edit', ['email' => $email->id]) }}')">
                                        {{ Str::limit($email->subject, 100) }}
                                    </td>
                                    <td class="w-1/4 justify-end py-2 px-4 cursor-pointer"
                                        onclick="handleRowClick(event, '{{ route('emails.edit', ['email' => $email->id]) }}')">
                                        {{ $email->updated_at->format('d/m/Y') }}
                                    </td>
                                    <td class="flex flex-row justify-end py-2 px-4">
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button class="inline-flex items-center font-semibold inner-element">
                                                    <p class="classic">
                                                        @lang('general.options')
                                                        <span>
                                                            <svg class="fill-current h-4 w-4"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </p>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                <div class="flex flex-col gap-2">
                                                    <a href="{{ route('emails.edit', ['email' => $email->id]) }}"
                                                        class="hover:bg-gray-100 inner-element py-1">
                                                        <button
                                                            class="hover:bg-gray-100 inner-element py-1 w-full text-cente">@lang('general.edit')</button>
                                                    </a>

                                                    <button class="hover:bg-gray-100 inner-element py-1"
                                                        data-id="{{ $email->id }}" x-data=""
                                                        @click="$dispatch('open-modal', 'duplicate-modal', { id: {{ $email->id }} })">@lang('general.duplicate')</button>

                                                </div>
                                            </x-slot>
                                        </x-dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Paginator controls -->
                    @include('layouts.partials.pagination-controls')
                @else
                    <p class="pt-4 text-center text-lg text-sky-700">
                        @lang('email.noEmail')</p>
                @endif
            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<!-- Delete modal -->
<x-modal name="delete-emails-modal" id="delete-emails-modal" title="Delete Emails" :show="false">
    <div class="p-4 rounded-lg relative">
        @include('phishing-email.modals.delete-emails')
    </div>
</x-modal>

<!-- Duplicate modal -->
<x-modal name="duplicate-modal" id="duplicate-modal" title="Duplicate Email" :show="false">
    <div class="p-4 rounded-lg relative">
        @include('phishing-email.modals.duplicate-email')
    </div>
</x-modal>

<!-- Delete successfully message -->
<x-modal name="delete-successfully-modal" id="delete-successfully-modal" title="Delete successfully modal"
    :show="false">
    <div class="p-6 rounded-lg relative text-center text-sky-800">
        <p id="modal-title-message" class="text-xl font-semibold pb-8"><!--  message  dynamically inserted  --></p>
        <p id="modal-message" class="text-lg text-sky-600 pb-4"><!-- Specific detailed message  --></p>
        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Duplicate successfully message -->
<x-modal name="duplicate-successfully-modal" id="duplicate-successfully-modal" title="Duplicate successfully modal"
    :show="false">
    <div class="p-4 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">
            @lang('email.duplicateSucc')
        </p>

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Update successfully message -->
<x-modal name="update-successfully-modal" id="update-successfully-modal" title="Update successfully modal"
    :show="false">
    <div class="p-6 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">
            @lang('email.updateSucc')
        </p>

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<script src="{{ asset('js/tabulationTable.js') }}"></script>
<script>
    // Tabulation
    document.addEventListener('DOMContentLoaded', function() {
        setupPagination(
            'emails-table', // Table ID
            'Total emails' // Personalized text for the total label
        );
    });

    // Modal options
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            @php
                $successMessage = session('success');
            @endphp
            if ("{{ $successMessage }}" === "Email duplicated successfully!") {
                const duplicateModalEvent = new CustomEvent('open-modal', {
                    detail: 'duplicate-successfully-modal',
                });
                window.dispatchEvent(duplicateModalEvent);
            }

            if ("{{ $successMessage }}" === "Emails updated successfully!") {
                const updateModalEvent = new CustomEvent('open-modal', {
                    detail: 'update-successfully-modal'
                });
                window.dispatchEvent(updateModalEvent);
            }
        @endif

        @if (session('delete-success') || session('delete-warning') || session('delete-error'))
            @php
                $successMessage = session('delete-success') ?? (session('delete-warning') ?? session('delete-error'));
            @endphp
            const modalMessageElement = document.getElementById('modal-message');
            const modalTitleMessageElement = document.getElementById('modal-title-message');

            if (modalMessageElement && modalTitleMessageElement) {

                // Set the specific detailed message based on session type
                @if (session('delete-success'))
                    modalTitleMessageElement.textContent = @json(__('email.deleteMessages.success'));
                    modalTitleMessageElement.classList.remove('hidden');
                    modalTitleMessageElement.classList.add('text-green-600');
                    modalMessageElement.textContent = `{{ $successMessage }}`;
                @elseif (session('delete-warning'))
                    modalTitleMessageElement.textContent = @json(__('email.deleteMessages.partial'));
                    modalTitleMessageElement.classList.remove('hidden');
                    modalTitleMessageElement.classList.add('text-yellow-600');
                    const undeletedEmails = @json(session('delete-warning'));
                    const messageTemplate = @json(__('email.deleteMessages.explanation.partial'));
                    const emails = undeletedEmails.join('<br>');
                    const message = messageTemplate.replace('{emails}', emails);

                    modalMessageElement.innerHTML = message;
                @elseif (session('delete-error'))
                    modalTitleMessageElement.textContent = @json(__('email.deleteMessages.notDeleted'));
                    modalTitleMessageElement.classList.remove('hidden');
                    modalTitleMessageElement.classList.add('text-red-600');
                    modalMessageElement.textContent = `{{ $successMessage }}`;
                @endif
            }

            const deleteModalEvent = new CustomEvent('open-modal', {
                detail: 'delete-successfully-modal'
            });
            window.dispatchEvent(deleteModalEvent);
        @endif

        @if ($errors->any())
            const errorModalEvent = new CustomEvent('open-modal', {
                detail: 'error-modal'
            });
            window.dispatchEvent(errorModalEvent);
        @endif
    });

    document.addEventListener('DOMContentLoaded', function() {
        const filterInput = document.getElementById("filter");
        const clearFilterButton = document.getElementById("clear-filter");
        const rows = document.querySelectorAll("#emails-table tbody tr");

        if (filterInput && clearFilterButton) {
            filterInput.addEventListener("input", function() {
                const filterValue = this.value.toLowerCase().trim();
                clearFilterButton.style.display = this.value.trim() !== "" ? "block" : "none";

                rows.forEach(function(row) {
                    const subjectCell = row.querySelector("td:nth-child(2)");

                    const subjectText = subjectCell.textContent.toLowerCase().trim();

                    if (subjectText.includes(filterValue)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });

            clearFilterButton.addEventListener("click", function() {

                filterInput.value = "";
                clearFilterButton.style.display = "none";

                rows.forEach(function(row) {
                    row.style.display = "";
                });
            });
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        const deleteButton = document.getElementById('delete-selected-btn');

        function toggleDeleteButton() {
            const selectedEmails = document.querySelectorAll('.email-checkbox:checked');
            const selectedEmailsIds = Array.from(selectedEmails).map(checkbox => checkbox.dataset.emailId);

            deleteButton.style.display = selectedEmails.length > 0 ? 'block' : 'none';

            deleteButton.setAttribute('data-id', selectedEmailsIds.join(','));
        }

        document.querySelectorAll('.email-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', toggleDeleteButton);
        });

    });


    function handleRowClick(event, url) {
        if (!event.target.closest('.inner-element')) {
            location.href = url;
        }
    }
</script>
