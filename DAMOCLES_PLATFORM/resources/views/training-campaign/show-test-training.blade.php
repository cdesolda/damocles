<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to training campaign -->
            <a href="{{ route('training-campaign.generateUserTraining', ['user' => $userTrainingCampaign->user_id, 'trainingCampaign' => $userTrainingCampaign->training_campaign_id]) }}"
                class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('training-campaign.index') }}">@lang('training-campaign.trainingCampaigns')</a></li>
                <li>/</li>
                <li><a
                        href="{{ route('training-campaign.generateUserTraining', ['user' => $userTrainingCampaign->user_id, 'trainingCampaign' => $userTrainingCampaign->training_campaign_id]) }}">@lang('training-campaign.generated.generateUserTraining')</a>
                </li>
                <li>/</li>
                <li><a
                        href="{{ route('training-campaign.generateTestUserTraining', ['userTrainingCampaign' => $userTrainingCampaign->id]) }}">@lang('training-campaign.generated.generateTestUserTraining')</a>
                </li>
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
                @else
                    <!-- Questionnaire -->
                    <div>
                        <p class="text-xl font-bold">@lang('training-campaign.generated.generateTestUserTraining')</p>

                        @foreach ($questions['questions'] as $index => $question)
                            <div class="py-3 flex flex-col border-b-2">
                                <p class="font-medium py-3">{{ $question['question'] }}</p>
                                <div class="flex flex-col w-full">
                                    @foreach ($question['choices'] as $choiceIndex => $choice)
                                        <div class="flex flex-row items-center gap-4">
                                            <input class="cursor-pointer checked:bg-sky-800 hover:bg-sky-600"
                                                type="radio" id="q{{ $index + 1 }}_{{ $choiceIndex + 1 }}"
                                                name="q{{ $index + 1 }}" value="{{ $choice }}" required>
                                            <label for="q{{ $index + 1 }}_{{ $choiceIndex + 1 }}">
                                                {{ $choice }}
                                            </label>
                                        </div>
                                    @endforeach
                                    <p id ="c_a_{{ $index + 1 }}" class="hidden"
                                        value="{{ $question['correct_answer'] }}"></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-end py-4">
                        <x-primary-button id="checkAnswer">@lang('general.finish')</x-primary-button>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>

<!-- Not answer message -->
<x-modal name="not-answer-modal" id="not-answer-modal" title="Not answer modal" :show="false">
    <div class="p-6 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">@lang('training-campaign.generated.notAnswerTest')</p>

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Pass message -->
<x-modal name="pass-modal" id="pass-modal" title="Pass modal" :show="false">
    <div class="p-6 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">@lang('training-campaign.generated.passTest')</p>

        <div class="flex justify-end">
            <a
                href="{{ route('training-campaign.doneUserTraining', ['userTrainingCampaign' => $userTrainingCampaign->id]) }}">
                <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
            </a>
        </div>
    </div>
</x-modal>

<!-- Not pass message -->
<x-modal name="not-pass-modal" id="not-pass-modal" title="Not pass modal" :show="false">
    <div class="p-6 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">@lang('training-campaign.generated.notpassTest')</p>

        <div class="flex justify-end">
            <a
                href="{{ route('training-campaign.generateUserTraining', ['user' => $userTrainingCampaign->user_id, 'trainingCampaign' => $userTrainingCampaign->training_campaign_id]) }}">
                <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
            </a>
        </div>
    </div>
</x-modal>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkButton = document.getElementById("checkAnswer");

        checkButton.addEventListener("click", function(event) {
            event.preventDefault();

            let correctAnswers = 0;
            let totalQuestions = document.querySelectorAll("[id^='c_a_']").length;
            let answeredQuestions = 0;

            for (let i = 1; i <= totalQuestions; i++) {
                const selected = document.querySelector(`input[name="q${i}"]:checked`);

                if (selected) {
                    answeredQuestions++;
                    const correctAnswer = document.getElementById(`c_a_${i}`).getAttribute("value");
                    if (selected.value === correctAnswer) {
                        correctAnswers++;
                    }
                }
            }

            if (answeredQuestions < totalQuestions) {
                const notAnswerModalEvent = new CustomEvent("open-modal", {
                    detail: "not-answer-modal"
                });
                window.dispatchEvent(notAnswerModalEvent);
                return;
            }

            if (correctAnswers >= 4) {
                const passModalEvent = new CustomEvent("open-modal", {
                    detail: "pass-modal"
                });
                window.dispatchEvent(passModalEvent);
            } else {
                const notPassModalEvent = new CustomEvent("open-modal", {
                    detail: "not-pass-modal"
                });
                window.dispatchEvent(notPassModalEvent);
            }
        });
    });
</script>
