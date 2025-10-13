<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twins campaign -->
            <a href="{{ route('digital-twins.new') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('digital-twins.index') }}">@lang('digital-twin.digitalTwinsCampaigns')</a></li>
                <li>/</li>
                <li><a href="{{ route('digital-twins.new') }}">@lang('digital-twin.new')</a></li>
                <li>/</li>
                <li><a href="{{ route('digital-twins.defineUserPrompt') }}">@lang('digital-twin.defineUserPromptShort')</a></li>
            </ul>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white sm:overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

                <div class="flex flex-row justify-around items-center pb-6">
                    <!-- Circles which indicates the steps of the creation -->
                    <div class="flex flex-row justify-center items-center">
                        <span class="status"></span>
                        <span class="status active"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                    </div>
                </div>

                <p class="text-lg w-full">{{ $validatedData['title'] }}</p>
                <div class="space-y-4">
                    <p class="font-semibold text-xl">@lang('digital-twin.defineUserPrompt')</p>
                    <p class="font-semibold text-sm">@lang('general.mandatoryField')</p>

                    <div>
                        @include('phishing-campaign.partials.phishing-llm')
                    </div>

                    <div>
                        @include('digital-twin.partials.type-of-prompt', ['tooltipType' => 'tooltip'])
                    </div>

                    <div>
                        @include('digital-twin.partials.demographic-attributes')
                    </div>

                    <div>
                        @include('digital-twin.partials.human-factors')
                    </div>

                    <div>
                        <!-- LLM prompt -->
                        <label for="llmPrompt"
                            class="block text-lg font-medium text-sky-900">*@lang('digital-twin.profilePrompt.profilePrompt'):</label>
                        <textarea id="llmPrompt" name="llmPrompt"
                            class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm"
                            rows="10"></textarea>
                    </div>

                    <form id="generateSimulationForm" data-id=""
                        action="{{ route('digital-twins.defineAttachPrompt') }}" method="POST">
                        @csrf
                        @method('post')
                        <input type="hidden" name="name" id="nameInput">
                        <input type="hidden" name="demographics" id="demographicsInput">
                        <input type="hidden" name="humanfactors" id="humanfactorsInput">
                        <input type="hidden" name="prompt" id="promptInput">
                        <input type="hidden" name="typeOfprompt" id="typeOfpromptInput">
                        <input type="hidden" name="llmId" id="llmIdInput">

                        <div class="flex flex-row justify-around items-center pt-6">
                            <div class="flex w-1/3">
                            </div>
                            <!-- Circles which indicates the steps of the creation -->
                            <div class="flex flex-row w-1/3 justify-center items-center">
                                <span class="status"></span>
                                <span class="status active"></span>
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status"></span>
                            </div>
                            <div class="flex w-1/3 justify-center">
                                <x-primary-button id="generateEmail" type="submit">@lang('general.next')</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-loading-screen />
</x-app-layout>

<!-- Error modal -->
<!-- Error data modal -->
<x-modal name="error-data-modal" id="error-data-modal" title="Error data" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('general.errorData')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
    </div>
</x-modal>

<!-- Error llm modal -->
<x-modal name="error-llm-modal" id="error-llm-modal" title="Error llm" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('phishing-campaign.newPhishingCampaign.noLLMs')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
    </div>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const llmSelected = document.getElementById('llmSelect');
        const errorModal = document.getElementById('error-data-modal');
        const generateSimulationForm = document.getElementById('generateSimulationForm');
        const demographicButtons = document.querySelectorAll('.demographic-checkbox');
        const humanFactorsButtons = document.querySelectorAll('.humanFactors-checkbox');
        const promptTypeSelect = document.getElementById("promptTypeSelect");
        const llmPromptTextarea = document.getElementById('llmPrompt');

        let selectedDemographics = [];
        let selectedHumanFactors = [];

        function updateTextarea() {

            const selectedDemographicsDescriptions = Array.from(document.querySelectorAll(
                '.demographic-checkbox:checked')).map(
                checkbox => checkbox.getAttribute('data-name')
            );
            const selectedHumanFactorsDescriptions = Array.from(document.querySelectorAll(
                '.humanFactors-checkbox:checked')).map(
                checkbox => checkbox.getAttribute('data-name')
            );

            const demographicsList = selectedDemographicsDescriptions.map(
                item => `- ${item} = [${item.toUpperCase()}_VALUE]`
            ).join('\n');

            const humanFactorsList = selectedHumanFactorsDescriptions.map(
                item => `- ${item} = [${item.toUpperCase()}_VALUE]`
            ).join('\n');


            const LIST_FEATURES = `${demographicsList}\n${humanFactorsList}`.trim();

            let promptTemplate = "";
            switch (promptTypeSelect.value) {
                case "short":
                    promptTemplate =
                        `{{ __('digital-twin.profilePrompt.openingSentence') }}\n{{ __('digital-twin.profilePrompt.shortFirstSentence') }}\n${LIST_FEATURES}\n{{ __('digital-twin.profilePrompt.shortLastSentence') }}`;
                    break;
                case "medium":
                    promptTemplate =
                        `{{ __('digital-twin.profilePrompt.openingSentence') }}\n{{ __('digital-twin.profilePrompt.mediumFirstSentence') }}\n${LIST_FEATURES}\n{{ __('digital-twin.profilePrompt.mediumLastSentence') }}`;
                    break;
                case "detailed":
                    promptTemplate =
                        `{{ __('digital-twin.profilePrompt.openingSentence') }}\n{{ __('digital-twin.profilePrompt.detailedFirstSentence') }}\n${LIST_FEATURES}\n{{ __('digital-twin.profilePrompt.detailedLastSentence') }}`;
                    break;
            }

            llmPromptTextarea.value = promptTemplate.trim();
        }

        document.getElementById('humanFactorsSelectAllBtn').addEventListener('click', function() {
            selectedHumanFactors = [];
            humanFactorsButtons.forEach(checkbox => {
                selectedHumanFactors.push(checkbox.getAttribute('data-name'));
            });
            updateTextarea();
        });

        document.getElementById('humanFactorsClearAllBtn').addEventListener('click', function() {
            selectedHumanFactors = [];
            updateTextarea();
        });

        document.getElementById('demographicSelectAllBtn').addEventListener('click', function() {
            selectedDemographics = [];
            demographicButtons.forEach(checkbox => {
                selectedDemographics.push(checkbox.getAttribute('data-name'));
            });
            updateTextarea();
        });

        document.getElementById('demographicClearAllBtn').addEventListener('click', function() {
            selectedDemographics = [];
            updateTextarea();
        });

        if (demographicButtons) {
            demographicButtons.forEach(checkbox => {
                checkbox.addEventListener('click', function() {
                    const value = this.getAttribute('data-name');
                    if (selectedDemographics.includes(value)) {
                        selectedDemographics.splice(selectedDemographics.indexOf(value), 1);
                    } else {
                        selectedDemographics.push(value);
                    }
                    updateTextarea();
                });
            });
        }

        if (humanFactorsButtons) {
            humanFactorsButtons.forEach(checkbox => {
                checkbox.addEventListener('click', function() {
                    const value = this.getAttribute('data-name');
                    if (selectedHumanFactors.includes(value)) {
                        selectedHumanFactors.splice(selectedHumanFactors.indexOf(value), 1);
                    } else {
                        selectedHumanFactors.push(value);
                    }
                    updateTextarea();
                });
            });
        }

        if (promptTypeSelect) {
            promptTypeSelect.addEventListener('change', function() {
                updateTextarea();
            });
        }

        generateSimulationForm.addEventListener('submit', function(event) {
            event.preventDefault();

            if (!document.getElementById('llmSelect')) {
                showErrorLLMModal();
                return;
            }

            if (selectedDemographics.length === 0 || selectedHumanFactors.length === 0) {
                showErrorModal();
                return;
            }

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

            const selectedDemographicIds = Array.from(document.querySelectorAll(
                '.demographic-checkbox:checked')).map(checkbox => checkbox.value);
            const selectedHumanFactorIds = Array.from(document.querySelectorAll(
                '.humanFactors-checkbox:checked')).map(checkbox => checkbox.value);

            document.getElementById('demographicsInput').value = selectedDemographicIds.join(',');
            document.getElementById('humanfactorsInput').value = selectedHumanFactorIds.join(',');
            document.getElementById('promptInput').value = llmPromptTextarea.value;
            document.getElementById('typeOfpromptInput').value = promptTypeSelect.value;
            document.getElementById('llmIdInput').value = llmSelected.value;

            this.submit();
        });

        function showErrorModal() {
            const errorModal = new CustomEvent('open-modal', {
                detail: 'error-data-modal'
            });
            window.dispatchEvent(errorModal);
        }

        function showErrorLLMModal() {
            const errorModal = new CustomEvent('open-modal', {
                detail: 'error-llm-modal'
            });
            window.dispatchEvent(errorModal);
        }

        updateTextarea();
    });
</script>
