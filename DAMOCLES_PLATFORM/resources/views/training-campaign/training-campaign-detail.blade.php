<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to training campaign -->
            <a href="{{ route('training-campaign.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('training-campaign.index') }}">@lang('training-campaign.trainingCampaigns')</a></li>
                <li>/</li>
                <li>@lang('training-campaign.detailsCampaign.trainingCampaignDetails')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

                <p class="font-semibold text-xl">@lang('training-campaign.detailsCampaign.trainingCampaignDetails')</p>

                @if ($trainingCampaign)
                    <div class="flex flex-col">

                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">@lang('training-campaign.detailsCampaign.title'):</p>
                            <p>{{ $trainingCampaign->title }}</p>
                        </div>

                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">@lang('training-campaign.detailsCampaign.description'):</p>
                            <p>{{ $trainingCampaign->description }}</p>
                        </div>

                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">
                                @lang('training-campaign.detailsCampaign.expirationDate'):
                            </p>
                            <p>{{ \Carbon\Carbon::parse($trainingCampaign->expiration_date)->format('d/m/Y') }}</p>
                        </div>

                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">@lang('training-campaign.detailsCampaign.threat'):</p>
                            <p>{{ $trainingCampaign->threat->name }}</p>
                        </div>

                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">@lang('training-campaign.detailsCampaign.type'):</p>
                            <p>{{ $trainingCampaign->type }}</p>
                        </div>

                        <div>
                            <!-- Prompt selected -->
                            @if (!empty($trainingCampaign->llm) && !empty($trainingCampaign->prompt))
                                <div class="flex flex-row gap-2">
                                    <p class="font-semibold">@lang('training-campaign.detailsCampaign.llm'):</p>
                                    <p>@lang('training-campaign.detailsCampaign.provider'): {{ $trainingCampaign->llm->provider }},
                                        @lang('training-campaign.detailsCampaign.model'):
                                        {{ $trainingCampaign->llm->model }}</p>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <p class="font-semibold">@lang('training-campaign.detailsCampaign.prompt'):</p>
                                    <textarea id="prompt" name="prompt"
                                        class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm" rows="4"
                                        readonly>{{ $trainingCampaign->prompt }}</textarea>
                                </div>

                                <!-- Users selected -->
                                @if (count($users) == 0)
                                    <div class="flex py-2 md:py-0 md:w-1/2 justify-start">
                                        <p class="font-semibold">@lang('traning-campaign.detailsCampaign.users'):</p>
                                    </div>
                                    <div class="flex flex-col w-full item-center pt-2 gap-2">
                                        <p class="text-center text-lg text-sky-700">@lang('training-campaign.detailsCampaign.noUsers')</p>

                                        <div class="flex justify-center">
                                            <a href="{{ route('training-campaign.users', ['trainingCampaign' => $trainingCampaign->id]) }}"
                                                id="chooseUserButton" class="flex justify-end pt-2">
                                                <x-primary-button>@lang('general.chooseUsers')</x-primary-button>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    @include('layouts.partials.users-view', ['users' => $users])
                                @endif
                            @else
                                <!-- Generate the training -->
                                <div class="flex flex-col w-full item-center pt-2 gap-2">
                                    <p class="text-center text-lg text-sky-700">
                                        @lang('training-campaign.detailsCampaign.noTrainings')
                                    </p>
                                    <div class="flex justify-center">
                                        <a href="{{ route('training-campaign.simulation', ['trainingCampaign' => $trainingCampaign->id]) }}"
                                            id="generateTrainingsButton" class="flex justify-end pt-2">
                                            <x-primary-button>@lang('training-campaign.detailsCampaign.generate')</x-primary-button>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center text-xl">
                        <p>@lang('training-campaign.detailsCampaign.errorRetrievingCampaign')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const generateButton = document.getElementById('generateTrainingsButton');

        if (generateButton) {
            generateButton.addEventListener('click', function(event) {
                // Loading screen
                document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
            });
        }

        const chooseUserButton = document.getElementById('chooseUserButton');

        if (chooseUserButton) {
            chooseUserButton.addEventListener('click', function(event) {
                // Loading screen
                document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
            });
        }
    });
</script>
