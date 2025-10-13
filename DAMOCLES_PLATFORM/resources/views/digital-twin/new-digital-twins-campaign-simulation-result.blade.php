<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twins campaign -->
            <a href="{{ route('digital-twins.simulation') }}" class="cursor-pointer">
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
                <li><a href="{{ route('digital-twins.simulationResult') }}">@lang('digital-twin.simulationResults')</a></li>
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
                        <span class="status"></span>
                        <span class="status active"></span>
                        <span class="status"></span>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="text-xl font-medium text-sky-900">@lang('digital-twin.simulationResultsTitle')</p>
                    @foreach ($users as $user)
                        <div class="py-4 user-entry" id="user-{{ $user->id }}">
                            <div class="flex flex-col">
                                <div class="flex flex-row gap-1">
                                    <p class="font-bold">@lang('general.user.user'):</p>
                                    <p class="user-name">{{ $user->name }} {{ $user->surname }}</p>
                                </div>
                                <div class="flex flex-row gap-1">
                                    <p class="font-bold">@lang('general.user.gender'):</p>
                                    <p class="user-gender">{{ $user->gender }}</p>
                                </div>
                                <div class="flex flex-row gap-1">
                                    <p class="font-bold">@lang('general.user.email'):</p>
                                    <p class="user-email">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="w-full text-center mb-4">
                                <table id="email-table-{{ $user->id }}" class="w-full text-center mb-4">
                                    <thead class="bg-gray-100">
                                        <tr class="border-b-2 border-gray-300">
                                            <th class="py-2 px-4">@lang('email.subject')</th>
                                            <th class="py-2 px-4">@lang('phishing-campaign.analyseCampaign.opened')</th>
                                            <th class="py-2 px-4">@lang('phishing-campaign.analyseCampaign.clicked')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        @if (count($user->emails) > 0)
                                            @foreach ($user->emails as $email)
                                                <tr class="hover:bg-gray-100 border-b border-gray-200">
                                                    <td class="py-2 px-4 w-1/5">{{ $email['email']->subject }}</td>
                                                    <td class="py-2 px-4 w-2/5">
                                                        <div
                                                            class="group relative {{ $email['opened'] ? 'text-[#DC3545]' : 'text-[#28A745]' }}">
                                                            {!! $email['opened'] ? 'Yes' : 'No' !!}

                                                        </div>
                                                        <div
                                                            class="mt-2 p-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 text-xs w-full max-h-32 overflow-y-auto shadow-sm">
                                                            {{ $email['opened_explanation'] }}
                                                        </div>
                                                    </td>

                                                    <td class="py-2 px-4 w-2/5">
                                                        <div
                                                            class="group relative {{ $email['clicked'] ? 'text-[#DC3545]' : 'text-[#28A745]' }}">
                                                            {!! $email['clicked'] ? 'Yes' : 'No' !!}
                                                        </div>
                                                        <div
                                                            class="mt-2 p-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 text-xs w-full max-h-32 overflow-y-auto shadow-sm">
                                                            {{ $email['clicked_explanation'] }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="py-2 px-4 text-gray-500">
                                                    @lang('digital-twin.noEmails')
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                    <!-- Paginator controls -->
                    <div id="pagination-controls" class="flex justify-around items-center">
                        <div class="flex justify-center w-1/3">
                            <x-primary-button id="prevUser">@lang('general.previous')</x-primary-button>
                        </div>
                        <div class="flex justify-center w-1/3">
                            <span id="userIndicator" class="text-gray-700"></span>
                        </div>
                        <div class="flex justify-center w-1/3">
                            <x-primary-button id="nextUser">@lang('general.next')</x-primary-button>
                        </div>
                    </div>

                </div>
                <form id="generateFakeUsersForm" data-id="" action="{{ route('digital-twins.chooseUsers') }}"
                    method="POST">
                    @csrf
                    @method('post')

                    <div class="flex flex-row justify-around items-center pt-6">
                        <div class="flex w-1/3">
                        </div>
                        <!-- Circles which indicates the steps of the creation -->
                        <div class="flex flex-row w-1/3 justify-center items-center">
                            <span class="status"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                            <span class="status"></span>
                            <span class="status active"></span>
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

    // Tabulation and Filter
    document.addEventListener('DOMContentLoaded', function() {
        const users = @json($users);
        let currentUserIndex = 0;
        const userContainers = document.querySelectorAll(".user-entry");
        const prevUserButton = document.getElementById('prevUser');
        const nextUserButton = document.getElementById('nextUser');
        const userIndicator = document.getElementById('userIndicator');
        const paginationControls = document.getElementById('pagination-controls');

        function toggleNavigationButtons() {
            if (users.length <= 1) {
                prevUserButton.style.display = 'none';
                nextUserButton.style.display = 'none';
            } else {
                prevUserButton.style.display = currentUserIndex > 0 ? 'block' : 'none';
                nextUserButton.style.display = currentUserIndex < users.length - 1 ? 'block' : 'none';
            }
        }

        function showUser(index) {
            userContainers.forEach(function(container, idx) {
                container.style.display = idx === index ? 'block' : 'none';
            });
            userIndicator.textContent = `User: ${index + 1} / ${users.length}`;
            toggleNavigationButtons();
        }

        function resetSearch() {
            filterInput.value = "";
            clearFilterButton.style.display = "none";
            userContainers.forEach(function(container, index) {
                container.style.display = index === 0 ? 'block' : 'none';
            });
            currentUserIndex = 0;
            showUser(currentUserIndex);
            paginationControls.style.display = 'flex';
            noResultsMessage.style.display = 'none';
        }

        prevUserButton.addEventListener('click', function() {
            if (currentUserIndex > 0) {
                currentUserIndex--;
                showUser(currentUserIndex);
            }
        });

        nextUserButton.addEventListener('click', function() {
            if (currentUserIndex < users.length - 1) {
                currentUserIndex++;
                showUser(currentUserIndex);
            }
        });
        showUser(currentUserIndex);
    });
</script>
