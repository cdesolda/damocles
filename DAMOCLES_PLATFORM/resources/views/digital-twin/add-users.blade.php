<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twins campaign -->
            <a href="{{ route('digital-twins.executeDigitalTwinCampaign', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id]) }}"
                class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('digital-twins.index') }}">@lang('digital-twin.digitalTwinsCampaigns')</a></li>
                <li>/</li>
                <li><a
                        href="{{ route('digital-twins.executeDigitalTwinCampaign', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id]) }}">
                        @lang('digital-twin.executeDigitalTwinCampaign')</a></li>
                <li>/</li>
                <li>@lang('general.addUsers')</li>
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
                        <span class="status active"></span>
                    </div>
                </div>

                <p class="flex text-lg font-medium text-sky-900 pb-2">
                    @lang('general.selectUsers'):
                </p>
                @if (!$users->isEmpty())
                    @include('layouts.partials.users-selection', [
                        'users' => $users,
                    ])

                    <form id="saveUsersForm"
                        action="{{ route('digital-twins.finalizeAddUsers', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id]) }}"
                        method="POST">
                        @csrf
                        <!-- Pass validated data as hidden field -->
                        <input type="hidden" name="usersIds" id="usersIdsInput">

                        <div class="flex flex-row justify-around items-center pt-6">
                            <div class="flex w-1/3">
                            </div>
                            <!-- Circles which indicates the steps of the creation -->
                            <div class="flex flex-row w-1/3 justify-center items-center">
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status active"></span>
                            </div>
                            <div class="flex w-1/3 justify-center">
                                <x-primary-button type="submit">@lang('general.finish')</x-primary-button>
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

<script src="{{ asset('js/tabulationTable.js') }}"></script>
<script src="{{ asset('js/usersSelection.js') }}"></script>

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
