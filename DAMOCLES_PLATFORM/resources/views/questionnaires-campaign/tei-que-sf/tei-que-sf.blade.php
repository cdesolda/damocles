<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to questionnaires -->
            <a href="{{ url()->previous() }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                @if (isset($questionnaireCampaignId) && isset($questionnaireId))
                    <li><a href="{{ route('questionnaires-campaign.index') }}">@lang('questionnaire-campaign.questionnaires')</a></li>
                    <li>/</li>
                    <li><a
                            href="{{ route('trait-emotional-intelligence.index', ['questionnaireCampaign' => $questionnaireCampaignId, 'questionnaire' => $questionnaireId]) }}">@lang('questionnaire-campaign.tei-que-sf.teiQueSF')</a>
                    </li>
                @else
                    <li><a href="{{ route('questionnaires') }}">@lang('questionnaire-campaign.questionnaires')</a></li>
                    <li>/</li>
                    <li>@lang('questionnaire-campaign.tei-que-sf.teiQueSF')</li>
                @endif
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-sky-900">
                    <!-- Questionnaire -->
                    <form class="pb-4" action="{{ route('trait-emotional-intelligence.create') }}" method="POST">
                        @csrf
                        @method('post')

                        @if (isset($questionnaireCampaignId) && isset($questionnaireId))
                            <input type="hidden" name="questionnaireCampaignId" id="questionnaireCampaignId"
                                value="{{ $questionnaireCampaignId }}">
                            <input type="hidden" name="questionnaireId" id="questionnaireId"
                                value="{{ $questionnaireId }}">
                        @endif

                        <p class="text-2xl font-semibold text-center">@lang('questionnaire-campaign.tei-que-sf.teiQueSFQuestionnaire')</p>
                        @php
                            $questionsPerTab = 9;
                        @endphp

                        <div>
                            @foreach (array_chunk(trans('questionnaire-campaign.tei-que-sf.question'), $questionsPerTab) as $tabQuestions)
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
                                                        <input
                                                            class="cursor-pointer checked:bg-sky-800 hover:bg-sky-600"
                                                            type="radio"
                                                            id="q{{ $loop->parent->iteration * $questionsPerTab - ($questionsPerTab - $loop->iteration) }}_{{ $i }}"
                                                            name="q{{ $loop->parent->iteration * $questionsPerTab - ($questionsPerTab - $loop->iteration) }}"
                                                            value="{{ $i }}" required>
                                                        <label
                                                            for="q{{ $loop->parent->iteration * $questionsPerTab - ($questionsPerTab - $loop->iteration) }}_{{ $i }}"
                                                            class="italic">
                                                            @lang("questionnaire-campaign.tei-que-sf.scale.$i")
                                                        </label>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="flex w-full items-center py-4">
                            <div class="flex w-1/3 justify-center">
                                <x-primary-button type="button" id="prevBtn"
                                    onclick="nextPrev(-1)">@lang('general.previous')</x-primary-button>
                            </div>

                            <!-- Circles which indicate the steps of the form: -->
                            <div class="flex w-1/3 justify-center flex-row">
                                @for ($i = 0; $i < ceil(count(trans('questionnaire-campaign.tei-que-sf.question')) / $questionsPerTab); $i++)
                                    <span class="step"></span>
                                @endfor
                            </div>

                            <div class="flex w-1/3 justify-center">
                                <x-primary-button type="button" id="nextBtn"
                                    onclick="nextPrev(1)">@lang('general.next')</x-primary-button>

                                @if (Auth::user() && Auth::user()->role == 'User')
                                    <x-primary-button id="submit" class="hidden"
                                        type="submit">@lang('questionnaire-campaign.tei-que-sf.submit')</x-primary-button>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Error modal -->
<x-modal name="error-modal" id="error-modal" title="Compile all the questions!" :show="false">
    <div class="p-4 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('questionnaire-campaign.tei-que-sf.compileError')</p>
        <x-primary-button x-on:click="$dispatch('close')">@lang('general.close')</x-primary-button>
    </div>
</x-modal>

<script src="{{ asset('js/questionnaireForm.js') }}"></script>
