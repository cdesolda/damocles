<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('phishing-campaign.option') }}">@lang('phishing-campaign.option.option')</a></li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 text-sky-900">

            <div class="bg-white sm:overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 space-y-6">
                    <p class="text-lg font-medium text-sky-900">
                        @lang('phishing-campaign.option.option'):
                    </p>

                    <!-- Downlaod data section -->
                    <div class="p-4 shadow space-y-4">
                        <div class="flex flex-row w-full items-center sm:pr-4">
                            <div class="w-1/2">
                                <p class="font-semibold text-xl">@lang('general.downloadData'):</p>
                            </div>
                            <div class="flex justify-end w-1/2">
                                <x-primary-button class="cursor-pointer" x-data=""
                                    @click="$dispatch('open-modal', 'download-data-modal')">
                                    @lang('general.download')
                                </x-primary-button>
                            </div>
                        </div>
                    </div>

                    <!-- Edit section -->
                    <div class="p-4 shadow space-y-4">
                        <p class="font-semibold text-xl">@lang('phishing-campaign.option.edit'):</p>

                        <div class="flex flex-row w-full items-center sm:px-4">
                            <div class="w-1/2">
                                <p>@lang('phishing-campaign.option.topics'):</p>
                            </div>
                            <div class="flex justify-end w-1/2">
                                <x-primary-button class="cursor-pointer" x-data=""
                                    @click="$dispatch('open-modal', 'topic-edit-modal')">
                                    @lang('phishing-campaign.option.editTopics')
                                </x-primary-button>
                            </div>
                        </div>

                        <div class="flex flex-row w-full items-center sm:px-4">
                            <div class="w-1/2">
                                <p>@lang('phishing-campaign.option.persusions'):</p>
                            </div>
                            <div class="flex justify-end w-1/2">
                                <x-primary-button class="cursor-pointer" x-data=""
                                    @click="$dispatch('open-modal', 'persuasion-edit-modal')">
                                    @lang('phishing-campaign.option.editPersusions')
                                </x-primary-button>
                            </div>
                        </div>

                        <div class="flex flex-row w-full items-center sm:px-4">
                            <div class="w-1/2">
                                <p>@lang('phishing-campaign.option.emotionalTrigger'):</p>
                            </div>
                            <div class="flex justify-end w-1/2">
                                <x-primary-button class="cursor-pointer" x-data=""
                                    @click="$dispatch('open-modal', 'emotional-trigger-edit-modal')">
                                    @lang('phishing-campaign.option.editEmotionalTriggers')
                                </x-primary-button>
                            </div>
                        </div>

                        <div class="flex flex-row w-full items-center sm:px-4">
                            <div class="w-1/2">
                                <p>@lang('phishing-campaign.option.threats'):</p>
                            </div>
                            <div class="flex justify-end w-1/2">
                                <x-primary-button class="cursor-pointer" x-data=""
                                    @click="$dispatch('open-modal', 'threat-edit-modal')">
                                    @lang('phishing-campaign.option.editThreats')
                                </x-primary-button>
                            </div>
                        </div>

                        <div class="flex flex-row w-full items-center sm:px-4">
                            <div class="w-1/2">
                                <p>@lang('phishing-campaign.option.humanFactors'):</p>
                            </div>
                            <div class="flex justify-end w-1/2">
                                <x-primary-button class="cursor-pointer" x-data=""
                                    @click="$dispatch('open-modal', 'humanFactor-edit-modal')">
                                    @lang('phishing-campaign.option.editHumanFactors')
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<!-- Download data modal -->
<x-modal name="download-data-modal" id="download-data-modal" title="Download data" :show="false">
    <div class="p-4 rounded-lg relative space-y-6 text-sky-800">
        <p class="text-xl font-semibold text-center">
            @lang('phishing-campaign.option.downloadModalMessage')
        </p>

        <div class="flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
            <a href="{{ route('phishing-campaign.download-all-data-csv') }}">
                <x-primary-button id="downloadButton" type="button">@lang('general.confirm')</x-primary-button>
            </a>
        </div>
    </div>
</x-modal>

<!-- Topic edit modal -->
<x-modal name="topic-edit-modal" id="topic-edit-modal" title="Topic edit" :show="false">
    <div class="p-4 rounded-lg relative space-y-6 text-sky-800">
        <p class="text-xl font-semibold">
            @lang('phishing-campaign.option.editTopics')
        </p>
        <!-- Add new topic -->
        @include('phishing-campaign.partials.topic-partials.add-topic')

        <!-- Delete topic -->
        @include('phishing-campaign.partials.topic-partials.delete-topic')

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Persuasion edit modal -->
<x-modal name="persuasion-edit-modal" id="persuasion-edit-modal" title="Persuasion edit" :show="false">
    <div class="p-4 rounded-lg relative space-y-6 text-sky-800">
        <p class="text-xl font-semibold">
            @lang('phishing-campaign.option.editPersusions')
        </p>
        <!-- Add new persuasion -->
        @include('phishing-campaign.partials.persuasion-partials.add-persuasion')

        <!-- Delete persuasion -->
        @include('phishing-campaign.partials.persuasion-partials.delete-persuasion')

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Emotional trigger edit modal -->
<x-modal name="emotional-trigger-edit-modal" id="emotional-trigger-edit-modal" title="Emotional trigger edit"
    :show="false">
    <div class="p-4 rounded-lg relative space-y-6 text-sky-800">
        <p class="text-xl font-semibold">
            @lang('phishing-campaign.option.editEmotionalTriggers')
        </p>
        <!-- Add new emotional trigger -->
        @include('phishing-campaign.partials.emotional-trigger-partials.add-emotional-trigger')

        <!-- Delete emotional trigger -->
        @include('phishing-campaign.partials.emotional-trigger-partials.delete-emotional-trigger')

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Threat edit modal -->
<x-modal name="threat-edit-modal" id="threat-edit-modal" title="Threat edit" :show="false">
    <div class="p-4 rounded-lg relative space-y-6 text-sky-800">
        <p class="text-xl font-semibold">
            @lang('phishing-campaign.option.editThreats')
        </p>
        <!-- Add new threat -->
        @include('phishing-campaign.partials.threat-partials.add-threat')

        <!-- Delete threat -->
        @include('phishing-campaign.partials.threat-partials.delete-threat')

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Human Factor edit modal -->
<x-modal name="humanFactor-edit-modal" id="humanFactor-edit-modal" title="Huamn Factor edit" :show="false">
    <div class="p-4 rounded-lg relative space-y-6 text-sky-800">
        <p class="text-xl font-semibold">
            @lang('phishing-campaign.option.editHumanFactors')
        </p>
        <!-- Add new human factor -->
        @include('phishing-campaign.partials.humanFactor-partials.add-humanFactor')

        <!-- Delete human factor -->
        @include('phishing-campaign.partials.humanFactor-partials.delete-humanFactor')

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Add successfully message -->
<x-modal name="add-successfully-modal" id="add-successfully-modal" title="Add successfully modal" :show="false">
    <div class="p-4 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">
            @lang('phishing-campaign.option.addSucc')
        </p>

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Delete successfully message -->
<x-modal name="delete-successfully-modal" id="delete-successfully-modal" title="Delete successfully modal"
    :show="false">
    <div class="p-4 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">
            @lang('phishing-campaign.option.deleteSucc')
        </p>

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Error modal -->
<x-modal name="error-modal" id="error-modal" title="Error modal" :show="false">
    <div class="p-4 rounded-lg relative space-y-6 text-red-800">
        <p class="text-xl font-semibold">
            @lang('general.option.tryAgain')
        </p>

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            @php
                $successMessage = session('success');
            @endphp

            // Determine which modal to show based on the success message
            if ("{{ $successMessage }}" === "Added successfully!") {
                const addModalEvent = new CustomEvent('open-modal', {
                    detail: 'add-successfully-modal'
                });
                window.dispatchEvent(addModalEvent);
            } else if ("{{ $successMessage }}" === "Deleted successfully!") {
                const deleteModalEvent = new CustomEvent('open-modal', {
                    detail: 'delete-successfully-modal'
                });
                window.dispatchEvent(deleteModalEvent);
            }
        @endif

        @if ($errors->any())
            const errorModalEvent = new CustomEvent('open-modal', {
                detail: 'error-modal'
            });
            window.dispatchEvent(errorModalEvent);
        @endif
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('downloadButton').addEventListener('click', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

            const errorModalEvent = new CustomEvent('close-modal', {
                detail: 'download-data-modal'
            });
            window.dispatchEvent(errorModalEvent);

            setTimeout(function() {
                // Loading screen
                document.getElementById('loadingOverlay').classList.replace('flex', 'hidden');
            }, 2000);
        });
    });
</script>
