<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twins campaign -->
            <a href="{{ route('digital-twins.defineUserPrompt') }}" class="cursor-pointer">
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
                <li><a href="{{ route('digital-twins.defineAttachPrompt') }}">@lang('digital-twin.defineThreatPromptShort')</a></li>
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
                        <span class="status"></span>
                        <span class="status active"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                    </div>
                </div>

                <p class="text-lg w-full">{{ $validatedData['title'] }}</p>
                <div class="space-y-4">
                    <p class="font-semibold text-xl">@lang('digital-twin.defineThreatPrompt')</p>
                    <p class="font-semibold text-sm">@lang('general.mandatoryField')</p>

                    <div>
                        @include('digital-twin.partials.type-of-prompt', [
                            'tooltipType' => 'tooltipThreat',
                        ])
                    </div>

                    <div>
                        <!-- LLM prompt -->
                        <label for="llmPrompt" class="text-lg font-medium text-sky-900">*@lang('digital-twin.threatPrompt.threatPrompt'):</label>
                        <textarea id="llmPrompt" name="llmPrompt"
                            class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm"
                            rows="10"></textarea>
                    </div>

                    <form id="generateSimulationForm" data-id="" action="{{ route('digital-twins.chooseEmail') }}"
                        method="POST">
                        @csrf
                        @method('post')

                        <input type="hidden" name="prompt" id="promptInput">
                        <input type="hidden" name="typeOfprompt" id="typeOfpromptInput">

                        <div class="flex flex-row justify-around items-center pt-6">
                            <div class="flex w-1/3">
                            </div>
                            <!-- Circles which indicates the steps of the creation -->
                            <div class="flex flex-row w-1/3 justify-center items-center">
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status active"></span>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const errorModal = document.getElementById('error-data-modal');

        const generateSimulationForm = document.getElementById('generateSimulationForm');

        const promptTypeSelect = document.getElementById("promptTypeSelect");

        const llmPromptTextarea = document.getElementById('llmPrompt');

        function updateTextarea() {
            const TYPE_OF_THREAT = "{{ $threatName }}";
            const EMAIL_CONTENT = " [EMAIL CONTENT] "

            let promptTemplate = "";
            switch (promptTypeSelect.value) {
                case "short":
                    promptTemplate =
                        `{{ __('digital-twin.threatPrompt.openingSentence') }} ${TYPE_OF_THREAT}\n{{ __('digital-twin.threatPrompt.shortFirstSentence') }}\n${EMAIL_CONTENT}\n{{ __('digital-twin.threatPrompt.shortLastSentence') }}`;
                    break;
                case "medium":
                    promptTemplate =
                        `{{ __('digital-twin.threatPrompt.openingSentence') }} ${TYPE_OF_THREAT}\n{{ __('digital-twin.threatPrompt.mediumFirstSentence') }}\n${EMAIL_CONTENT}\n{{ __('digital-twin.threatPrompt.mediumLastSentence') }}`;
                    break;
                case "detailed":
                    promptTemplate =
                        `{{ __('digital-twin.threatPrompt.openingSentence') }} ${TYPE_OF_THREAT}\n{{ __('digital-twin.threatPrompt.detailedFirstSentence') }}\n${EMAIL_CONTENT}\n{{ __('digital-twin.threatPrompt.detailedLastSentence') }}`;
                    break;
            }

            llmPromptTextarea.value = promptTemplate.trim();
        }

        if (promptTypeSelect) {
            promptTypeSelect.addEventListener('change', function() {
                updateTextarea();
            });
        }

        generateSimulationForm.addEventListener('submit', function(event) {
            event.preventDefault();

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

            document.getElementById('promptInput').value = llmPromptTextarea.value;
            document.getElementById('typeOfpromptInput').value = promptTypeSelect.value;

            this.submit();
        });

        function showErrorModal() {
            const errorModal = new CustomEvent('open-modal', {
                detail: 'error-data-modal'
            });
            window.dispatchEvent(errorModal);
        }

        updateTextarea();

    });
</script>
