<section>
    <header>
        <p class="text-lg font-medium text-sky-900">
            *@lang('phishing-campaign.partials.topic.topicTitle'):
        </p>
    </header>

    @if ($topics->isEmpty())
        <p class="text-center">@lang('phishing-campaign.partials.topic.noTopics')</p>
    @else
        <div class="flex flex-col md:flex-row justify-between">
            <div class="flex w-full md:w-1/3">
                <select id="topicIdSelect" name="topicId"
                    class="mt-1 block w-full py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
                    @foreach ($topics as $topic)
                        <option value="{{ $topic->id }}" data-topic="{{ $topic->description }}">
                            {{ $topic->description }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex w-full md:w-1/3 text-center items-center justify-center">
                <p>Or</p>
            </div>

            <div class="flex w-full md:w-1/3 justify-end">
                <x-primary-button data-id="" x-data=""
                    @click="$dispatch('open-modal', 'extract-topic-modal')">@lang('phishing-campaign.partials.topic.extractTopic')</x-primary-button>
            </div>
        </div>

        <div class="py-4">
            <p class="text-lg font-medium text-sky-900">*@lang('phishing-campaign.partials.topic.emailsTitle')</p>
            <div id="email-container" class="flex flex-wrap gap-4">
                @foreach ($topicsGroup as $description => $group)
                    @foreach ($group as $topic)
                        <div class="email-card border border-sky-700 rounded-lg p-2 bg-white shadow-sm w-96 h-full transition-all cursor-pointer"
                            data-topic-description="{{ $description }}">
                            <div class="flex flex-col gap-1" id="{{ $topic->id }}">
                                <div class="flex flex-col">
                                    <p class="font-semibold">@lang('general.subject'):</p>
                                    <p id="email-subject-card">{{ $topic->subject_email }}</p>
                                </div>
                                <div class="flex flex-col">
                                    <p class="font-semibold">@lang('general.body'):</p>
                                    <p id="email-body-card">{{ $topic->body_email }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif
</section>

<!-- Extract topic modal -->
<x-modal name="extract-topic-modal" id="extract-topic-modal" title="Extract topic modal!" :show="false">
    <div class="p-4 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-4">
            @lang('phishing-campaign.partials.topic.extractTopic')
        </p>
        <textarea id="emailTextArea"
            class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm"
            rows="10"></textarea>

        <div class="flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.cancel')</x-secondary-button>
            <x-primary-button type="submit" onclick="submitExtract()">@lang('general.analyse')</x-primary-button>
        </div>
    </div>
</x-modal>

<script>
    function submitExtract() {
        const emailTextArea = document.getElementById(`emailTextArea`).value;

        if (!emailTextArea) {
            return;
        }

        // Loading screen
        document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

        // Data to send to the backend
        const data = {
            emailTextArea: emailTextArea
        };

        fetch('/phishing-emails/extract-topic', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(responseData => {
                if (responseData.success) {
                    emailTextArea.value = '';

                    const emailExampleContainer = document.getElementById('emailExampleContainer');
                    emailExampleContainer.classList.remove('hidden');
                    const nextBtn = document.getElementById('nextBtn');
                    nextBtn.classList.remove('hidden');

                    document.getElementById('emailSubjectExample').innerHTML = responseData.emailData.subject;
                    document.getElementById('emailBodyExample').innerHTML = responseData.emailData.body;

                    const topicIdSelect = document.getElementById('topicIdSelect');
                    const newOption = document.createElement('option');
                    newOption.value = responseData.emailData.topic.id;
                    newOption.textContent = responseData.emailData.topic.description;
                    topicIdSelect.appendChild(newOption);

                    topicIdSelect.value = responseData.emailData.topic.id;

                    window.dispatchEvent(new CustomEvent('close-modal', {
                        detail: 'extract-topic-modal'
                    }));

                    // Loading screen
                    document.getElementById('loadingOverlay').classList.replace('flex', 'hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Loading screen
                document.getElementById('loadingOverlay').classList.replace('flex', 'hidden');
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const topicSelect = document.getElementById('topicIdSelect');
        const emailCards = document.querySelectorAll('.email-card');
        const persuasionButtons = document.querySelectorAll('.persuasion-button');
        const emotionalTriggerButtons = document.querySelectorAll('.emotional-trigger-button');
        const emailExampleContainer = document.getElementById('emailExampleContainer');
        const emailSubjectExample = document.getElementById('emailSubjectExample');
        const emailBodyExample = document.getElementById('emailBodyExample');
        const nextBtn = document.getElementById('nextBtn');

        const resetPersuasionsAndEmotionalTriggers = () => {
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
        };

        const updateEmailCards = () => {
            const selectedTopicDescription = topicSelect.selectedOptions[0].dataset.topic;
            nextBtn.classList.add('hidden');
            emailExampleContainer?.classList.add('hidden');
            emailSubjectExample.value = "";
            emailBodyExample.value = "";

            resetPersuasionsAndEmotionalTriggers();

            emailCards.forEach(card => {
                const cardTopicDescription = card.dataset.topicDescription;
                card.classList.toggle('hidden', cardTopicDescription !== selectedTopicDescription);
                if (card.classList.contains('selected')) {
                    card.classList.remove('selected');
                    card.style.backgroundColor = 'white';
                    card.style.color = '#0369a1';
                }
            });
        };

        // Handle topic selection change
        topicSelect.addEventListener('change', updateEmailCards);

        // Handle email card selection events
        emailCards.forEach(card => {
            card.addEventListener('click', () => {
                const isSelected = card.classList.contains('selected');

                // Deselect all cards if already selected
                emailCards.forEach(c => {
                    c.classList.remove('selected');
                    c.style.backgroundColor = 'white';
                    c.style.color = '#0369a1';
                });

                if (isSelected) {
                    emailExampleContainer.classList.add('hidden');
                    emailSubjectExample.innerHTML = "";
                    emailBodyExample.innerHTML = "";
                    nextBtn.classList.add('hidden');
                } else {
                    // Select the clicked card
                    card.classList.add('selected');
                    card.style.backgroundColor = '#0369a1';
                    card.style.color = 'white';

                    const emailSubject = card.querySelector('#email-subject-card').innerText;
                    const emailBody = card.querySelector('#email-body-card').innerText;
                    emailSubjectExample.innerHTML = emailSubject;
                    emailBodyExample.innerHTML = emailBody;

                    document.getElementById('emailSubjectExampleInput').value = emailSubject;
                    document.getElementById('emailBodyExampleInput').value = emailBody;
                    nextBtn.classList.remove('hidden');

                    resetPersuasionsAndEmotionalTriggers();
                }
            });
        });

        updateEmailCards();
    });
</script>
