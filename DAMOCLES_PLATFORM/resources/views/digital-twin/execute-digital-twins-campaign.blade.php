<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twins campaign -->
            <a href="{{ route('digital-twins.index') }}" class="cursor-pointer">
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
            </ul>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">
                <p class="font-semibold text-xl mb-6">@lang('digital-twin.executeDigitalTwinCampaign')</p>

                @if ($digitalTwinsCampaign)
                    <!-- Users -->
                    @if ($users->isNotEmpty())

                        <div class="flex justify-between items-center pt-2 pb-4">
                            <!-- Execute/Update Results Button -->
                            @php
                                $allStatesNull = $users->every(fn($user) => is_null($user->state));
                                $allStatesNotNull = $users->every(fn($user) => !is_null($user->state));
                            @endphp
                            <!-- Legend -->
                            <div
                                class="flex flex-col md:flex-row justify-center items-center space-y-2 md:space-x-4 md:space-y-0">
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-1"></span>
                                    <span class="text-sm text-gray-700 text-center">@lang('digital-twin.inProgress')</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-1"></span>
                                    <span class="text-sm text-gray-700 text-center">@lang('digital-twin.completed')</span>
                                </div>
                            </div>

                            <div class="flex-1 flex justify-end gap-2">
                                <a class="relative group"
                                    href="{{ $allStatesNotNull ? '#' : route('digital-twins.executeCampaign', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id]) }}"
                                    id="executeCampaignButton">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-sky-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150
                                            {{ $allStatesNotNull ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $allStatesNotNull ? 'disabled' : '' }}>
                                        <!-- Button Content -->
                                        {{ $allStatesNull ? __('digital-twin.executeCampaign') : __('digital-twin.executeOnNewUsers') }}
                                    </button>
                                    @if ($allStatesNotNull)
                                        <div
                                            class="text-center absolute left-1/2 transform -translate-x-1/2 mt-2 w-32 p-2 bg-sky-900 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50
                                                {{ $allStatesNotNull ? 'group-hover:opacity-100' : 'group-hover:opacity-0' }}">
                                            @lang('digital-twin.allDone')
                                        </div>
                                    @endif
                                </a>

                                <a class="relative group"
                                    href="{{ route('digital-twins.analyse', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id]) }}"
                                    id="analyseButton">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-sky-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150
                                            {{ $allStatesNull ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $allStatesNull ? 'disabled' : '' }}>
                                        {{ __('digital-twin.detailsAnalyse') }}
                                    </button>
                                    @if ($allStatesNull)
                                        <div
                                            class="text-center absolute left-1/2 transform -translate-x-1/2 mt-2 w-32 p-2 bg-sky-900 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50
                                                {{ $allStatesNull ? 'group-hover:opacity-100' : 'group-hover:opacity-0' }}">
                                            @lang('digital-twin.executeFirst')
                                        </div>
                                    @endif
                                </a>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <p class="font-semibold">@lang('questionnaire-campaign.detailsCampaign.users'):</p>
                            <table id="user-table" class="w-full text-center">
                                <thead class="bg-gray-100">
                                    <tr class="border-b-2 border-gray-300">
                                        <th class="py-2 px-4">@lang('general.user.name')</th>
                                        <th class="py-2 px-4">@lang('general.user.surname')</th>
                                        <th class="py-2 px-4">@lang('general.user.email')</th>
                                        <th class="py-2 px-4">@lang('digital-twin.campaignState')</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach ($users as $user)
                                        <tr class="hover:bg-gray-100 border-b border-gray-200  
                                            @if (!is_null($user->state)) cursor-pointer @endif"
                                            @if (!is_null($user->state)) onclick="handleRowClick(event, '{{ route('digital-twins.analyseUser', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id, 'user' => $user->id]) }}')" @endif>
                                            <td class="py-2 px-4">{{ $user->name }}</td>
                                            <td class="py-2 px-4">{{ $user->surname }}</td>
                                            <td class="py-2 px-4">{{ $user->email }}</td>
                                            <td class="py-2 px-4">
                                                @if (is_null($user->state))
                                                    <span
                                                        class="relative group inline-block w-3 h-3 bg-yellow-500 rounded-full hover:bg-yellow-600">
                                                        <div
                                                            class="absolute left-1/2 transform -translate-x-1/2 mt-2 w-32 p-2 bg-sky-900 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                                            @lang('digital-twin.inProgress')
                                                        </div>
                                                    </span>
                                                @else
                                                    <span
                                                        class="relative group inline-block w-3 h-3 bg-green-500 rounded-full hover:bg-green-600">
                                                        <div
                                                            class="absolute left-1/2 transform -translate-x-1/2 mt-2 w-32 p-2 bg-sky-900 text-white text-xs rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                                            @lang('digital-twin.completed')
                                                        </div>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Paginator controls -->
                            @include('layouts.partials.pagination-controls')
                        </div>
                        <div class="flex flex-col w-full item-center gap-2">
                            <div class="flex justify-center">
                                <a href="{{ route('digital-twins.addUsers', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id]) }}"
                                    id="chooseUsersButton" class="flex justify-end pt-2">
                                    <x-primary-button>@lang('general.addUsers')</x-primary-button>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col w-full item-center gap-2">
                            <p class="text-center text-lg text-sky-700">@lang('general.noUsers')
                            </p>
                            <div class="flex justify-center">
                                <a href="{{ route('digital-twins.addUsers', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id]) }}"
                                    id="chooseUsersButton" class="flex justify-end pt-2">
                                    <x-primary-button>@lang('general.chooseUsers')</x-primary-button>
                                </a>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center text-xl">
                        <p>@lang('general.errorRetrievingCampaign')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<script src="{{ asset('js/tabulationTable.js') }}"></script>
<script>
    // Tabulation
    document.addEventListener('DOMContentLoaded', function() {
        setupPagination(
            'user-table', // Table ID
            'Total users' // Personalized text for the total label
        );
    });

    function handleRowClick(event, url) {
        // Check if the click was on a non-linkable part (e.g., status icon)
        if (!event.target.closest('td')) return;

        // Redirect to the provided URL
        window.location.href = url;
    }

    // Show the loading overlay
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = ['executeCampaignButton', 'analyseButton'];

        buttons.forEach(buttonId => {
            document.getElementById(buttonId).querySelector('button').addEventListener('click',
                function(event) {
                    // Loading screen
                    document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
                });
        });
    });
</script>
