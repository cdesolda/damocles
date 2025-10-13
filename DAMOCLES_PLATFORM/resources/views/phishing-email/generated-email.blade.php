<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to phishing campaign -->
            <a href="{{ route('emails.new') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('emails.index') }}">@lang('email.emails')</a></li>
                <li>/</li>
                <li><a href="{{ route('emails.new') }}">@lang('email.new')</a></li>
                <li>/</li>
                <li>@lang('phishing-campaign.generated.emailGenerated')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

                <div class="flex flex-row justify-around items-center pb-6">
                    <!-- Circles which indicates the steps of the creation -->
                    <div class="flex flex-row justify-center items-center py-2">
                        <span class="status"></span>
                        <span class="status active"></span>
                    </div>
                </div>

                @php
                    if (count($bodies) == 0) {
                        // Error modal case
                        $modalId = 'error-modal';
                        $modalTitle = 'Not all emails have been generated!';
                        $modalMessage = __('phishing-campaign.generated.errorGeneratedModal');
                    } elseif ($numberEmails != count($bodies)) {
                        // Error modal not all case
                        $modalId = 'error-not-all-modal';
                        $modalTitle = 'Not all emails have been generated!';
                        $modalMessage = __('phishing-campaign.generated.notAllGeneratedModal');
                    } else {
                        // Edit modal case
                        $modalId = 'edit-modal';
                        $modalTitle = 'Edit emails generated!';
                        $modalMessage = __('phishing-campaign.generated.okGeneratedModal');
                        $additionalMessage = __('phishing-campaign.generated.editModal');
                    }
                @endphp

                <!-- Common Modal -->
                <x-modal name="modal" id="{{ $modalId }}" title="{{ $modalTitle }}" :show="true">
                    <div class="p-4 rounded-lg relative text-center text-sky-800">
                        <p class="text-xl font-semibold text-red-700 pb-8">
                            {{ $modalMessage }}
                        </p>

                        @isset($additionalMessage)
                            <p class="pb-4">{{ $additionalMessage }}</p>
                        @endisset

                        <div class="flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                @lang('general.close')
                            </x-secondary-button>
                        </div>
                    </div>
                </x-modal>

                @php
                    $editMode = 'edit';
                @endphp
                @include('phishing-email.partials.edit-emails')

            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var saveEmailsForm = document.getElementById('saveEmailsForm');
        if (saveEmailsForm) {
            saveEmailsForm.addEventListener('submit', function(event) {
                var subjects = document.querySelectorAll('textarea[id^="subject-"]');
                subjects.forEach(function(textarea, index) {
                    var hiddenInput = document.getElementById('hidden-subject-' + index);
                    if (hiddenInput) {
                        hiddenInput.value = textarea.value;
                    }
                });

                var bodies = document.querySelectorAll('textarea[id^="body-"]');
                bodies.forEach(function(textarea, index) {
                    var hiddenInput = document.getElementById('hidden-body-' + index);
                    if (hiddenInput) {
                        hiddenInput.value = textarea.value;
                    }
                });

                var explanations = document.querySelectorAll('textarea[id^="explanation-"]');
                explanations.forEach(function(textarea, index) {
                    var hiddenInput = document.getElementById('hidden-explanation-' + index);
                    if (hiddenInput) {
                        hiddenInput.value = textarea.value;
                    }
                });

                // Loading screen
                document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
            });
        }
    });
</script>
