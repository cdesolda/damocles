<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('training-campaign.option') }}">@lang('training-campaign.option.option')</a></li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 text-sky-900">

            <div class="bg-white sm:overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 space-y-6">
                    <p class="text-lg font-medium text-sky-900">
                        @lang('training-campaign.option.option'):
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
                        <p class="font-semibold text-xl">@lang('training-campaign.option.edit'):</p>

                        <div class="flex flex-row w-full items-center sm:px-4">
                            <div class="w-1/2">
                                <p>@lang('training-campaign.option.prompt'):</p>
                            </div>
                            <div class="flex justify-end w-1/2">
                                <x-primary-button class="cursor-pointer" x-data=""
                                    @click="$dispatch('open-modal', 'prompt-edit-modal')">
                                    @lang('training-campaign.option.editPrompts')
                                </x-primary-button>
                            </div>
                        </div>

                        <div class="flex flex-row w-full items-center sm:px-4">
                            <div class="w-1/2">
                                <p>@lang('training-campaign.option.fakeUser'):</p>
                            </div>
                            <div class="flex justify-end w-1/2">
                                <x-primary-button class="cursor-pointer" x-data=""
                                    @click="$dispatch('open-modal', 'fake-user-edit-modal')">
                                    @lang('training-campaign.option.editFakeUsers')
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
            @lang('training-campaign.option.downloadModalMessage')
        </p>

        <div class="flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
            <a href="{{ route('training-campaign.download-all-data-csv') }}">
                <x-primary-button id="downloadButton" type="button">@lang('general.confirm')</x-primary-button>
            </a>
        </div>
    </div>
</x-modal>

<!-- Prompt edit modal -->
<x-modal name="prompt-edit-modal" id="prompt-edit-modal" title="Prompt edit" :show="false">
    <div class="p-4 rounded-lg relative space-y-6 text-sky-800">
        <p class="text-xl font-semibold">
            @lang('training-campaign.option.editPrompts')
        </p>
        <!-- Add new prompt -->
        @include('training-campaign.partials.prompt-partials.add-prompt')

        <!-- Delete prompt -->
        @include('training-campaign.partials.prompt-partials.delete-prompt')

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Fake user edit modal -->
<x-modal name="fake-user-edit-modal" id="fake-user-edit-modal" title="Prompt edit" :show="false">
    <div class="p-4 rounded-lg relative space-y-6 text-sky-800">
        <p class="text-xl font-semibold">
            @lang('training-campaign.option.editFakeUsers')
        </p>
        <!-- Add new fake user -->
        @include('training-campaign.partials.fake-user-partials.add-fake-user')

        <!-- Delete fake user -->
        @include('training-campaign.partials.fake-user-partials.delete-fake-user')

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Add successfully message -->
<x-modal name="add-successfully-modal" id="add-successfully-modal" title="Add successfully modal" :show="false">
    <div class="p-4 rounded-lg relative text-center text-sky-800">
        <p class="text-xl font-semibold pb-8">
            @lang('training-campaign.option.addSucc')
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
            @lang('training-campaign.option.deleteSucc')
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
            @lang('training-campaign.option.tryAgain')
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
