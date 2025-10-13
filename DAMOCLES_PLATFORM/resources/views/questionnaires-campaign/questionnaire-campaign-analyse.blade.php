<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to questionnaire campaign details -->
            <a href="{{ route('questionnaires-campaign.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('questionnaires-campaign.index') }}">@lang('questionnaire-campaign.questionnaireCampaigns')</a></li>
                <li>/</li>
                <li>@lang('questionnaire-campaign.analyseCampaign.analyse')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8 text-sky-900">
                <div class="flex flex-col w-full">
                    <p class="font-semibold text-xl">@lang('questionnaire-campaign.analyseCampaign.analyses')</p>

                    <!-- Filter Questionnaires -->
                    <x-input-label for="questionnaireSelect"
                        class="font-semibold block pb-2 flex flex-wrap items-center mt-2">
                        <p class="text-base font-semibold text-sky-900">@lang('questionnaire-campaign.analyseCampaign.filterQuestionnaires')</p>

                        <div id="questionnaireButtons" class="flex flex-wrap gap-2 ml-4">
                            @foreach ($questionnaires as $questionnaire)
                                <button type="button"
                                    class="questionnaire-button py-2 px-4 border text-white border-sky-800 bg-sky-800 rounded-md shadow-sm hover:bg-sky-700 hover:text-white focus:outline-none focus:ring-sky-800 focus:border-sky-800 text-sm sm:text-xs"
                                    data-questionnaire-id="{{ $questionnaire->id }}">
                                    {{ $questionnaire->name }}
                                </button>
                            @endforeach
                        </div>
                    </x-input-label>

                    <!-- Charts -->
                    @php
                        $divsPerRow = count($questionnaires);
                    @endphp

                    <!-- Tabs -->
                    <div class="flex justify-center border-b border-gray-300">
                        <button id="tab-overview"
                            class="tab-btn active-tab px-4 py-2 font-semibold text-sky-700 border-b-2 border-sky-700 hover:text-sky-700">
                            @lang('questionnaire-campaign.analyseCampaign.overview')
                        </button>
                        <button id="tab-details"
                            class="tab-btn px-4 py-2 font-semibold text-gray-600 border-b-2 hover:text-sky-700">
                            @lang('general.details')
                        </button>
                    </div>

                    <div id="overview-content" class="tab-content">
                        <!-- Tab Content -->
                        <div class="flex flex-col py-2 space-y-4 md:space-y-0 md:flex-row md:justify-around w-full justify-start overflow-x-auto"
                            id="charts-container">
                            @foreach ($questionnaires as $questionnaire)
                                <div
                                    class="flex flex-col items-center w-full lg:min-w-[33.33%] md:min-w-[50%] questionnaire-{{ $questionnaire->id }}">
                                    <!-- Chart -->
                                    <div class="w-full flex justify-center">
                                        <div class="md:w-64">
                                            <canvas id="chart-{{ $questionnaire->id }}"></canvas>
                                        </div>
                                    </div>

                                    <!-- Scales results -->
                                    @php
                                        $questionnaireName = str_replace(' ', '_', $questionnaire->name);
                                        $scales = $tableNameAverages[$questionnaireName] ?? null;
                                    @endphp

                                    @if (isset($scales))
                                        <div class="scales-container p-2 mt-2"
                                            id="scalesResult-{{ $questionnaire->id }}">
                                            <p class="font-semibold">
                                                @lang('questionnaire-campaign.analyseCampaign.avgResults'):
                                                <button class="toggle-scales text-sm ml-2"
                                                    data-id="{{ $questionnaire->id }}"> &#9660; </button>
                                            </p>

                                            <ul id="scales-list-{{ $questionnaire->id }}"
                                                class="list-disc pl-4 pr-4 mt-2 hidden">
                                                @foreach ($scales as $scale => $value)
                                                    @php
                                                        $maxValue = $questionnaire->likert_scales;
                                                        $percentage = ($value / $maxValue) * 100;
                                                    @endphp
                                                    <li class="scale-item mb-3 items-center space-x-3  xl:flex">
                                                        <span
                                                            class="font-medium flex-shrink-0 text-sm w-1/3">{{ $scale }}:</span>

                                                        <!-- Progress bar -->
                                                        <div class="flex-1 bg-gray-300 rounded-full h-2">
                                                            <div class="bg-sky-800 h-2 rounded-full"
                                                                style="width: {{ $percentage }}%"></div>
                                                        </div>

                                                        <!-- Display value and percentage -->
                                                        <span
                                                            class="ml-3 font-medium text-sm w-1/3 text-right">{{ number_format($value, 2) }}
                                                            ({{ number_format($percentage, 2) }}%)
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <div class="scales-container p-2 mt-2">
                                            <p class="font-semibold">
                                                @lang('questionnaire-campaign.analyseCampaign.avgResults'):
                                                <button disabled class="toggle-scales text-sm ml-2 text-gray-600">
                                                    @lang('questionnaire-campaign.analyseCampaign.noData') </button>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="details-content" class="tab-content hidden">
                        <!-- Tab Content -->
                        <br>
                        <!-- Filter Users-->
                        @if ($users->count() > 1)
                            <div
                                class="flex w-full md:w-auto justify-center md:justify-end sm:items-center mt-4 md:mt-0">
                                <div class="flex flex-row w-80 gap-3 justify-end items-center relative">
                                    <label for="filter"
                                        class="block text-sm font-bold text-sky-700">@lang('general.search')</label>
                                    <input type="text" id="filter" name="filter"
                                        class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                                        placeholder="@lang('general.enterSearchUser')">
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

                        <!-- Table -->
                        <div class="w-full text-center mb-4">
                            <table id="questionnaire-summary" class="w-full text-center mb-4">
                                <thead class="bg-gray-100">
                                    <tr class="border-b-2 border-gray-300">
                                        <th class="py-2 px-4">@lang('questionnaire-campaign.analyseCampaign.user')</th>
                                        @foreach ($questionnaires as $questionnaire)
                                            <th class="py-2 px-4   questionnaire-{{ $questionnaire->id }}">
                                                {{ $questionnaire->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @if ($users->count() > 0)
                                        @foreach ($users as $user)
                                            <tr class="border-b border-gray-200">
                                                <td class="py-2 px-4">{{ $user->name }} {{ $user->surname }}</td>
                                                <!-- User and Questionnaires Display -->
                                                @foreach ($questionnaires as $questionnaire)
                                                    <td
                                                        class="border-l border-gray-200 py-2 px-4  questionnaire-{{ $questionnaire->id }}">
                                                        <div class="!relative flex justify-center gap-4">

                                                            @if (isset($userAnswers[$user->id][$questionnaire->id]))
                                                                <!-- Display "YES" and Options -->
                                                                <p class="text-[#28A745]">
                                                                    <svg class="fill-current text-[#28A745] h-6 w-6"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 20 20">
                                                                        <title>Answered</title>
                                                                        <path d="M5 10l3 3L15 5" stroke="currentColor"
                                                                            stroke-width="1.5" stroke-linecap="round"
                                                                            stroke-linejoin="round" fill="none" />
                                                                    </svg>
                                                                </p>

                                                                <div class="absolute right-0  h-full flex items-center">
                                                                    <x-dropdown align="right" width="48">
                                                                        <x-slot name="trigger">
                                                                            <button
                                                                                class="inline-flex items-center font-semibold">
                                                                                <p class="classic !p-1">
                                                                                    <span>
                                                                                        <svg class="fill-current h-4 w-4"
                                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                                            viewBox="0 0 20 20">
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
                                                                                @foreach ($userAnswers[$user->id][$questionnaire->id] as $userAnswer)
                                                                                    <a href="{{ route(str_replace(' ', '-', strtolower($questionnaire->name)) . '.result', [str_replace(' ', '_', strtolower($questionnaire->name)) => $userAnswer->answer_id]) }}"
                                                                                        class="hover:bg-gray-200 inner-element py-1">
                                                                                        @lang('questionnaire-campaign.analyseCampaign.result')
                                                                                    </a>
                                                                                    @if ($questionnaireCampaign->state === 'Live')
                                                                                        <button
                                                                                            class="hover:bg-gray-100 text-red-500 inner-element py-1"
                                                                                            data-answer-id="{{ $userAnswer->id }}"
                                                                                            x-data=""
                                                                                            @click="$dispatch('open-modal', 'delete-modal', { id: {{ $userAnswer->id }} })">
                                                                                            @lang('questionnaire-campaign.analyseCampaign.delete')
                                                                                        </button>
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        </x-slot>
                                                                    </x-dropdown>
                                                                </div>
                                                            @else
                                                                <p class="text-red-500">
                                                                    <svg class="fill-current text-red-500 h-5 w-5"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 20 20">
                                                                        <title>@lang('questionnaire-campaign.analyseCampaign.unanswered')</title>
                                                                        <path d="M5 5L15 15M5 15L15 5"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" />
                                                                    </svg>
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="no-results" class="hidden">
                                            <td colspan="100%" class="font-bold py-4 text-center">
                                                @lang('general.noResult')
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!-- Paginator controls -->
                            @include('layouts.partials.pagination-controls')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Delete modal -->
<x-modal name="delete-modal" id="delete-modal" title="Delete answer" :show="false">
    <div class="p-4 rounded-lg relative">
        @include('questionnaires-campaign.hais.partials.delete-result')
    </div>
</x-modal>

<script src="{{ asset('js/tabulationTable.js') }}"></script>
<script>
    // Tabulation
    document.addEventListener('DOMContentLoaded', function() {
        setupPagination(
            'questionnaire-summary', // Table ID
            'Total users' // Personalized text for the total label
        );
    });

    // Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the charts for each questionnaire
        const questionnaires = @json($questionnaires);
        const totalAnswersPerQuestionnaire = @json($totalAnswersPerQuestionnaire);

        questionnaires.forEach(questionnaire => {
            const ctx = document.getElementById(`chart-${questionnaire.id}`).getContext('2d');
            const totalAnswers = @json($users->count());
            const totalAnswersGiven = totalAnswersPerQuestionnaire[questionnaire.id] || 0;
            const unanswered = totalAnswers - totalAnswersGiven;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Answered', 'Unanswered'],
                    datasets: [{
                        data: [totalAnswersGiven, unanswered],
                        backgroundColor: ['#4CAF50', '#F44336']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: `Answers for ${questionnaire.name}`
                        }
                    }
                }
            });
        });
    });
</script>
<script src="{{ asset('js/questionnaireAnalyse.js') }}"></script>
