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
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white sm:overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

                <div class="flex flex-row justify-around items-center pb-6">
                    <!-- Circles which indicates the steps of the creation -->
                    <div class="flex flex-row justify-center items-center">
                        <span class="status active"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="font-semibold text-xl">@lang('training-campaign.newTrainingCampaign.new')</p>
                    <p class="font-semibold text-sm">@lang('training-campaign.newTrainingCampaign.mandatoryField')</p>
                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('training-campaign.newTrainingCampaign.title'):
                        </p>
                        <input type="text" id="title" name="title"
                            class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full sm:w-2/3"
                            required>
                    </div>

                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('training-campaign.newTrainingCampaign.description'):
                        </p>
                        <textarea id="description" name="description"
                            class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full" required></textarea>
                    </div>

                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('training-campaign.newTrainingCampaign.expirationDate'):
                        </p>
                        <input type="date" id="expirationDate" name="expirationDate"
                            class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md cursor-pointer"
                            required>
                    </div>

                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            @lang('training-campaign.newTrainingCampaign.threat'):
                        </p>
                        <select id="threatIdSelect" name="threat"
                            class="mt-1 block w-full sm:w-1/2 py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
                            @foreach ($threats as $threat)
                                <option value="{{ $threat->id }}" data-threat="{{ $threat->name }}">
                                    {{ $threat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            @lang('training-campaign.newTrainingCampaign.type'):
                        </p>
                        <select id="typeSelect" name="type"
                            class="mt-1 block w-full sm:w-1/2 py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
                            <option value="Text">@lang('training-campaign.newTrainingCampaign.text')</option>
                            <option value="Audio">@lang('training-campaign.newTrainingCampaign.audio')</option>
                        </select>
                    </div>
                </div>

                <form id="createCampaignForm" data-id="" action="{{ route('training-campaign.create') }}"
                    method="POST">
                    @csrf
                    @method('post')

                    <input type="hidden" name="title" id="titleInput">
                    <input type="hidden" name="description" id="descriptionInput">
                    <input type="hidden" name="expirationDate" id="expirationDateInput">
                    <input type="hidden" name="threatId" id="threatIdInput">
                    <input type="hidden" name="type" id="typeInput">
                    <input type="hidden" name="evaluatorId" id="evaluatorIdInput" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="state" id="stateInput" value="Draft">

                    <div class="flex flex-row justify-around items-center pt-6">
                        <div class="flex w-1/3">
                        </div>
                        <!-- Circles which indicates the steps of the creation -->
                        <div class="flex flex-row w-1/3 justify-center items-center">
                            <span class="status active"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                        </div>
                        <div class="flex w-1/3 justify-center">
                            <x-primary-button id="simulateTraining" type="submit">@lang('training-campaign.newTrainingCampaign.simulate')</x-primary-button>
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
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('training-campaign.newTrainingCampaign.errorData')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
    </div>
</x-modal>

<!-- Error date modal -->
<x-modal name="error-date-modal" id="error-date-modal" title="Error date" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('training-campaign.newTrainingCampaign.errorDate')</p>
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
        const createCampaignForm = document.getElementById('createCampaignForm');

        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        const expirationDateInput = document.getElementById('expirationDate');
        const typeSelected = document.getElementById('typeSelect');
        const simulateTrainingButton = document.getElementById('simulateTraining');
        const errorModal = document.getElementById('error-data-modal');

        const threatIdSelect = document.getElementById('threatIdSelect');

        const today = new Date().toISOString().split('T')[0];
        expirationDateInput.setAttribute('min', today);

        createCampaignForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const title = titleInput.value.trim();
            const description = descriptionInput.value.trim();
            const expirationDate = expirationDateInput.value;

            if (title === '' || description === '') {
                showErrorModal();
                return;
            }

            const selectedDate = new Date(expirationDate);
            const todayDate = new Date(today);

            if (selectedDate < todayDate) {
                showErrorDateModal();
                return;
            }

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

            const selectedThreat = threatIdSelect ? threatIdSelect.value : '';

            document.getElementById('titleInput').value = title;
            document.getElementById('descriptionInput').value = description;
            document.getElementById('expirationDateInput').value = expirationDate;
            document.getElementById('threatIdInput').value = selectedThreat;
            document.getElementById('typeInput').value = typeSelected.value;

            this.submit();
        });

        function showErrorModal() {
            const errorModal = new CustomEvent('open-modal', {
                detail: 'error-data-modal'
            });
            window.dispatchEvent(errorModal);
        }

        function showErrorDateModal() {
            const errorModal = new CustomEvent('open-modal', {
                detail: 'error-date-modal'
            });
            window.dispatchEvent(errorModal);
        }
    });
</script>
