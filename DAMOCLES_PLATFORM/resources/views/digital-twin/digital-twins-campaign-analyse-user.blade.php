<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twins simuations -->
            <a href="{{ route('digital-twins.executeDigitalTwinCampaign', [
                'digitalTwinsCampaign' => $digitalTwinsCampaign->id,
            ]) }}"
                class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('digital-twins.index') }}">@lang('digital-twin.digitalTwinsCampaigns')</a></li>
                <li>/</li>
                <li><a
                        href="{{ route('digital-twins.executeDigitalTwinCampaign', [
                            'digitalTwinsCampaign' => $digitalTwinsCampaign->id,
                        ]) }}">@lang('digital-twin.executeDigitalTwinCampaign')</a>
                </li>
                <li>/</li>
                <li>@lang('digital-twin.analyseUser')</li>
            </ul>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">
                <div class="flex flex-col w-full">
                    <div class="flex justify-between">
                        <p class="font-semibold text-xl">@lang('digital-twin.analyseUser')</p>
                    </div>
                    @if ($users->count() < 1)
                        <p class="text-lg my-4">@lang('digital-twin.noUserFound')</p>
                    @endif
                    @foreach ($users as $user)
                        <div class="py-4 user-entry" id="user-{{ $user->id }}">
                            <div class="flex flex-col">
                                <div class="flex flex-row gap-1">
                                    <p class="font-bold">@lang('general.user.user'):</p>
                                    <p class="user-name">{{ $user->name }} {{ $user->surname }}</p>
                                </div>
                                <div class="flex flex-row gap-1">
                                    <p class="font-bold">@lang('general.user.gender'):</p>
                                    <p class="user-gender">{{ $user->gender }}</p>
                                </div>
                                <div class="flex flex-row gap-1">
                                    <p class="font-bold">@lang('general.user.email'):</p>
                                    <p class="user-email">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="w-full text-center my-4">
                                <table id="email-table-{{ $user->id }}" class="w-full text-center mb-4">
                                    <thead class="bg-gray-100">
                                        <tr class="border-b-2 border-gray-300">
                                            <th class="py-2 px-4 w-1/3">@lang('email.subject')</th>
                                            <th class="py-2 px-4 w-1/3">@lang('phishing-campaign.analyseCampaign.opened')</th>
                                            <th class="py-2 px-4 w-1/3">@lang('phishing-campaign.analyseCampaign.clicked')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        @foreach ($user->emails as $email)
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 px-4 relative group">
                                                    <div class="group relative">
                                                        {{ $email['email']->subject }}
                                                    </div>
                                                    @if ($email['email']->subject === __('email.noEmailFound.subject'))
                                                        <div
                                                            class="absolute left-1/2 transform -translate-x-1/2 mt-2 w-48 p-2 bg-sky-900 rounded-md text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                            {{ __('email.noEmailFound.body') }}
                                                        </div>
                                                    @else
                                                        <p class="text-blue-500 cursor-pointer"
                                                            onclick="toggleBodyVisibility('bodyDiv{{ $email['email']->id }}', this)">
                                                            Show Body
                                                        </p>
                                                        <div class="text-left bg-gray-200 mt-2 p-2 border border-gray-300 rounded-lg shadow-sm text-xs w-full overflow-y-auto hidden"
                                                            id="bodyDiv{{ $email['email']->id }}"
                                                            style="white-space: pre-line; max-height: 7rem;">
                                                            {{ $email['email']->body }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-4">
                                                    <div
                                                        class="group relative {{ $email['opened'] ? 'text-[#DC3545]' : 'text-[#28A745]' }}">
                                                        {!! $email['opened'] ? 'Yes' : 'No' !!}
                                                    </div>
                                                    <div
                                                        class="mt-2 p-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 text-xs w-full max-h-32 overflow-y-auto shadow-sm">
                                                        {{ $email['opened_explanation'] }}
                                                    </div>
                                                </td>

                                                <td class="py-2 px-4">
                                                    <div
                                                        class="group relative {{ $email['clicked'] ? 'text-[#DC3545]' : 'text-[#28A745]' }}">
                                                        {!! $email['clicked'] ? 'Yes' : 'No' !!}
                                                    </div>
                                                    <div
                                                        class="mt-2 p-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 text-xs w-full max-h-32 overflow-y-auto shadow-sm">
                                                        {{ $email['clicked_explanation'] }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- Internal Paginator controls -->
                                <div id="pagination-controls-email-{{ $user->id }}"
                                    class="pagination-controls flex justify-around items-center mt-4">
                                    <div class="flex justify-center w-1/3">
                                        <x-primary-button
                                            id="prevPage-email-{{ $user->id }}">@lang('general.previous')</x-primary-button>
                                    </div>
                                    <div class="flex flex-row justify-center items-center w-1/3 gap-4">
                                        <span id="pageIndicator-email-{{ $user->id }}"
                                            class="text-gray-700"></span>
                                        <span id="totalRows-email-{{ $user->id }}"
                                            class="text-gray-500 text-sm"></span>
                                        <select id="rowsPerPage-email-{{ $user->id }}"
                                            class="border border-gray-300 rounded-md shadow-sm">
                                            <option value="10" selected>10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                    <div class="flex justify-center w-1/3">
                                        <x-primary-button
                                            id="nextPage-email-{{ $user->id }}">@lang('general.next')</x-primary-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- User -->
            <div id="no-results" class="font-bold py-4 text-center hidden">
                @lang('general.noResult')
            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<!-- Download data csv modal -->
<x-modal name="download-data-csv-modal" id="download-data-csv-modal"
    title="Download data csv Digital Twins Campaig Simulation" :show="false">
    <div class="p-4 rounded-lg relative">
        @include('digital-twin.modals.download-data-csv')
    </div>
</x-modal>


<script>
    // Internal Tabulation
    document.addEventListener('DOMContentLoaded', function() {

        function setupPagination(tableIdPrefix) {
            document.querySelectorAll(`[id^="${tableIdPrefix}"]`).forEach(table => {
                const tableId = table.id;
                const identifier = tableId.replace(tableIdPrefix, '');
                const paginationPrefix = tableIdPrefix.replace('table-', '');

                const rowsPerPageSelect = document.getElementById(
                    `rowsPerPage-${paginationPrefix}${identifier}`);
                const pageIndicator = document.getElementById(
                    `pageIndicator-${paginationPrefix}${identifier}`);
                const totalItemsLabel = document.getElementById(
                    `totalRows-${paginationPrefix}${identifier}`);
                const prevButton = document.getElementById(`prevPage-${paginationPrefix}${identifier}`);
                const nextButton = document.getElementById(`nextPage-${paginationPrefix}${identifier}`);

                let rowsPerPage = parseInt(rowsPerPageSelect.value);
                let currentPage = 1;
                const tbody = table.getElementsByTagName('tbody')[0];
                const totalRows = tbody.getElementsByTagName('tr').length;
                let totalPages = Math.ceil(totalRows / rowsPerPage);

                if (totalRows <= rowsPerPage) {
                    pageIndicator.style.display = 'none';
                    rowsPerPageSelect.style.display = 'none';
                }

                function updateTable() {
                    for (let i = 0; i < totalRows; i++) {
                        tbody.rows[i].style.display = (i >= (currentPage - 1) * rowsPerPage && i <
                            currentPage * rowsPerPage) ? '' : 'none';
                    }
                    totalPages = Math.ceil(totalRows / rowsPerPage);
                    pageIndicator.innerText = `${currentPage} / ${totalPages}`;
                    totalItemsLabel.innerText = `Total results: ${totalRows}`;
                    prevButton.classList.toggle('hidden', currentPage === 1);
                    nextButton.classList.toggle('hidden', currentPage === totalPages);
                }

                prevButton.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        updateTable();
                    }
                });

                nextButton.addEventListener('click', function() {
                    if (currentPage < totalPages) {
                        currentPage++;
                        updateTable();
                    }
                });

                rowsPerPageSelect.addEventListener('change', function() {
                    rowsPerPage = parseInt(this.value);
                    currentPage = 1;
                    updateTable();
                });

                updateTable();
            });
        }

        // Initialize pagination for both user and email tables
        setupPagination('email-table-');
    });

    // Tabulation
    document.addEventListener('DOMContentLoaded', function() {
        initializeTabulationAndFilter({
            elements: @json($users),
            elementContainers: document.querySelectorAll(".user-entry"),
            prevButton: document.getElementById('prevUser'),
            nextButton: document.getElementById('nextUser'),
            indicator: document.getElementById('userIndicator'),
            paginationControls: document.getElementById('pagination-controls'),
            filterInput: document.getElementById("filter"),
            clearFilterButton: document.getElementById("clear-filter"),
            noResultsMessage: document.getElementById('no-results'),
            nameClass: 'user-name',
            additionalFilters: ['user-email'],
            customLabel: "User"
        });
    });

    // Expand the body
    function toggleBodyVisibility(bodyId, clickedText) {
        var bodyDiv = document.getElementById(bodyId);

        if (bodyDiv.classList.contains('hidden')) {
            bodyDiv.classList.remove('hidden');
            bodyDiv.style.maxHeight = '7rem';
            clickedText.innerHTML = 'Hide Body';
        } else {
            bodyDiv.classList.add('hidden');
            clickedText.innerHTML = 'Show Body';
        }
    }
</script>
