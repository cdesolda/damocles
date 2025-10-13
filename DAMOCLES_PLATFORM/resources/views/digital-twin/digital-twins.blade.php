@php
    use Carbon\Carbon;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('digital-twins.index') }}">@lang('digital-twin.digitalTwinsCampaigns')</a></li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8 text-sky-900">

                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 sm:justify-between items-center mb-6">
                    <p class="font-semibold text-xl w-full">@lang('digital-twin.digitalTwinsCampaigns')</p>

                    <div class="flex flex-col md:flex-row w-full justify-end items-center gap-4">
                        <div>
                            @if ($digitalTwinsCampaigns->count() > 0)
                                <!-- Filter -->
                                <div class="flex flex-row w-80 gap-3 justify-end items-center relative">
                                    <label for="filter"
                                        class="block text-sm font-bold text-sky-700">@lang('general.search')</label>
                                    <input type="text" id="filter" name="filter"
                                        class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                                        placeholder="Enter the campaign name">
                                    <button id="clear-filter"
                                        class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 0a10 10 0 0 1 7.071 2.929A10 10 0 0 1 20 10a10 10 0 0 1-2.929 7.071A10 10 0 0 1 10 20a10 10 0 0 1-7.071-2.929A10 10 0 0 1 0 10a10 10 0 0 1 2.929-7.071A10 10 0 0 1 10 0zm3.536 5.05a.5.5 0 0 1 .708.708L10.707 10l3.536 3.536a.5.5 0 0 1-.708.708L10 10.707l-3.536 3.536a.5.5 0 1 1-.708-.708L9.293 10 5.757 6.464a.5.5 0 0 1 .708-.708L10 9.293l3.536-3.536z" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('digital-twins.new') }}" class="cursor-pointer">
                                <x-primary-button>
                                    @lang('digital-twin.new')
                                </x-primary-button>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                @if ($digitalTwinsCampaigns->count() > 0)
                    <table id="digital-twins-campaigns-table" class="min-w-full text-left border-collapse">
                        <thead class="bg-gray-100">
                            <tr class="border-b-2 border-gray-300">
                                <th class="py-2 px-4">@lang('phishing-campaign.title')</th>
                                <th class="py-2 px-4">@lang('general.updatedAt')</th>
                                <th class="py-2 px-4"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($digitalTwinsCampaigns as $digitalTwinsCampaign)
                                <tr class="hover:bg-gray-100 border-b border-gray-200 cursor-pointer"
                                    onclick="handleRowClick(event, '{{ route('digital-twins.executeDigitalTwinCampaign', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id]) }}')">
                                    <td class="py-2 px-4">{{ $digitalTwinsCampaign->title }}</td>
                                    <td class="py-2 px-4">{{ $digitalTwinsCampaign->updated_at }}</td>
                                    <td class="flex flex-row justify-end py-2 px-4">
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button class="inline-flex items-center font-semibold inner-element">
                                                    <p class="classic">
                                                        @lang('general.options')
                                                        <span>
                                                            <svg class="fill-current h-4 w-4"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </p>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                <div class="flex flex-col gap-2 justify-center items-center">
                                                    <a href="{{ route('digital-twins.details', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id]) }}"
                                                        class="hover:bg-gray-100 inner-element py-1 w-full">
                                                        <button class="hover:bg-gray-100 w-full">
                                                            {{ __('general.details') }}
                                                        </button>
                                                    </a>
                                                    <a href="{{ route('digital-twins.executeDigitalTwinCampaign', ['digitalTwinsCampaign' => $digitalTwinsCampaign->id]) }}"
                                                        class="hover:bg-gray-100 inner-element py-1 w-full">
                                                        <button class="hover:bg-gray-100 w-full">
                                                            {{ __('general.analyse') }}
                                                        </button>
                                                    </a>
                                                    <button
                                                        class="hover:bg-gray-100 text-red-500 inner-element py-1 w-full"
                                                        data-id="{{ $digitalTwinsCampaign->id }}"
                                                        x-data=""
                                                        @click="$dispatch('open-modal', 'delete-modal', { id: {{ $digitalTwinsCampaign->id }} })">
                                                        @lang('general.delete')
                                                    </button>
                                                </div>
                                            </x-slot>
                                        </x-dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Paginator controls -->
                    @include('layouts.partials.pagination-controls')
                @else
                    <p class="pt-4 text-center text-lg text-sky-700">
                        @lang('digital-twin.noDigitalTwinsCampaign')</p>
                @endif

            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<!-- Delete modal -->
<x-modal name="delete-modal" id="delete-modal" title="Delete Phishing Campaign" :show="false">
    <div class="p-4 rounded-lg relative">
        @include('digital-twin.modals.delete-digital-twins-campaign')
    </div>
</x-modal>

<!-- Delete successfully message -->
<x-modal name="delete-successfully-modal" id="delete-successfully-modal" title="Delete successfully modal"
    :show="false">
    <div class="p-6 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">
            @lang('general.deleteSucc')
        </p>

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Error modal -->
<x-modal name="error-modal" id="error-modal" title="Error modal" :show="false">
    <div class="p-6 rounded-lg relative text-center text-red-800">
        <p class="text-xl font-semibold pb-8">
            @lang('general.tryAgain')
        </p>

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<script src="{{ asset('js/tabulationTable.js') }}"></script>
<script>
    // Tabulation
    document.addEventListener('DOMContentLoaded', function() {
        setupPagination(
            'digital-twins-campaigns-table', // Table ID
            'Total campaigns' // Personalized text for the total label
        );
    });

    function handleRowClick(event, url) {
        if (!event.target.closest('.inner-element')) {
            location.href = url;
        }
    }
</script>
