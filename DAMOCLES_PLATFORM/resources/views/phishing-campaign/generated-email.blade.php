<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to phishing campaign -->
            <a href="{{ route('phishing-campaign.new') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('phishing-campaign.index') }}">@lang('phishing-campaign.phishingCampaigns')</a></li>
                <li>/</li>
                <li><a href="{{ route('phishing-campaign.new') }}">@lang('phishing-campaign.new')</a></li>
                <li>/</li>
                <li>@lang('phishing-campaign.generated.emailGenerated')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

                <div class="flex flex-row justify-around items-center pb-6">
                    <!-- Circles which indicates the steps of the creation -->
                    <div class="flex flex-row justify-center items-center py-2">
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status active"></span>
                        <span class="status"></span>
                    </div>
                </div>

                @if (count($bodies) == 0)
                    <!-- Error modal -->
                    <x-modal name="error-modal" id="error-modal" title="Not all emails have been generated!"
                        :show="true">
                        <div class="p-4 rounded-lg relative text-center text-sky-800">
                            <p class="text-xl font-semibold text-red-700 pb-8">
                                @lang('phishing-campaign.generated.errorGeneratedModal')
                            </p>

                            <div class="flex justify-end">
                                <x-secondary-button
                                    x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
                            </div>
                        </div>
                    </x-modal>
                @endif

                @if (count($bodies) > 0)
                    @if ($phishingCampaign->number_emails != count($bodies))
                        <!-- Error modal not all -->
                        <x-modal name="error-not-all-modal" id="error-not-all-modal"
                            title="Not all emails have been generated!" :show="true">
                            <div class="p-4 rounded-lg relative text-center text-sky-800">
                                <p class="text-xl font-semibold text-red-700 pb-8">
                                    @lang('phishing-campaign.generated.notAllGeneratedModal')
                                </p>

                                <div class="flex justify-end">
                                    <x-secondary-button
                                        x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
                                </div>
                            </div>
                        </x-modal>
                    @endif

                    @if ($phishingCampaign->number_emails == count($bodies))
                        <!-- Edit modal -->
                        <x-modal name="edit-modal" id="edit-modal" title="Edit emails generated!" :show="true">
                            <div class="p-4 rounded-lg relative text-center text-sky-800">
                                <p class="text-xl font-semibold pb-4">
                                    @lang('phishing-campaign.generated.okGeneratedModal')
                                </p>
                                <p class="pb-4">@lang('phishing-campaign.generated.editModal')</p>

                                <div class="flex justify-end">
                                    <x-secondary-button
                                        x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
                                </div>
                            </div>
                        </x-modal>
                    @endif
                @endif

                @if (isset($bodies) && count($bodies) > 0)
                    <p class="font-semibold text-xl pb-4">
                        @lang('phishing-campaign.generated.details'){{ count($bodies) > 1 ? __('phishing-campaign.generated.s') : '' }}
                        @lang('phishing-campaign.generated.generated') (3/4)
                    </p>
                    <div class="flex flex-col">
                        @if (isset($phishingCampaign->number_emails))
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">
                                    @lang('phishing-campaign.generated.email'){{ count($bodies) > 1 ? __('phishing-campaign.generated.s') : '' }}
                                    @lang('phishing-campaign.generated.toGenerate'):
                                </p>
                                <p>{{ $phishingCampaign->number_emails }}</p>
                            </div>
                        @endif

                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">
                                @lang('phishing-campaign.generated.email'){{ count($bodies) > 1 ? __('phishing-campaign.generated.s') : '' }}
                                @lang('phishing-campaign.generated.generated'):
                            </p>
                            <p>{{ count($bodies) }}</p>
                        </div>

                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">@lang('phishing-campaign.generated.topic'):</p>
                            <p>{{ $phishingCampaign->phishingTopic->description }}</p>
                        </div>

                        @if ($persuasions->isNotEmpty())
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('phishing-campaign.generated.persuasions'):</p>
                                <p>
                                    @foreach ($persuasions as $index => $persuasion)
                                        {{ $persuasion->description }}{{ $index < $persuasions->count() - 1 ? ',' : '' }}
                                    @endforeach
                                </p>
                            </div>
                        @endif

                        @if ($emotionalTriggers->isNotEmpty())
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('phishing-campaign.generated.emotionalTriggers'):</p>
                                <p>
                                    @foreach ($emotionalTriggers as $index => $trigger)
                                        {{ $trigger->description }}{{ $index < $emotionalTriggers->count() - 1 ? ',' : '' }}
                                    @endforeach
                                </p>
                            </div>
                        @endif

                        @if (isset($llm))
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('phishing-campaign.generated.llm'):</p>
                                <p>@lang('phishing-campaign.generated.provider'): {{ $llm->provider }}, @lang('phishing-campaign.generated.model'):
                                    {{ $llm->model }}</p>
                            </div>
                        @endif

                        <div class="pt-3 @if (count($bodies) > 1) shadow p-2 @endif">
                            <div class="flex flex-col">
                                @foreach ($bodies as $index => $body)
                                    <div class="email-container tab flex flex-col" style="display: none;">
                                        @if (count($bodies) > 1)
                                            <div class="flex flex-row gap-2">
                                                <p class="font-semibold">
                                                    @lang('phishing-campaign.generated.email'){{ count($bodies) > 1 ? __('phishing-campaign.generated.s') : '' }}
                                                    @lang('phishing-campaign.generated.generated') ({{ $index + 1 }}/{{ count($bodies) }}):
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex flex-row gap-2">
                                                <p class="font-semibold">
                                                    @lang('phishing-campaign.generated.email')
                                                </p>
                                            </div>
                                        @endif

                                        <div class="flex flex-col w-full md:flex-row gap-4">
                                            <!-- Emails -->
                                            <div class="flex flex-col w-full">
                                                <p class="font-semibold pt-2">*@lang('phishing-campaign.generated.subject'):</p>
                                                <textarea id="subject-{{ $index }}"
                                                    class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm tab-content"
                                                    rows="2">{{ $subjects[$index] }}</textarea>

                                                <p class="font-semibold pt-2">*@lang('phishing-campaign.generated.body'):</p>
                                                <textarea id="body-{{ $index }}"
                                                    class="w-full h-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm tab-content relative"
                                                    rows="10" onmouseup="showTooltip(event, this)" onkeyup="showTooltip(event, this)">{{ $body }}</textarea>

                                                <!-- Tooltip -->
                                                <div id="tooltip"
                                                    class="hidden absolute bg-sky-700 text-white text-sm rounded-md p-2 shadow-lg">
                                                    <button data-id="" x-data=""
                                                        @click="$dispatch('open-modal', 'rewrite-modal')">@lang('phishing-campaign.generated.rewrite')</button>
                                                </div>

                                                <!-- Rewrite modal -->
                                                <x-modal name="rewrite-modal" id="rewrite-modal" title="Rewrite modal!"
                                                    :show="false">
                                                    <div class="p-4 rounded-lg relative text-center text-sky-800">
                                                        <p class="text-xl font-semibold pb-4">
                                                            @lang('phishing-campaign.generated.rewrite')
                                                        </p>
                                                        <textarea id="rewriteTextArea"
                                                            class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm"></textarea>

                                                        <div class="flex justify-end gap-3">
                                                            <x-secondary-button
                                                                x-on:click="$dispatch('close')">@lang('general.cancel')</x-secondary-button>
                                                            <x-primary-button type="submit"
                                                                onclick="submitRewrite({{ $index }}, {{ $llm->id }})">@lang('general.confirm')</x-primary-button>
                                                        </div>
                                                    </div>
                                                </x-modal>
                                            </div>

                                            <!-- Explanation -->
                                            <div class="flex flex-col w-full">
                                                <p class="font-semibold pt-2">@lang('phishing-campaign.generated.explanation'):</p>
                                                <div id="explanation-{{ $index }}"
                                                    class="w-full h-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm tab-content bg-gray-100">
                                                    {!! \Parsedown::instance()->text(nl2br(e($explanations[$index]))) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if (count($bodies) > 1)
                                <div class="flex w-full items-center py-2 pb-4">
                                    <div class="flex w-1/3 justify-center">
                                        <x-primary-button type="button" id="prevBtn"
                                            onclick="nextPrev(-1)">@lang('phishing-campaign.generated.previousEmail')</x-primary-button>
                                    </div>
                                    <!-- Circles which indicate the steps of the bodies: -->
                                    <div class="flex w-1/3 justify-center flex-row">
                                        @for ($i = 0; $i < count($bodies); $i++)
                                            <span class="step"></span>
                                        @endfor
                                    </div>

                                    <div class="flex w-1/3 justify-center">
                                        <x-primary-button type="button" id="nextBtn"
                                            onclick="nextPrev(1)">@lang('phishing-campaign.generated.nextEmail')</x-primary-button>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <form id="saveEmailsForm" action="{{ route('phishing-campaign.save-emails') }}"
                            method="POST">
                            @csrf
                            @method('post')

                            <input type="hidden" name="phishingCampaignId" id="phishingCampaignId"
                                value="{{ $phishingCampaign->id }}">

                            @foreach ($subjects as $index => $subject)
                                <input type="hidden" name="subjects[]" id="hidden-subject-{{ $index }}"
                                    value="{{ $subject }}">
                            @endforeach

                            @foreach ($bodies as $index => $body)
                                <input type="hidden" name="bodies[]" id="hidden-body-{{ $index }}"
                                    value="{{ $body }}">
                            @endforeach

                            @foreach ($explanations as $index => $explanation)
                                <input type="hidden" name="explanations[]"
                                    id="hidden-explanation-{{ $index }}" value="{{ $explanation }}">
                            @endforeach

                            <div class="flex flex-row justify-around items-center pt-6">
                                <div class="flex w-1/3">
                                </div>
                                <!-- Circles which indicates the steps of the creation -->
                                <div class="flex flex-row w-1/3 justify-center items-center py-2">
                                    <span class="status"></span>
                                    <span class="status"></span>
                                    <span class="status active"></span>
                                    <span class="status"></span>
                                </div>
                                <div class="flex w-1/3 justify-center">
                                    <x-primary-button type="submit">@lang('general.chooseUsers')</x-primary-button>
                                </div>
                            </div>
                        </form>

                    </div>
                @else
                    <div class="text-center text-xl">
                        <p>@lang('phishing-campaign.generated.errorGenerate')</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="{{ asset('js/generatedEmail.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var saveEmailsForm = document.getElementById('saveEmailsForm');
        if (saveEmailsForm) {
            saveEmailsForm.addEventListener('submit', function(event) {
                var subjects = document.querySelectorAll('textarea[id^="subject-"]');
                subjects.forEach(function(textarea, index) {
                    var hiddenInput = document.getElementById('hidden-subject-' + index);
                    if (hiddenInput) {
                        hiddenInput.value = textarea.value;
                    }
                });

                var bodies = document.querySelectorAll('textarea[id^="body-"]');
                bodies.forEach(function(textarea, index) {
                    var hiddenInput = document.getElementById('hidden-body-' + index);
                    if (hiddenInput) {
                        hiddenInput.value = textarea.value;
                    }
                });

                // Loading screen
                document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
            });
        }
    });
</script>
