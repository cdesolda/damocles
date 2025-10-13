<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to questionnaires campaign -->
            <a href="{{ url()->previous() }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('questionnaires-campaign.index') }}">@lang('questionnaire-campaign.questionnairesCampaigns')</a></li>
                <li>/</li>
                <li><a>@lang('questionnaire-campaign.stp-ii-b.stpIIB')</a></li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-sky-900">
                    <p class="text-2xl font-semibold text-center">@lang('questionnaire-campaign.stp-ii-b.result')</p>
                    @php
                        $questionsPerTab = 9;
                    @endphp

                    <!-- Scales results -->
                    @if (isset($scales))
                        <div id="scalesResult" class="p-4">
                            <p class="text-xl font-semibold">Scales:</p>
                            <ul class="list-disc pl-5 pr-5 mt-2">
                                @foreach ($scales as $scale => $value)
                                    @php
                                        // Calculate the progress percentage (value as percentage of total questions)
                                        $maxValue = 7;
                                        $percentage = ($value / $maxValue) * 100;
                                    @endphp
                                    <li class="mb-4 flex items-center space-x-4">
                                        <span class="font-medium flex-shrink-0 w-1/4">{{ $scale }}:</span>

                                        <!-- Progress bar -->
                                        <div class="flex-1 bg-gray-300 rounded-full h-2.5">
                                            <div class="bg-sky-800 h-2.5 rounded-full"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>

                                        <!-- Display value and percentage -->
                                        <span class="ml-4 font-medium w-1/4 text-right">{{ number_format($value, 2) }}
                                            ({{ number_format($percentage, 2) }}%)</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Answers -->
                    <div>
                        @foreach (array_chunk(trans('questionnaire-campaign.stp-ii-b.question'), $questionsPerTab) as $tabQuestions)
                            <div class="tab hidden">
                                @foreach ($tabQuestions as $index => $question)
                                    <div class="py-3 flex flex-col border-b-2">
                                        <!-- Question -->
                                        <p class="text-center font-medium py-3">{{ $question['text'] }}</p>

                                        <!-- Answer Options -->
                                        <div
                                            class="flex flex-col md:flex-row items-top md:items-center w-full md:justify-around">
                                            @for ($i = 1; $i <= 7; $i++)
                                                <div class="flex flex-row md:flex-col items-center">
                                                    <input disabled class="checked:bg-sky-800" type="radio"
                                                        id="q{{ $loop->parent->iteration * $questionsPerTab - ($questionsPerTab - $loop->iteration) }}_{{ $i }}"
                                                        name="q{{ $loop->parent->iteration * $questionsPerTab - ($questionsPerTab - $loop->iteration) }}"
                                                        value="{{ $i }}"
                                                        {{ isset($answers['q' . ($loop->parent->iteration * $questionsPerTab - ($questionsPerTab - $loop->iteration))]) && $answers['q' . ($loop->parent->iteration * $questionsPerTab - ($questionsPerTab - $loop->iteration))] == $i ? 'checked' : '' }}>
                                                    <label
                                                        for="q{{ $loop->parent->iteration * $questionsPerTab - ($questionsPerTab - $loop->iteration) }}_{{ $i }}"
                                                        class="italic">
                                                        @lang("questionnaire-campaign.stp-ii-b.scale.$i")
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                </div>

                <div class="flex w-full items-center pb-4">
                    <div class="flex w-1/3 justify-center">
                        <x-primary-button type="button" id="prevBtn"
                            onclick="nextPrev(-1)">Previous</x-primary-button>
                    </div>

                    <!-- Circles which indicate the steps of the form: -->
                    <div class="flex w-1/3 justify-center flex-row">
                        @for ($i = 0; $i < ceil(count(trans('questionnaire-campaign.stp-ii-b.question')) / $questionsPerTab); $i++)
                            <span class="step"></span>
                        @endfor
                    </div>

                    <div class="flex w-1/3 justify-center">
                        <x-primary-button type="button" id="nextBtn" onclick="nextPrev(1)">Next</x-primary-button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="{{ asset('js/questionnaireResult.js') }}"></script>
