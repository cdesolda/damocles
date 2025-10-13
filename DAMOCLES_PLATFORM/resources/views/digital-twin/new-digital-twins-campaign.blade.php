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
                <li><a href="{{ route('digital-twins.new') }}">@lang('digital-twin.new')</a></li>
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
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status"></span>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="font-semibold text-xl">@lang('digital-twin.newCampaign.newTitle')</p>
                    <p class="font-semibold text-sm">@lang('general.mandatoryField')</p>
                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('digital-twin.newCampaign.title'):
                        </p>
                        <input type="text" id="title" name="title"
                            class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full sm:w-2/3"
                            required>
                    </div>

                    <div>
                        <p class="text-lg font-medium text-sky-900">
                            *@lang('digital-twin.newCampaign.description'):
                        </p>
                        <textarea type="text" id="description" name="description"
                            class="border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md w-full" required></textarea>
                    </div>

                    <div>
                        @include('digital-twin.partials.threats')
                    </div>

                    <form id="generateCampaignForm" data-id=""
                        action="{{ route('digital-twins.defineUserPrompt') }}" method="POST">
                        @csrf
                        @method('post')

                        <input type="hidden" name="title" id="titleInput">
                        <input type="hidden" name="description" id="descriptionInput">
                        <input type="hidden" name="threatId" id="threatIdInput">
                        <input type="hidden" name="evaluatorId" id="evaluatorIdInput" value="{{ auth()->user()->id }}">

                        <div class="flex flex-row justify-around items-center pt-6">
                            <div class="flex w-1/3">
                            </div>
                            <!-- Circles which indicates the steps of the creation -->
                            <div class="flex flex-row w-1/3 justify-center items-center">
                                <span class="status active"></span>
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status"></span>
                            </div>
                            <div class="flex w-1/3 justify-center">
                                <x-primary-button id="generateCampaign"
                                    type="submit">@lang('general.next')</x-primary-button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<!-- Error modal -->
<!-- Error data modal -->
<x-modal name="error-data-modal" id="error-data-modal" title="Error data" :show="false">
    <div class="p-6 rounded-lg relative text-center">
        <p class="text-2xl font-semibold text-red-700 pb-8">@lang('general.errorData')</p>
        <x-secondary-button x-on:click="$dispatch('close')">@lang('general.close')</x-secondary-button>
    </div>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const generateCampaignForm = document.getElementById('generateCampaignForm');
        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        const threatIdSelect = document.getElementById('threatIdSelect');
        const errorModal = document.getElementById('error-data-modal');

        generateCampaignForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const title = titleInput.value.trim();
            const description = descriptionInput.value.trim();

            if (title === '' || description === '') {
                showErrorModal();
                return;
            }

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

            const selectedThreat = threatIdSelect ? threatIdSelect.value : '';

            document.getElementById('titleInput').value = title;
            document.getElementById('descriptionInput').value = description;
            document.getElementById('threatIdInput').value = selectedThreat;

            this.submit();
        });

        function showErrorModal() {
            const errorModal = new CustomEvent('open-modal', {
                detail: 'error-data-modal'
            });
            window.dispatchEvent(errorModal);
        }
    });
</script>
