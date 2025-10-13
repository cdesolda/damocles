<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <!-- Back to training campaign details -->
            <a href="{{ route('training-campaign.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('training-campaign.index') }}">@lang('training-campaign.trainingCampaigns')</a></li>
                <li>/</li>
                <li>@lang('training-campaign.analyseCampaign.analyse')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

                <div class="flex flex-col w-full">

                    <div class="flex justify-between">
                        <p class="font-semibold text-xl">@lang('training-campaign.analyseCampaign.analyses')</p>

                        <x-primary-button class="hover:bg-gray-100 inner-element" data-id="{{ $trainingCampaign->id }}"
                            x-data=""
                            @click="$dispatch('open-modal', 'download-data-csv-modal', { id: {{ $trainingCampaign->id }} })">@lang('general.downloadData')</x-primary-button>
                    </div>
                </div>

                <!-- Charts -->
                <div class="my-4">
                    <div
                        class="flex flex-col py-2 space-y-4 md:space-y-0 md:flex-row md:justify-around w-full items-center">
                        <div class="flex flex-col items-center w-60 md:w-64">
                            <canvas id="totalGenerated"></canvas>
                        </div>
                        <div class="flex flex-col items-center w-60 md:w-64">
                            <canvas id="totalDone"></canvas>
                        </div>
                    </div>
                </div>

                <!-- User -->
                @if ($userTrainings->count() > 0)

                    <div class="flex flex-col md:flex-row md:items-center py-2">

                        <div class="flex py-2 md:py-0 md:w-1/2 justify-start">
                            <p class="font-semibold">@lang('phishing-campaign.detailsCampaign.users'):</p>
                        </div>

                        <div class="flex py-2 md:py-0 md:w-1/2 justify-end">
                            <div class="flex flex-row w-80 gap-3 items-center relative">
                                @if (!$userTrainings->isEmpty())
                                    <!-- Filter -->
                                    <label for="userFilter"
                                        class="block text-sm font-bold text-sky-700">@lang('general.search')</label>
                                    <input type="text" id="userFilter" name="userFilter"
                                        class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                                        placeholder="@lang('general.enterSearchUser')">
                                    <button id="clear-user-filter"
                                        class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 0a10 10 0 0 1 7.071 2.929A10 10 0 0 1 20 10a10 10 0 0 1-2.929 7.071A10 10 0 0 1 10 20a10 10 0 0 1-7.071-2.929A10 10 0 0 1 0 10a10 10 0 0 1 2.929-7.071A10 10 0 0 1 10 0zm3.536 5.05a.5.5 0 0 1 .708.708L10.707 10l3.536 3.536a.5.5 0 0 1-.708.708L10 10.707l-3.536 3.536a.5.5 0 1 1-.708-.708L9.293 10 5.757 6.464a.5.5 0 0 1 .708-.708L10 9.293l3.536-3.536z" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    @foreach ($userTrainings as $userTraining)
                        <div class="p-4 user-trainings-entry rounded-lg shadow-sm"
                            id="user-training-{{ $userTraining['id'] }}">
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('general.user.name'):</p>
                                <p class="user-name">{{ $userTraining->user->name }}</p>
                            </div>
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('general.user.surname'):</p>
                                <p class="user-surname">{{ $userTraining->user->surname }}</p>
                            </div>
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('general.user.dob'):</p>
                                <p>{{ $userTraining->user->dob }}</p>
                            </div>
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('general.user.gender'):</p>
                                <p class="user-gender">{{ $userTraining->user->gender }}</p>
                            </div>
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('general.user.email'):</p>
                                <p class="user-email">{{ $userTraining->user->email }}</p>
                            </div>
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('general.user.companyRole'):</p>
                                <p>{{ $userTraining->user->company_role }}</p>
                            </div>

                            @if ($userTraining->training != null)
                                <div class="flex flex-row gap-2">
                                    <p class="font-semibold">@lang('training-campaign.analyseCampaign.done'):</p>
                                    <p class="{{ $userTraining->done ? 'text-[#28A745]' : 'text-[#DC3545]' }}">
                                        {!! $userTraining->done ? 'True' : 'False' !!}
                                    </p>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <p class="font-semibold">@lang('training-campaign.analyseCampaign.training'):</p>
                                    <textarea id="prompt" name="prompt"
                                        class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm" rows="4"
                                        readonly>{{ $userTraining->training }}</textarea>
                                </div>
                            @else
                                <div class="flex flex-row gap-2">
                                    <p class="font-semibold">@lang('training-campaign.analyseCampaign.training'):</p>
                                    <p>@lang('training-campaign.analyseCampaign.noTraining')</p>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Paginator controls -->
                    <div id="pagination-controls" class="flex justify-around items-center pt-4">
                        <div class="flex justify-center w-1/3">
                            <x-primary-button id="prevUser">@lang('general.previous')</x-primary-button>
                        </div>
                        <div class="flex justify-center w-1/3">
                            <span id="userTrainingsIndicator" class="text-gray-700"></span>
                        </div>
                        <div class="flex justify-center w-1/3">
                            <x-primary-button id="nextUser">@lang('general.next')</x-primary-button>
                        </div>
                    </div>
                @else
                    <div class="flex py-2 md:py-0 md:w-1/2 justify-start">
                        <p class="font-semibold">@lang('general.user.users'):</p>
                    </div>
                    <div class="text-center text-xl py-4">
                        <p>@lang('training-campaign.analyseCampaign.noUsers')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<!-- Download data csv modal -->
<x-modal name="download-data-csv-modal" id="download-data-csv-modal" title="Download data csv training Campaign"
    :show="false">
    <div class="p-4 rounded-lg relative">
        @include('training-campaign.modals.download-data-csv')
    </div>
</x-modal>

<script>
    // Charts initialization
    const totalUserTrainings = {{ $userTrainings->count() }};
    const countUserTrainingsGenerated = {{ $countUserTrainingsGenerated }};
    const countUserTrainingsDone = {{ $countUserTrainingsDone }};

    const dataTotalTrainingGenerated = [{
            status: 'Not generated',
            count: totalUserTrainings - countUserTrainingsGenerated
        },
        {
            status: 'Generated',
            count: countUserTrainingsGenerated
        }
    ];

    new Chart(document.getElementById('totalGenerated'), {
        type: 'doughnut',
        data: {
            labels: dataTotalTrainingGenerated.map(row => row.status),
            datasets: [{
                data: dataTotalTrainingGenerated.map(row => row.count),
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
                    text: 'Training generated (' + countUserTrainingsGenerated + ') of total (' +
                        totalUserTrainings + ')'
                }
            }
        }
    });

    const dataTotalDone = [{
            status: 'Not done',
            count: countUserTrainingsGenerated - countUserTrainingsDone
        },
        {
            status: 'Done',
            count: countUserTrainingsDone
        }
    ];

    new Chart(document.getElementById('totalDone'), {
        type: 'doughnut',
        data: {
            labels: dataTotalDone.map(row => row.status),
            datasets: [{
                data: dataTotalDone.map(row => row.count),
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
                    text: 'Training done (' + countUserTrainingsDone + ') of total (' +
                        countUserTrainingsGenerated + ')'
                }
            }
        }
    });

    // Tabulation and Filter
    document.addEventListener('DOMContentLoaded', function() {
        const userTrainings = @json($userTrainings);
        let currentUserIndex = 0;
        const userContainers = document.querySelectorAll(".user-trainings-entry");
        const prevUserButton = document.getElementById('prevUser');
        const nextUserButton = document.getElementById('nextUser');
        const userTrainingsIndicator = document.getElementById('userTrainingsIndicator');
        const paginationControls = document.getElementById('pagination-controls');

        function toggleNavigationButtons() {
            if (userTrainings.length <= 1) {
                prevUserButton.style.display = 'none';
                nextUserButton.style.display = 'none';
            } else {
                prevUserButton.style.display = currentUserIndex > 0 ? 'block' : 'none';
                nextUserButton.style.display = currentUserIndex < userTrainings.length - 1 ? 'block' : 'none';
            }
        }

        function showUser(index) {
            userContainers.forEach(function(container, idx) {
                container.style.display = idx === index ? 'block' : 'none';
            });
            userTrainingsIndicator.textContent = `User: ${index + 1} / ${userTrainings.length}`;
            toggleNavigationButtons();
        }

        function resetSearch() {
            filterInput.value = "";
            clearFilterButton.style.display = "none";
            userContainers.forEach(function(container, index) {
                container.style.display = index === 0 ? 'block' : 'none';
            });
            currentUserIndex = 0;
            showUser(currentUserIndex);
            paginationControls.style.display = 'flex';
            noResultsMessage.style.display = 'none';
        }

        prevUserButton.addEventListener('click', function() {
            if (currentUserIndex > 0) {
                currentUserIndex--;
                showUser(currentUserIndex);
            }
        });

        nextUserButton.addEventListener('click', function() {
            if (currentUserIndex < userTrainings.length - 1) {
                currentUserIndex++;
                showUser(currentUserIndex);
            }
        });

        // Initialize: show the first user and manage navigation buttons
        showUser(currentUserIndex);

        // Filter handling
        const filterInput = document.getElementById("filter");
        const clearFilterButton = document.getElementById("clear-filter");
        const noResultsMessage = document.getElementById('no-results');

        if (filterInput && clearFilterButton) {
            filterInput.addEventListener("input", function() {
                const filterValue = this.value.toLowerCase().trim();

                clearFilterButton.style.display = this.value.trim() !== "" ? "block" : "none";

                let resultsFound = false;

                userContainers.forEach(function(container, index) {
                    const name = container.querySelector('.user-name').textContent
                        .toLowerCase();
                    const surname = container.querySelector('.user-surname').textContent
                        .toLowerCase();
                    const email = container.querySelector('.user-email').textContent
                        .toLowerCase();
                    const gender = container.querySelector('.user-gender').textContent
                        .toLowerCase();

                    if (name.includes(filterValue) || surname.includes(filterValue) || email
                        .includes(filterValue) || gender.includes(filterValue)) {
                        container.style.display = 'block';
                        if (index === 0) {
                            showUser(index);
                        }
                        resultsFound = true;
                    } else {
                        container.style.display = 'none';
                    }
                });

                // Show no results
                noResultsMessage.style.display = resultsFound ? 'none' : 'block';

                // Show pagination controls only if there's no active filter
                paginationControls.style.display = filterValue !== "" ? 'none' : 'flex';

                // Reset pagination and show first user if filter is cleared manually
                if (filterValue === "") {
                    resetSearch();
                }
            });

            clearFilterButton.addEventListener("click", function() {
                resetSearch();
            });
        }
    });
</script>
