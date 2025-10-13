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
                <li>@lang('general.selectUsers')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

                <div class="flex flex-row justify-around items-center pb-6">
                    <!-- Circles which indicates the steps of the creation -->
                    <div class="flex flex-row justify-center items-center">
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status active"></span>
                    </div>
                </div>

                <p class="font-semibold text-xl pb-4">
                    @lang('training-campaign.users.selectUsersToInvite'):
                </p>
                @if (!$users->isEmpty())
                    @include('layouts.partials.users-selection', ['users' => $users])

                    <form id="saveUsersForm" action="{{ route('training-campaign.users-training') }}" method="POST">
                        @csrf
                        @method('post')

                        <input type="hidden" name="trainingCampaignId" id="trainingCampaignIdInput"
                            value="{{ $trainingCampaignId }}">
                        <input type="hidden" name="usersIds" id="usersIdsInput">

                        <div class="flex flex-row justify-around items-center pt-6">
                            <div class="flex w-1/3">
                            </div>
                            <!-- Circles which indicates the steps of the creation -->
                            <div class="flex flex-row w-1/3 justify-center items-center">
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status active"></span>
                            </div>
                            <div class="flex w-1/3 justify-center">
                                <x-primary-button type="submit">@lang('general.create')</x-primary-button>
                            </div>
                        </div>
                    </form>
                @else
                    <p class="text-center">@lang('general.noUsers')</p>
                @endif

            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<!-- Error user modal -->
<x-modal name="error-user-modal" id="error-user-modal" title="Error user!" :show="false">
    <div class="p-4 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('general.chooseUser')</p>
        <x-primary-button x-on:click="$dispatch('close')">@lang('general.close')</x-primary-button>
    </div>
</x-modal>

<!-- Error age modal -->
<x-modal name="error-age-modal" id="error-age-modal" title="Error age!" :show="false">
    <div class="p-4 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('general.errorAge')</p>
        <x-primary-button x-on:click="$dispatch('close')">@lang('general.close')</x-primary-button>
    </div>
</x-modal>

<script>
    document.getElementById('saveUsersForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const selectedUserCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        if (selectedUserCheckboxes.length === 0) {
            const modalEvent = new CustomEvent('open-modal', {
                detail: 'error-user-modal'
            });
            window.dispatchEvent(modalEvent);
            return;
        }

        // Loading screen
        document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

        const selectedUsersIds = Array.from(selectedUserCheckboxes).map(checkbox => checkbox.dataset.userId);
        document.getElementById('usersIdsInput').value = selectedUsersIds.join(',');

        document.getElementById('saveUsersForm').submit();
    });
</script>
