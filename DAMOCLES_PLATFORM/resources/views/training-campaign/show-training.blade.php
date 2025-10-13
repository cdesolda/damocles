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
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

                @if ($error)
                    <!-- Error modal -->
                    <x-modal name="error-modal" id="error-modal" title="Not all trainings have been generated!"
                        :show="true">
                        <div class="p-4 rounded-lg relative text-center text-sky-800">
                            <p class="text-xl font-semibold text-red-700 pb-8">
                                @lang('training-campaign.generated.errorGeneratedModal')
                            </p>
                            <p class="text-xl font-semibold text-red-700 pb-8">
                                {{ $status }}
                            </p>

                            <div class="flex justify-end">
                                <x-secondary-button
                                    x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
                            </div>
                        </div>
                    </x-modal>
                    <div class="text-center text-xl">
                        <p>@lang('training-campaign.generated.errorGenerate')</p>
                    </div>
                @elseif (isset($userTrainingCampaign) && !empty($userTrainingCampaign))
                    <p class="font-semibold text-xl">
                        {{ $trainingCampaign->title }}
                    </p>

                    <div class="pt-2" id="training">
                        {!! \Parsedown::instance()->text(nl2br(e($userTrainingCampaign->training))) !!}
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button id="checkReadingTime">@lang('general.next')</x-primary-button>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<!-- Finish message -->
<x-modal name="finish-modal" id="finish-modal" title="Finish modal" :show="false">
    <div class="p-6 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">@lang('training-campaign.generated.alertReadingPass')</p>

        <div class="flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
            <a
                href="{{ route('training-campaign.generateTestUserTraining', ['userTrainingCampaign' => $userTrainingCampaign->id]) }}">
                <x-primary-button id="generateTestButton"
                    x-on:click="$dispatch('close')">@lang('training-campaign.generated.generateTestUserTraining')</x-primary-button>
            </a>
        </div>
    </div>
</x-modal>

<!-- Finish error message -->
<x-modal name="finish-error-modal" id="finish-error-modal" title="Finish error modal" :show="false">
    <div class="p-6 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">@lang('training-campaign.generated.alertReadingNotPass')</p>

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let startTime = Date.now();

        function getReadingTime(text, wpm = 250) {
            let words = text.trim().split(/\s+/).length;
            return (words * 60) / wpm;
        }

        document.getElementById("checkReadingTime").addEventListener("click", function() {
            let text = document.getElementById("training").innerText;
            let minTime = getReadingTime(text);
            let elapsedTime = (Date.now() - startTime) / 1000;

            if (elapsedTime < minTime) {
                const finishErrorModalEvent = new CustomEvent('open-modal', {
                    detail: 'finish-error-modal'
                });
                window.dispatchEvent(finishErrorModalEvent);
            } else {
                const finishModalEvent = new CustomEvent('open-modal', {
                    detail: 'finish-modal'
                });
                window.dispatchEvent(finishModalEvent);
            }
        });

        function showLoadingOverlay() {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        }

        document.getElementById("generateTestButton").addEventListener("click", showLoadingOverlay);

        if (document.getElementById("closeButtonFinish")) {
            document.getElementById("closeButtonFinish").addEventListener("click", showLoadingOverlay);
        }

    });
</script>
