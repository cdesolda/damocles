<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to phishing campaign -->
            <a href="{{ route('phishing-campaign.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('phishing-campaign.index') }}">@lang('phishing-campaign.phishingCampaigns')</a></li>
                <li>/</li>
                <li><a href="{{ route('phishing-campaign.new') }}">@lang('phishing-campaign.new')</a></li>
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
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="font-semibold text-xl">@lang('phishing-campaign.newPhishingCampaign.newCampaignDetailsTitle')</p>
                    <p class="font-semibold text-sm">@lang('general.mandatoryField')</p>

                    <div id="wizard">
                        <div class="wizard-step space-y-4" data-step="1">

                            <div class="space-y-4">
                                <div id="emailsDiv">
                                    <p class="text-lg font-medium text-sky-900">
                                        *@lang('phishing-campaign.newPhishingCampaign.numbersEmail'):
                                    </p>
                                    <input type="number" id="numberEmails" name="numberEmails" min="1"
                                        max="10" step="1" value="1"
                                        class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md"
                                        required>
                                </div>

                                <div id="timingDiv" class="hidden">
                                    <p class="text-lg font-medium text-sky-900">
                                        *@lang('phishing-campaign.newPhishingCampaign.timing')
                                    </p>

                                    <input type="number" id="timingEmail" name="timingEmail" min="1"
                                        max="180" step="1" value="1"
                                        class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md"
                                        required>
                                    <span id="timingUnit">day</span>
                                </div>
                            </div>

                            <div>
                                @include('phishing-campaign.partials.topic-partials.phishing-topic')
                            </div>
                        </div>

                        <div class="wizard-step hidden" data-step="2">
                            @include('phishing-campaign.partials.persuasion-partials.phishing-persuasion')
                        </div>

                        <div class="wizard-step hidden" data-step="3">
                            @include('phishing-campaign.partials.emotional-trigger-partials.phishing-emotional-trigger')
                        </div>

                        <div class="wizard-step hidden" data-step="4">
                            @include('phishing-campaign.partials.phishing-llm')
                        </div>
                    </div>

                    <!-- Example Email -->
                    <div id="emailExampleContainer" class="hidden">
                        <p class="text-lg font-medium text-sky-900">@lang('phishing-campaign.newPhishingCampaign.emailExample'):</p>

                        <label for="emailSubjectExample"
                            class="block font-medium text-sky-900 py-2">@lang('general.subject'):</label>
                        <div id="emailSubjectExample"
                            class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm">
                        </div>

                        <label for="emailBodyExample"
                            class="block font-medium text-sky-900 py-2">@lang('general.body'):</label>
                        <div id="emailBodyExample"
                            class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm">
                        </div>
                    </div>
                </div>

                <form id="generateCampaignForm" data-id="" action="{{ route('phishing-campaign.save-details') }}"
                    method="POST">
                    @csrf
                    @method('post')

                    <input type="hidden" name="id" id="id" value="{{ $phishingCampaign->id }}">
                    <input type="hidden" name="numberEmails" id="numberEmailsInput">
                    <input type="hidden" name="timingEmail" id="timingEmailInput">
                    <input type="hidden" name="topicId" id="topicIdInput">
                    <input type="hidden" name="emotionalTriggers" id="emotionalTriggersInput">
                    <input type="hidden" name="persuasions" id="persuasionsInput">
                    <input type="hidden" name="llmId" id="llmIdInput">
                    <input type="hidden" name="emailSubjectExample" id="emailSubjectExampleInput">
                    <input type="hidden" name="emailBodyExample" id="emailBodyExampleInput">

                    <div class="flex flex-row justify-around items-center pt-6">
                        <div class="flex w-1/3 justify-center">
                            <x-primary-button id="prevBtn" class="hidden"
                                type="button">@lang('general.previous')</x-primary-button>
                        </div>

                        <!-- Circles which indicates the steps of the creation -->
                        <div class="flex flex-row w-1/3 justify-center items-center">
                            <span class="status"></span>
                            <span class="status active"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                        </div>

                        <div class="flex w-1/3 justify-center">
                            <x-primary-button id="nextBtn" type="button">@lang('general.next')</x-primary-button>

                            <x-primary-button class="hidden" id="generateEmail"
                                type="submit">@lang('phishing-campaign.generate')</x-primary-button>
                        </div>
                    </div>
                </form>

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

<script src="https://cdn.jsdelivr.net/npm/diff@5.0.0/dist/diff.min.js"></script>

<script>
    // Wizard
    document.addEventListener("DOMContentLoaded", function() {
        let currentStep = 1;
        const steps = document.querySelectorAll(".wizard-step");
        const nextBtn = document.getElementById("nextBtn");
        const prevBtn = document.getElementById("prevBtn");
        const generateEmailBtn = document.getElementById("generateEmail");
        const emailExampleContainer = document.getElementById('emailExampleContainer');

        function updateSteps() {
            steps.forEach(step => {
                step.classList.add("hidden");
                if (parseInt(step.dataset.step) === currentStep) {
                    step.classList.remove("hidden");
                }
            });

            prevBtn.classList.toggle("hidden", currentStep === 1);
            emailExampleContainer.classList.toggle("hidden", currentStep === 1);

            if (currentStep === steps.length) {
                nextBtn.classList.add("hidden");
                generateEmailBtn.classList.remove("hidden");
            } else {
                nextBtn.classList.remove("hidden");
                generateEmailBtn.classList.add("hidden");
            }
        }

        nextBtn.addEventListener("click", function() {
            if (currentStep < steps.length) {
                currentStep++;
                updateSteps();
            }
        });

        prevBtn.addEventListener("click", function() {
            if (currentStep > 1) {
                currentStep--;
                updateSteps();
            }
        });

        updateSteps();
    });

    // Show timingDiv
    document.addEventListener('DOMContentLoaded', function() {
        const numberEmailsInput = document.getElementById('numberEmails');
        const timingDiv = document.getElementById('timingDiv');

        numberEmailsInput.addEventListener('input', function() {
            const numberEmailsValue = parseInt(numberEmailsInput.value);

            if (numberEmailsValue > 1) {
                timingDiv.classList.remove('hidden');
                timingDiv.classList.add('block');
            } else {
                timingDiv.classList.remove('block');
                timingDiv.classList.add('hidden');
            }
        });
    });

    const timingInput = document.getElementById('timingEmail');
    const timingUnitSpan = document.getElementById('timingUnit');

    timingInput.addEventListener('input', function() {
        timingUnitSpan.textContent = timingInput.value == 1 ? 'day' : 'days';
    });

    // Main JS of the page
    document.addEventListener('DOMContentLoaded', function() {
        const generateCampaignForm = document.getElementById('generateCampaignForm');

        const timingEmailInput = document.getElementById('timingEmail');
        const numberEmailsInput = document.getElementById('numberEmails');
        const llmSelected = document.getElementById('llmSelect');

        const newEmailSubjectExample = document.getElementById('emailSubjectExampleInput');
        const newEmailBodyExample = document.getElementById('emailBodyExampleInput');

        const generateEmailButton = document.getElementById('generateEmail');
        const errorModal = document.getElementById('error-data-modal');

        const topicIdSelect = document.getElementById('topicIdSelect');
        const persuasionButtons = document.querySelectorAll('.persuasion-button');
        const emotionalTriggerButtons = document.querySelectorAll('.emotional-trigger-button');

        let selectedPersuasions = [];
        let selectedEmotionalTriggers = [];
        let selectedEmail = null;

        function highlightChanges(oldText, newText) {
            const diff = Diff.diffWords(oldText, newText);
            return diff.map(part => {
                const color = part.added ? 'bg-sky-200' : part.removed ? 'bg-red-200' : '';
                return `<span class="${color}">${part.value}</span>`;
            }).join('');
        }

        function updateTextArea(subject, body) {
            document.getElementById('emailSubjectExample').innerHTML = highlightChanges(newEmailSubjectExample.value, subject);
            document.getElementById('emailBodyExample').innerHTML = highlightChanges(newEmailBodyExample.value, body);

            newEmailSubjectExample.value = subject;
            newEmailBodyExample.value = body;
        }

        function toggleSelection(type, element, dataAttribute, selectedArray) {
            const value = element.getAttribute(dataAttribute);

            if (selectedArray.includes(value)) {
                selectedArray.splice(selectedArray.indexOf(value), 1);
                element.classList.remove('bg-sky-800', 'text-white');
                element.classList.add('bg-white', 'text-sky-800');
            } else {
                selectedArray.push(value);
                element.classList.remove('bg-white', 'text-sky-800');
                element.classList.add('bg-sky-800', 'text-white');

                if (type == "persuasions") {
                    async function updateEmailDetails() {
                        const result = await addDetailsEmailExample(
                            newEmailSubjectExample.value,
                            newEmailBodyExample.value,
                            "persuasions",
                            selectedArray
                        );

                        if (result) {
                            updateTextArea(result.subject, result.body);
                        }
                    }

                    updateEmailDetails();
                } else if (type == "emotional-triggers") {
                    async function updateEmailDetails() {
                        const result = await addDetailsEmailExample(
                            newEmailSubjectExample.value,
                            newEmailBodyExample.value,
                            "emotional-triggers",
                            selectedArray
                        );

                        if (result) {
                            updateTextArea(result.subject, result.body);
                        }
                    }

                    updateEmailDetails();
                }
            }
        }

        function updateGenerateButtonText() {
            const numberEmails = numberEmailsInput.value;
            if (numberEmails > 1) {
                generateEmailButton.textContent = "{{ __('Generate emails') }}";
            } else {
                generateEmailButton.textContent = "{{ __('Generate email') }}";
            }
        }

        if (numberEmailsInput) {
            numberEmailsInput.addEventListener('change', updateGenerateButtonText);
        }

        if (topicIdSelect) {
            topicIdSelect.addEventListener('change', function() {
                selectedEmail = null;
                updateTextArea("", "");

                // Reset persuasions and emotional triggers when the topic changes
                selectedPersuasions = [];
                selectedEmotionalTriggers = [];

                persuasionButtons.forEach(button => {
                    button.classList.remove('bg-sky-800', 'text-white');
                    button.classList.add('bg-white', 'text-sky-800');
                });
                emotionalTriggerButtons.forEach(button => {
                    button.classList.remove('bg-sky-800', 'text-white');
                    button.classList.add('bg-white', 'text-sky-800');
                });
            });
        }

        if (persuasionButtons) {
            persuasionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    toggleSelection('persuasions', this, 'data-persuasion',
                        selectedPersuasions);
                });
            });
        }

        if (emotionalTriggerButtons) {
            emotionalTriggerButtons.forEach(button => {
                button.addEventListener('click', function() {
                    toggleSelection('emotional-triggers', this, 'data-emotional-trigger',
                        selectedEmotionalTriggers);
                });
            });
        }

        // Event Listener per il submit del form
        generateCampaignForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const timingEmail = timingEmailInput.value;
            const numberEmails = numberEmailsInput.value;

            if (timingEmail === '') {
                showErrorModal();
                return;
            }

            if (!document.getElementById('llmSelect')) {
                showErrorLLMModal();
                return;
            }

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

            const selectedTopic = topicIdSelect ? topicIdSelect.value : '';
            const selectedPersuasionIds = Array.from(document.querySelectorAll(
                '.persuasion-button.bg-sky-800')).map(button => button.value);
            const selectedEmotionalTriggerIds = Array.from(document.querySelectorAll(
                '.emotional-trigger-button.bg-sky-800')).map(button => button.value);

            document.getElementById('numberEmailsInput').value = numberEmails;
            document.getElementById('topicIdInput').value = selectedTopic;
            document.getElementById('emotionalTriggersInput').value = selectedEmotionalTriggerIds.join(
                ',');
            document.getElementById('persuasionsInput').value = selectedPersuasionIds.join(',');
            document.getElementById('llmIdInput').value = llmSelected.value;

            document.getElementById('timingEmailInput').value = timingEmail;

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

        updateGenerateButtonText();
    });

    // Callback to the backend to add details to the email example
    async function addDetailsEmailExample(subject, body, type, values) {
        if (!subject && !body && !type && !values) {
            return null;
        }

        // Loading screen
        document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

        // Data to send to the backend
        const data = {
            subject: subject,
            body: body,
            type: type,
            values: values,
        };

        try {
            const response = await fetch('/phishing-emails/add-details-email-example', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(data)
            });

            const responseData = await response.json();

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('flex', 'hidden');

            if (responseData.success) {
                const newSubjectEmail = responseData.emailData.subject;
                const newBodyEmail = responseData.emailData.body;

                return {
                    subject: newSubjectEmail,
                    body: newBodyEmail
                };
            } else {
                throw new Error('Errore nella risposta del server');
            }
        } catch (error) {
            console.error('Error:', error);

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('flex', 'hidden');

            return {
                subject: document.getElementById('emailSubjectExampleInput').value,
                body: document.getElementById('emailBodyExampleInput').value
            };
        }
    }
</script>
