<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to questionnaire campaign -->
            <a href="{{ route('questionnaires-campaign.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('questionnaires-campaign.index') }}">@lang('questionnaire-campaign.questionnaireCampaigns')</a></li>
                <li>/</li>
                <li>@lang('questionnaire-campaign.detailsCampaign.detail')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

                <p class="font-semibold text-xl">@lang('questionnaire-campaign.detailsCampaign.details')</p>

                @if ($questionnaireCampaign)
                    <div class="flex flex-col">
                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">
                                @lang('questionnaire-campaign.detailsCampaign.title'):
                            </p>
                            <p>{{ $questionnaireCampaign->title }}</p>
                        </div>

                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">
                                @lang('questionnaire-campaign.detailsCampaign.description'):
                            </p>
                            <p>{{ $questionnaireCampaign->description }}</p>
                        </div>

                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">
                                @lang('questionnaire-campaign.detailsCampaign.expirationDate'):
                            </p>
                            <p>{{ \Carbon\Carbon::parse($questionnaireCampaign->expiration_date)->format('d/m/Y') }}
                            </p>
                        </div>

                        <!-- Questionnaires -->
                        @if ($questionnaires->isNotEmpty())
                            <div class="flex flex-row  gap-2">
                                <p class="font-semibold">@lang('questionnaire-campaign.questionnaires'):</p>
                                <p>
                                    @foreach ($questionnaires as $index => $questionnaire)
                                        {{ $questionnaire->name }}{{ $index < $questionnaires->count() - 1 ? ',' : '' }}
                                    @endforeach
                                </p>
                            </div>

                            <!-- Users -->
                            @if ($users->isNotEmpty())
                                @include('layouts.partials.users-view', ['users' => $users])
                            @else
                                <div class="flex py-2 md:py-0 md:w-1/2 justify-start">
                                    <p class="font-semibold">@lang('phishing-campaign.detailsCampaign.users'):</p>
                                </div>
                                <div class="flex flex-col w-full item-center gap-2">
                                    <p class="text-center text-lg text-sky-700">@lang('questionnaire-campaign.detailsCampaign.noUsers')
                                    </p>
                                    <div class="flex justify-center">
                                        <a href="{{ route('questionnaire-campaign.users', ['questionnaireCampaign' => $questionnaireCampaign->id]) }}"
                                            id="chooseUsersButton" class="flex justify-end pt-2">
                                            <x-primary-button>@lang('general.chooseUsers')</x-primary-button>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="flex flex-col w-full item-center gap-2">
                                <p class="text-center text-lg text-sky-700">@lang('questionnaire-campaign.detailsCampaign.noQuestionnairesSelected')</p>
                                <div class="flex justify-center">
                                    <a href="{{ route('questionnaire-campaign.questionnaires', ['questionnaireCampaign' => $questionnaireCampaign->id]) }}"
                                        id="chooseQuestionnaireButton" class="flex justify-end pt-2">
                                        <x-primary-button>@lang('questionnaire-campaign.detailsCampaign.chooseQuestionnaires')</x-primary-button>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center text-xl">
                        <p>@lang('questionnaire-campaign.detailsCampaign.errorRetrievingCampaign')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chooseQuestionnaireButton = document.getElementById('chooseQuestionnaireButton');

        if (chooseQuestionnaireButton) {
            chooseQuestionnaireButton.addEventListener('click', function(event) {
                // Loading screen
                document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
            });
        }

        const chooseUsersButton = document.getElementById('chooseUsersButton');

        if (chooseUsersButton) {
            chooseUsersButton.addEventListener('click', function(event) {
                // Loading screen
                document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
            });
        }
    });
</script>
