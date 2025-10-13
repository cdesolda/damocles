<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twins campaign -->
            <a href="{{ route('digital-twins.chooseEmail') }}" class="cursor-pointer">
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
                <li><a href="{{ route('digital-twins.simulation') }}">@lang('digital-twin.digitalTwinCampaignSimulation')</a></li>
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
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status active"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="font-semibold text-xl">@lang('digital-twin.digitalTwinCampaignSimulationTitle')</p>
                    <p class="font-semibold text-sm">@lang('general.mandatoryField')</p>
                    <p class="text-lg font-medium text-sky-900">@lang('digital-twin.chooseFakeUsersExplanation'):</p>
                    <div>
                        @include('digital-twin.partials.fake-user')
                    </div>
                </div>
                <form id="generateFakeUsersForm" data-id="" action="{{ route('digital-twins.simulationResult') }}"
                    method="POST">
                    @csrf
                    @method('post')
                    <input type="hidden" name="selectedUserIds" id="selectedUserIdsInput" value="">

                    <div class="flex flex-row justify-around items-center pt-6">
                        <div class="flex w-1/3">
                        </div>
                        <!-- Circles which indicates the steps of the creation -->
                        <div class="flex flex-row w-1/3 justify-center items-center">
                            <span class="status"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                            <span class="status active"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                        </div>
                        <div class="flex w-1/3 justify-center">
                            <x-primary-button id="generateTraining" type="submit">@lang('general.next')</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<!-- Error modal -->
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const generateFakeUsersForm = document.getElementById('generateFakeUsersForm');
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

        updateSelectedUserIds();

        generateFakeUsersForm.addEventListener('submit', function(event) {
            event.preventDefault();
            updateSelectedUserIds();
            const selectedUsers = JSON.parse(selectedUserIdsInput.value);

            if (selectedUsers.length === 0) {
                showErrorUserModal();
                return;
            }

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

            this.submit();
        });

        function showErrorUserModal() {
            const errorModal = new CustomEvent('open-modal', {
                detail: 'error-user-modal'
            });
            window.dispatchEvent(errorModal);
        }

    });
</script>
