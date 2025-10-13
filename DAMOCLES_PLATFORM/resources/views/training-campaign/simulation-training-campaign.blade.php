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
                <li>/</li>
                <li><a href="{{ route('training-campaign.new') }}">@lang('training-campaign.new')</a></li>
                <li>/</li>
                <li><a
                        href="{{ route('training-campaign.simulation', ['trainingCampaign' => $trainingCampaign->id]) }}">@lang('training-campaign.newTrainingCampaign.simulationTraining')</a>
                </li>
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
                    <p class="font-semibold text-xl">@lang('training-campaign.newTrainingCampaign.simulationTraining')</p>
                    <p class="font-semibold text-sm">@lang('training-campaign.newTrainingCampaign.mandatoryField')</p>

                    <div>
                        @include('training-campaign.partials.fake-user-partials.fake-user')
                    </div>

                    <div>
                        @include('training-campaign.partials.training-llm')
                    </div>

                    <div>
                        @include('training-campaign.partials.prompt-partials.training-prompt')
                    </div>
                </div>

                <form id="generateTrainingForm" data-id=""
                    action="{{ route('training-campaign.generate-trainings') }}" method="POST">
                    @csrf
                    @method('post')

                    <input type="hidden" name="trainingCampaignId" id="trainingCampaignInput"
                        value="{{ $trainingCampaign->id }}">
                    <input type="hidden" name="llmId" id="llmIdInput">
                    <input type="hidden" name="prompt" id="promptDescriptiontInput">
                    <input type="hidden" name="evaluatorId" id="evaluatorIdInput" value="{{ auth()->user()->id }}">

                    <input type="hidden" name="selectedUserIds" id="selectedUserIdsInput" value="">

                    <div class="flex flex-row justify-around items-center pt-6">
                        <div class="flex w-1/3">
                        </div>
                        <!-- Circles which indicates the steps of the creation -->
                        <div class="flex flex-row w-1/3 justify-center items-center">
                            <span class="status"></span>
                            <span class="status active"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                        </div>
                        <div class="flex w-1/3 justify-center">
                            <x-primary-button id="generateTraining" type="submit">@lang('training-campaign.newTrainingCampaign.simulate')</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<!-- Error modals -->
<!-- Error user selection modal -->
<x-modal name="error-user-modal" id="error-user-modal" title="Error User Selection" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">
            @lang('training-campaign.newTrainingCampaign.noUsersSelected')
        </p>
        <x-secondary-button x-on:click="$dispatch('close')">
            @lang('general.close')
        </x-secondary-button>
    </div>
</x-modal>

<!-- Error data modal -->
<x-modal name="error-data-modal" id="error-data-modal" title="Error data" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('training-campaign.newTrainingCampaign.errorData')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
    </div>
</x-modal>

<!-- Error llm modal -->
<x-modal name="error-llm-modal" id="error-llm-modal" title="Error llm" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('training-campaign.newTrainingCampaign.noLLMs')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
    </div>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const generateTrainingForm = document.getElementById('generateTrainingForm');
        const llmSelected = document.getElementById('llmSelect');
        const promptTextarea = document.getElementById('promptDescription');
        const selectedUserIdsInput = document.getElementById('selectedUserIdsInput');
        const checkboxes = document.querySelectorAll('.user-checkbox');

        function updateSelectedUserIds() {
            const selectedUsers = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => {
                    const userId = checkbox.getAttribute('data-user-id');

                    return {
                        id: userId
                    };
                });

            selectedUserIdsInput.value = JSON.stringify(selectedUsers);
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedUserIds);
        });

        // Update when a select or input changes
        document.addEventListener('change', function(event) {
            if (
                event.target.classList.contains('human-factor-select') ||
                event.target.classList.contains('education-level')
            ) {
                updateSelectedUserIds();
            }
        });

        // Add input validation
        document.addEventListener('input', function(event) {
            if (event.target.classList.contains('education-level')) {
                const value = parseFloat(event.target.value);
                const max = parseFloat(event.target.max);
                const min = parseFloat(event.target.min);

                // Ensure the value is within the correct range
                if (value > max) {
                    event.target.value = max;
                } else if (value < min) {
                    event.target.value = min;
                }
            }
        });

        updateSelectedUserIds();

        generateTrainingForm.addEventListener('submit', function(event) {
            event.preventDefault();

            updateSelectedUserIds();

            const selectedUsers = JSON.parse(selectedUserIdsInput.value);
            if (selectedUsers.length === 0) {
                showErrorUserModal();
                return;
            }

            if (!llmSelected) {
                showErrorLLMModal();
                return;
            }

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

            document.getElementById('llmIdInput').value = llmSelected.value;
            document.getElementById('promptDescriptiontInput').value = promptTextarea.value;

            this.submit();
        });

        function showErrorUserModal() {
            const errorModal = new CustomEvent('open-modal', {
                detail: 'error-user-modal'
            });
            window.dispatchEvent(errorModal);
        }

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
    });
</script>
