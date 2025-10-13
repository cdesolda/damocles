<section>
    @if (auth()->user()->is_accept)
        @if (auth()->user()->is_active)
            <div class="flex flex-wrap justify-center gap-x-24">
                <div class="flex flex-col justify-center items-center pt-2 pb-6">
                    <p class="font-semibold text-lg text-center">@lang('dashboard.evaluator.questionnairesCampaign')</p>
                    <a href="{{ route('questionnaires-campaign.index') }}">
                        <p class="font-semibold gap-x-6">
                            <span class="material-symbols-outlined text-5xl align-middle">dynamic_form</span>
                            <span>{{ $totalQuestionnaireCampaigns }}</span>
                        </p>
                    </a>
                </div>

                <div class="flex flex-col justify-center items-center pt-2 pb-6">
                    <p class="font-semibold text-lg text-center">@lang('dashboard.evaluator.trainingCampaign')</p>
                    <a href="{{ route('training-campaign.index') }}">
                        <p class="font-semibold gap-x-6">
                            <span class="material-symbols-outlined text-5xl align-middle">library_books</span>
                            <span>{{ $totalTrainingCampaigns }}</span>
                        </p>
                    </a>
                </div>
            </div>
        @else
            <div class="text-sky-800 text-center">
                <p class="text-center text-lg font-semibold">@lang('dashboard.accountNotActive')</p>
            </div>
        @endif
    @else
        <div class="text-sky-800 text-center">
            <p class="text-center text-lg font-semibold">@lang('dashboard.accountNotAccept')</p>
        </div>
    @endif
</section>
