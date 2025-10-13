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
                <li>@lang('digital-twin.analyse')</li>
            </ul>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">
                <div class="flex flex-col w-full">
                    {{-- <p class="text-lg w-full">{{ $digitalTwinsCampaign->title }}</p> --}}
                    <div class="flex justify-between">
                        <p class="font-semibold text-xl">@lang('digital-twin.simulationAnalyses')</p>

                        <x-primary-button class="hover:bg-gray-100 inner-element"
                            data-id="{{ $digitalTwinsCampaign->id }}" x-data=""
                            @click="$dispatch('open-modal', 'download-data-csv-modal', { id: {{ $digitalTwinsCampaign->id }} })">@lang('general.downloadData')
                        </x-primary-button>

                    </div>
                    <!-- Tabs -->
                    <div class="flex justify-center border-b border-gray-300">
                        <button id="tab-overview"
                            class="tab-btn-general active-tab px-4 py-2 font-semibold text-sky-700 border-b-2 border-sky-700 hover:text-sky-700">
                            @lang('questionnaire-campaign.analyseCampaign.overview')
                        </button>
                        <button id="tab-details"
                            class="tab-btn-general px-4 py-2 font-semibold text-gray-600 border-b-2 hover:text-sky-700">
                            @lang('general.details')
                        </button>
                    </div>
                    <div id="overview-content" class="tab-content-general flex flex-col gap-2 pt-4">
                        <!-- Charts -->
                        <div class="my-4">
                            <div id="chart-container" class="flex flex-wrap justify-center gap-4">
                                <div id="openedEmailsContainer" class="flex justify-center items-center"><canvas
                                        id="openedEmails"></canvas></div>
                                <div id="clickedEmailsContainer" class="flex justify-center items-center"><canvas
                                        id="clickedEmails"></canvas></div>
                                <div id="genderOpenedContainer" class="flex justify-center items-center"><canvas
                                        id="genderOpened"></canvas></div>
                                <div id="genderClickedContainer" class="flex justify-center items-center"><canvas
                                        id="genderClicked"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div id="details-content" class="hidden tab-content-general flex flex-col gap-2 pt-4">
                        <div class="flex flex-col md:flex-row justify-between items-center w-full gap-4">
                            <div class="flex w-full md:w-1/2 justify-center md:justify-start">
                                <section>
                                    <header>
                                        <p class="text-lg font-medium text-sky-900">
                                            @lang('digital-twin.analyseBy'):
                                        </p>
                                    </header>

                                    <x-input-label for="typeOfAnalysisIdSelect"
                                        class="text-md block font-medium text-sky-700" :value="'Choose the focus of the analysis'" />
                                    <select id="typeOfAnalysisIdSelect" name="threatId"
                                        class="mt-1 block w-full py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
                                        <option value="tab-emailTable">@lang('email.email')</option>
                                        <option value="tab-userTable">@lang('general.user.user')</option>
                                    </select>
                                </section>
                            </div>

                            <!-- Filter -->
                            @if ($emails->count() > 1)
                                <div class="flex w-full md:w-1/2 justify-center md:justify-end">
                                    <div id="email-filter-content"
                                        class="flex flex-row w-80 gap-3 justify-end items-center relative">
                                        <label for="filter-email"
                                            class="block text-sm font-bold text-sky-700">@lang('general.search')</label>
                                        <input type="text" id="filter-email" name="filter"
                                            class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                                            placeholder="@lang('general.enterSearchEmail')"
                                            value="{{ request()->query('filter', '') }}">
                                        <button id="clear-filter-email"
                                            class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 0a10 10 0 0 1 7.071 2.929A10 10 0 0 1 20 10a10 10 0 0 1-2.929 7.071A10 10 0 0 1 10 20a10 10 0 0 1-7.071-2.929A10 10 0 0 1 0 10a10 10 0 0 1 2.929-7.071A10 10 0 0 1 10 0zm3.536 5.05a.5.5 0 0 1 .708.708L10.707 10l3.536 3.536a.5.5 0 0 1-.708.708L10 10.707l-3.536 3.536a.5.5 0 1 1-.708-.708L9.293 10 5.757 6.464a.5.5 0 0 1 .708-.708L10 9.293l3.536-3.536z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Filter -->
                            @if ($users->count() > 1)
                                <div id="user-filter-content"
                                    class="hidden flex w-full md:w-1/2 justify-center md:justify-end">
                                    <div class="flex flex-row w-80 gap-3 justify-end items-center relative">
                                        <label for="filter"
                                            class="block text-sm font-bold text-sky-700">@lang('general.search')</label>
                                        <input type="text" id="filter" name="filter"
                                            class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                                            placeholder="@lang('general.enterSearchUser')"
                                            value="{{ request()->query('filter', '') }}">
                                        <button id="clear-filter"
                                            class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 0a10 10 0 0 1 7.071 2.929A10 10 0 0 1 20 10a10 10 0 0 1-2.929 7.071A10 10 0 0 1 10 20a10 10 0 0 1-7.071-2.929A10 10 0 0 1 0 10a10 10 0 0 1 2.929-7.071A10 10 0 0 1 10 0zm3.536 5.05a.5.5 0 0 1 .708.708L10.707 10l3.536 3.536a.5.5 0 0 1-.708.708L10 10.707l-3.536 3.536a.5.5 0 1 1-.708-.708L9.293 10 5.757 6.464a.5.5 0 0 1 .708-.708L10 9.293l3.536-3.536z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div id="email-content" class="tab-content flex flex-col gap-2 pt-4">
                            <!-- Paginator controls -->
                            <div id="pagination-controls-email" class="flex justify-around items-center">
                                <div class="flex justify-center w-1/3">
                                    <x-primary-button id="prevEmail">@lang('general.previous')</x-primary-button>
                                </div>
                                <div class="flex justify-center w-1/3">
                                    <span id="emailIndicator" class="text-gray-700"></span>
                                </div>
                                <div class="flex justify-center w-1/3">
                                    <x-primary-button id="nextEmail">@lang('general.next')</x-primary-button>
                                </div>
                            </div>
                            @foreach ($emails as $email)
                                <div class="py-4 email-entry" id="email-{{ $email->id }}">
                                    <div
                                        class="flex flex-col items-left border border-gray-300 rounded-lg p-4 w-fit mx-auto">
                                        <div class="flex flex-row gap-1">
                                            <p class="font-bold min-w-[62px]">@lang('email.subject'):</p>
                                            <p class="email-subject">{{ $email->subject }}</p>
                                        </div>
                                        <div class="flex flex-row gap-1">
                                            <p class="font-bold min-w-[62px]">@lang('email.body'):</p>
                                            <p class="cursor-pointer text-blue-500"
                                                onclick="toggleBodyVisibility('bodyDiv{{ $email->id }}', this)">
                                                Show Body
                                            </p>
                                        </div>
                                        <div class="flex flex-row gap-1">
                                            <p id="bodyDiv{{ $email->id }}"
                                                class="hidden bg-gray-200 text-sm email-body whitespace-pre-line overflow-auto max-h-48 mt-2 p-2 text-left rounded-lg shadow-sm"
                                                style="white-space: pre-line;">{{ $email->body }}</p>
                                        </div>
                                    </div>

                                    <div class="w-full text-center mb-4">
                                        <table id="user-table-{{ $email->id }}" class="w-full text-center mb-4">
                                            <thead class="bg-gray-100">
                                                <tr class="border-b-2 border-gray-300">
                                                    <th class="py-2 px-4">@lang('general.user.user')</th>
                                                    <th class="py-2 px-4">@lang('general.user.gender')</th>
                                                    <th class="py-2 px-4">@lang('general.user.email')</th>
                                                    <th class="py-2 px-4  w-1/3">@lang('phishing-campaign.analyseCampaign.opened')</th>
                                                    <th class="py-2 px-4  w-1/3">@lang('phishing-campaign.analyseCampaign.clicked')</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                                @foreach ($email->users as $user)
                                                    <tr class="border-b border-gray-200">
                                                        <td class="py-2 px-4">{{ $user['user']->name }}
                                                            {{ $user['user']->surname }}</td>
                                                        <td class="py-2 px-4">{{ $user['user']->gender }}</td>
                                                        <td class="py-2 px-4">{{ $user['user']->email }}</td>

                                                        <td class="py-2 px-4">
                                                            <div
                                                                class="group relative {{ $user['opened'] ? 'text-[#DC3545]' : 'text-[#28A745]' }}">
                                                                {!! $user['opened'] ? 'Yes' : 'No' !!}
                                                            </div>
                                                            <div
                                                                class="mt-2 p-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 text-xs w-full max-h-32 overflow-y-auto shadow-sm">
                                                                {{ $user['opened_explanation'] }}
                                                            </div>
                                                        </td>

                                                        <td class="py-2 px-4">
                                                            <div
                                                                class="group relative {{ $user['clicked'] ? 'text-[#DC3545]' : 'text-[#28A745]' }}">
                                                                {!! $user['clicked'] ? 'Yes' : 'No' !!}
                                                            </div>
                                                            <div
                                                                class="mt-2 p-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 text-xs w-full max-h-32 overflow-y-auto shadow-sm">
                                                                {{ $user['clicked_explanation'] }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <!-- Internal Paginator controls -->
                                        <div id="pagination-controls-user-{{ $email->id }}"
                                            class="pagination-controls flex justify-around items-center mt-4">
                                            <div class="flex justify-center w-1/3">
                                                <x-primary-button
                                                    id="prevPage-user-{{ $email->id }}">@lang('general.previous')</x-primary-button>
                                            </div>
                                            <div class="flex flex-row justify-center items-center w-1/3 gap-4">
                                                <span id="pageIndicator-user-{{ $email->id }}"
                                                    class="text-gray-700"></span>
                                                <span id="totalRows-user-{{ $email->id }}"
                                                    class="text-gray-500 text-sm"></span>
                                                <select id="rowsPerPage-user-{{ $email->id }}"
                                                    class="border border-gray-300 rounded-md shadow-sm">
                                                    <option value="10" selected>10</option>
                                                    <option value="20">20</option>
                                                    <option value="50">50</option>
                                                </select>
                                            </div>
                                            <div class="flex justify-center w-1/3">
                                                <x-primary-button
                                                    id="nextPage-user-{{ $email->id }}">@lang('general.next')</x-primary-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div id="user-content" class="hidden tab-content flex flex-col gap-2 pt-4">
                            <!-- Paginator controls -->
                            <div id="pagination-controls" class="flex justify-around items-center">
                                <div class="flex justify-center w-1/3">
                                    <x-primary-button id="prevUser">@lang('general.previous')</x-primary-button>
                                </div>
                                <div class="flex justify-center w-1/3">
                                    <span id="userIndicator" class="text-gray-700"></span>
                                </div>
                                <div class="flex justify-center w-1/3">
                                    <x-primary-button id="nextUser">@lang('general.next')</x-primary-button>
                                </div>
                            </div>
                            @foreach ($users as $user)
                                <div class="py-4 user-entry" id="user-{{ $user->id }}">
                                    <div
                                        class="min-w-[365px] flex flex-col items-left border border-gray-300 rounded-lg p-4 w-fit mx-auto">
                                        <div class="flex flex-row gap-1">
                                            <p class="font-bold min-w-[62px]">@lang('general.user.user'):</p>
                                            <p class="user-name">{{ $user->name }} {{ $user->surname }}</p>
                                        </div>
                                        <div class="flex flex-row gap-1">
                                            <p class="font-bold min-w-[62px]">@lang('user.gender'):</p>
                                            <p class="user-gender">{{ $user->gender }}</p>
                                        </div>
                                        <div class="flex flex-row gap-1">
                                            <p class="font-bold min-w-[62px]">@lang('user.email'):</p>
                                            <p class="user-email">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="w-full text-center mb-4">
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
                                                            {{ $email['email']->subject }}
                                                            @if ($email['email']->subject === __('email.noEmailFound.subject'))
                                                                <div
                                                                    class="absolute left-1/2 transform -translate-x-1/2 mt-2 w-48 p-2 bg-sky-900 rounded-md text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                                    {{ __('email.noEmailFound.body') }}
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
                </div>

                <!-- User -->
                <div id="no-results" class="font-bold py-4 text-center hidden">
                    @lang('general.noResult')
                </div>
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


<script src="{{ asset('js/tabulationAndFilter.js') }}"></script>

<script>
    // Expand the body
    function toggleBodyVisibility(bodyId, clickedText) {
        var bodyDiv = document.getElementById(bodyId);

        if (bodyDiv.classList.contains('hidden')) {
            bodyDiv.classList.remove('hidden');
            clickedText.innerHTML = 'Hide Body';
        } else {
            bodyDiv.classList.add('hidden');
            clickedText.innerHTML = 'Show Body';
        }
    }

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
        setupPagination('user-table-');
        setupPagination('email-table-');
    });

    // Tabs
    document.addEventListener("DOMContentLoaded", () => {
        const tabs = document.querySelectorAll(".tab-btn-general");
        const contents = document.querySelectorAll(".tab-content-general");

        tabs.forEach((tab, index) => {
            tab.addEventListener("click", () => {
                tabs.forEach(t => t.classList.remove("active-tab", "text-sky-700",
                    "border-sky-700"));
                contents.forEach(content => content.classList.add("hidden"));
                tab.classList.add("active-tab", "text-sky-700", "border-sky-700");
                contents[index].classList.remove("hidden");
            });
        });
    });

    // details Tabs
    document.getElementById('typeOfAnalysisIdSelect').addEventListener('change', function() {
        var selectedValue = this.value;

        var emailContent = document.getElementById('email-content');
        var userContent = document.getElementById('user-content');
        var emailFilterContent = document.getElementById('email-filter-content');
        var userFilterContent = document.getElementById('user-filter-content');

        if (emailContent) emailContent.classList.add('hidden');
        if (userContent) userContent.classList.add('hidden');
        if (emailFilterContent) emailFilterContent.classList.add('hidden');
        if (userFilterContent) userFilterContent.classList.add('hidden');

        if (selectedValue === 'tab-emailTable') {
            if (emailContent) emailContent.classList.remove('hidden');
            if (emailFilterContent) emailFilterContent.classList.remove('hidden');
        } else if (selectedValue === 'tab-userTable') {
            if (userContent) userContent.classList.remove('hidden');
            if (userFilterContent) userFilterContent.classList.remove('hidden');
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const chartContainer = document.getElementById("chart-container");

        // These two charts should always be visible
        const openedEmailsContainer = document.getElementById("openedEmailsContainer");
        const clickedEmailsContainer = document.getElementById("clickedEmailsContainer");
        const genderOpenedContainer = document.getElementById("genderOpenedContainer");
        const genderClickedContainer = document.getElementById("genderClickedContainer");
        openedEmailsContainer.classList.remove("hidden");
        clickedEmailsContainer.classList.remove("hidden");

        let genderChartsCount = 0;
        if (totalOpens > 0) {
            genderOpenedContainer.classList.remove("hidden");
            genderChartsCount++;
        } else {
            genderOpenedContainer.classList.add("hidden");
        }

        if (totalClicks > 0) {
            genderClickedContainer.classList.remove("hidden");
            genderChartsCount++;
        } else {
            genderClickedContainer.classList.add("hidden");
        }

        // apply 2x2 grid if all 4 charts are visible
        if (genderChartsCount === 2) {
            chartContainer.classList.remove("flex", "flex-wrap", "justify-center");
            chartContainer.classList.add("grid", "grid-cols-2", "gap-4");
        } else {
            // apply flex layout if only 2 or 3 charts are visible
            chartContainer.classList.add("flex", "flex-wrap", "justify-center");
            chartContainer.classList.remove("grid", "grid-cols-2");
        }
    });

    // Charts initialization
    const totalEmailsCount = {{ $digitalTwinsResults->count() }};
    const sentEmailsCount = {{ $digitalTwinsResults->count() }};
    const emailOpenedCount = {{ $emailOpened->count() }};
    const emailNotOpenedCount = {{ $emailNotOpened->count() }};
    const emailClickedCount = {{ $emailClicked->count() }};
    const emailNotClickedCount = {{ $emailNotClicked->count() }};

    const opened = sentEmailsCount - emailNotOpenedCount
    const notOpened = sentEmailsCount - emailOpenedCount
    const dataOpenedEmails = [{
            status: 'Not opened',
            count: notOpened
        },
        {
            status: 'Opened',
            count: opened
        }
    ];

    new Chart(document.getElementById('openedEmails'), {
        type: 'doughnut',
        data: {
            labels: dataOpenedEmails.map(row => row.status),
            datasets: [{
                data: dataOpenedEmails.map(row => row.count),
                backgroundColor: ['#F44336', '#4CAF50']
            }]
        },
        options: {
            responsive: false,
            plugins: {
                legend: {
                    position: 'left',
                },
                title: {
                    display: true,
                    text: 'Phishing Emails opened (' + opened + ') and not opened (' + notOpened + ')'
                }
            }
        }
    });

    const clicked = sentEmailsCount - emailNotClickedCount
    const notClicked = sentEmailsCount - emailClickedCount
    const dataClickedEmails = [{
            status: 'Not clicked',
            count: notClicked
        },
        {
            status: 'Clicked',
            count: clicked
        }
    ];

    new Chart(document.getElementById('clickedEmails'), {
        type: 'doughnut',
        data: {
            labels: dataClickedEmails.map(row => row.status),
            datasets: [{
                data: dataClickedEmails.map(row => row.count),
                backgroundColor: ['#F44336', '#4CAF50']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'left',
                },
                title: {
                    display: true,
                    text: 'Phishing Emails links clicked (' + clicked + ') and not clicked (' + notClicked + ')'
                }
            }
        }
    });

    const dataGenderOpened = [{
            status: 'Male',
            count: emailOpenedCount - {{ $openedFemaleCount }} - {{ $openedOtherCount }}
        },
        {
            status: 'Female',
            count: emailOpenedCount - {{ $openedMaleCount }} - {{ $openedOtherCount }}
        },
        {
            status: 'Other',
            count: emailOpenedCount - {{ $openedMaleCount }} - {{ $openedFemaleCount }}
        }
    ];
    const totalOpens = dataGenderOpened.reduce((sum, row) => sum + row.count, 0);

    if (totalOpens > 0) {
        new Chart(document.getElementById('genderOpened'), {
            type: 'doughnut',
            data: {
                labels: dataGenderOpened.map(row => row.status),
                datasets: [{
                    data: dataGenderOpened.map(row => row.count),
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'left',
                    },
                    title: {
                        display: true,
                        text: 'Phishing Emails opened by gender'
                    }
                },
                layout: {
                    padding: 10
                },
            }
        });
    } else {
        document.getElementById('genderOpenedContainer').style.display = 'none';
    }
    const dataGenderClicked = [{
            status: 'Male',
            count: emailClickedCount - {{ $clickedFemaleCount }} - {{ $clickedOtherCount }}
        },
        {
            status: 'Female',
            count: emailClickedCount - {{ $clickedMaleCount }} - {{ $clickedOtherCount }}
        },
        {
            status: 'Other',
            count: emailClickedCount - {{ $clickedMaleCount }} - {{ $clickedFemaleCount }}
        }
    ];

    const totalClicks = dataGenderClicked.reduce((sum, row) => sum + row.count, 0);

    if (totalClicks > 0) {
        new Chart(document.getElementById('genderClicked'), {
            type: 'doughnut',
            data: {
                labels: dataGenderClicked.map(row => row.status),
                datasets: [{
                    data: dataGenderClicked.map(row => row.count),
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'left',
                    },
                    title: {
                        display: true,
                        text: 'Phishing Emails links clicked by gender'
                    }
                },
                layout: {
                    padding: 10
                },
            }
        });
    } else {
        document.getElementById('genderClickedContainer').style.display = 'none';
    }

    // Tabulation
    document.addEventListener('DOMContentLoaded', function() {
        initializeTabulationAndFilter({
            elements: @json($emails),
            elementContainers: document.querySelectorAll(".email-entry"),
            prevButton: document.getElementById('prevEmail'),
            nextButton: document.getElementById('nextEmail'),
            indicator: document.getElementById('emailIndicator'),
            paginationControls: document.getElementById('pagination-controls-email'),
            filterInput: document.getElementById("filter-email"),
            clearFilterButton: document.getElementById("clear-filter-email"),
            noResultsMessage: document.getElementById('no-results'),
            nameClass: 'email-subject',
            additionalFilters: [null],
            customLabel: "Email"
        });

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
</script>
