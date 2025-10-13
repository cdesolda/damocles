<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

            <p class="font-semibold text-xl text-sky-900">
                {{ $Title }}:
            </p>
            @if (!$emails->isEmpty())

                <!-- Filters -->
                <p class="font-medium">@lang('general.filters'):</p>
                <div class="flex flex-row flex-wrap gap-2 pb-4">
                    <div class="flex flex-row w-80 gap-3 items-center relative">
                        <!-- Filter -->
                        <label for="emailFilter" class="block text-sm font-bold text-sky-700">@lang('general.search')</label>
                        <input type="text" id="emailFilter" name="emailFilter"
                            class="p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                            placeholder="@lang('general.enterSearchEmail')">
                        <button id="clear-email-filter"
                            class="hidden absolute right-2 top-1/2 transform -translate-y-1/2 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 0a10 10 0 0 1 7.071 2.929A10 10 0 0 1 20 10a10 10 0 0 1-2.929 7.071A10 10 0 0 1 10 20a10 10 0 0 1-7.071-2.929A10 10 0 0 1 0 10a10 10 0 0 1 2.929-7.071A10 10 0 0 1 10 0zm3.536 5.05a.5.5 0 0 1 .708.708L10.707 10l3.536 3.536a.5.5 0 0 1-.708.708L10 10.707l-3.536 3.536a.5.5 0 1 1-.708-.708L9.293 10 5.757 6.464a.5.5 0 0 1 .708-.708L10 9.293l3.536-3.536z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-4 items-center justify-start w-full">
                        <div class="w-full sm:w-auto">
                            @include('layouts.partials.phishing-topic-filter')
                        </div>
                        <div class="w-full sm:w-auto">
                            @include('layouts.partials.phishing-persuasion-filter')
                        </div>
                        <div class="w-full sm:w-auto">
                            @include('layouts.partials.phishing-emotional-trigger-filter')
                        </div>
                        <div class="w-full sm:w-auto">
                            @include('layouts.partials.email-groups-filter')
                        </div>
                    </div>
                </div>
                <div class="pt-2 pb-4 shadow">
                    <p class="font-bold pb-2 pl-2">@lang('general.totalEmail'): <span id="totalSelectedEmails">0</span>
                    </p>
                    <table id="email-table" class="w-full text-center">
                        <thead class="bg-gray-100">
                            <tr class="border-b-2 border-gray-300">
                                <th class="py-2 px-4"></th>
                                <th class="py-2 px-4">@lang('email.subject')</th>
                                <th class="py-2 px-4">@lang('email.body')</th>
                                <th class="py-2 px-4">@lang('email.topic')</th>
                                <th class="py-2 px-4">@lang('email.persuasions')</th>
                                <th class="py-2 px-4">@lang('email.emotionalTriggers')</th>
                                <th class="py-2 px-4">@lang('general.updatedAt')</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($emails as $email)
                                <tr class="hover:bg-gray-100 border-b border-gray-200" data-topic="{{ $email->topic }}">
                                    <td class="py-2 px-4">
                                        <input id="{{ $email->id }}" type="checkbox" class="email-checkbox"
                                            data-email-id="{{ $email->id }}">
                                    </td>
                                    <td class="py-2 px-4">{{ Str::limit($email->subject, 100) }}</td>
                                    <td class="py-2 px-4">{{ Str::limit($email->body, 100) }}</td>
                                    <td class="py-2 px-4">{{ $email->topic }}</td>
                                    <td class="py-2 px-4">
                                        {{ is_array($email->persuasions) ? implode(', ', $email->persuasions) : $email->persuasions ?? 'N/A' }}
                                    </td>
                                    <td class="py-2 px-4">
                                        {{ is_array($email->emotional_triggers) ? implode(', ', $email->emotional_triggers) : $email->emotional_triggers ?? 'N/A' }}
                                    </td>
                                    <td class="py-2 px-4">{{ $email->updated_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Paginator controls -->
                    @include('layouts.partials.pagination-controls')
                </div>

                <form id="saveEmailsForm" action="{{ $submitRoute }}" method="POST">
                    @csrf
                    <!-- Pass validated data as hidden field -->
                    <input type="hidden" name="validatedData" value="{{ json_encode($validatedData) }}">
                    <input type="hidden" name="emailsIds" id="emailsIdsInput">

                    <div class="flex flex-row justify-around items-center pt-6">
                        <div class="flex w-1/3">
                        </div>
                        <div class="flex flex-row w-1/3 justify-center items-center">
                            @for ($i = 1; $i <= $total; $i++)
                                <span class="status {{ $i === $active ? 'active' : '' }}"></span>
                            @endfor
                        </div>
                        <div class="flex w-1/3 justify-center">
                            <x-primary-button type="submit">{{ $Finish }}</x-primary-button>
                        </div>
                    </div>
                </form>
            @else
                <p class="text-center">@lang('general.noEmails')</p>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const topicSelect = document.getElementById('topicIdSelect');
        const persuasionSelect = document.getElementById('persuasionPreview');
        const emotionalTriggerSelect = document.getElementById('emotionalTriggerPreview');
        const groupSelect = document.getElementById('groupIdSelect');

        const rows = document.querySelectorAll('#email-table tbody tr');

        const checkboxes = document.querySelectorAll('.persuasion-checkbox');

        topicSelect.addEventListener('change', function() {
            filterRows();
        });

        if (groupSelect) {
            groupSelect.addEventListener('change', function() {
                filterRows();
            });
        }

        document.getElementById('persuasionDropdownBtn').addEventListener('click', function() {
            filterRows();
        });

        let debounceTimeout;

        document.querySelectorAll('.persuasion-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                filterRows();
            });
        });

        document.querySelectorAll('.emotional-trigger-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                filterRows();
            });
        });

        document.getElementById('persuasionSelectAllBtn').addEventListener('click', function() {
            filterRows();
        });

        document.getElementById('persuasionClearAllBtn').addEventListener('click', function() {
            filterRows();
        });

        document.getElementById('emotionalTriggersSelectAllBtn').addEventListener('click', function() {
            filterRows();
        });

        document.getElementById('emotionalTriggersClearAllBtn').addEventListener('click', function() {
            filterRows();
        });


        document.querySelectorAll('.email-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedEmailsCount();
            });
        });

        function updateSelectedEmailsCount() {
            const selectedCount = document.querySelectorAll('.email-checkbox:checked').length;
            document.getElementById('totalSelectedEmails').textContent = selectedCount;
        }

        function filterRows() {
            const selectedTopic = topicSelect.value;
            let selectedPersuasions = persuasionSelect.textContent.trim();
            let selectedEmotionalTriggers = emotionalTriggerSelect.textContent.trim();
            const selectedGroup = groupSelect.options[groupSelect.selectedIndex].getAttribute("data-group");

            selectedPersuasions = selectedPersuasions === "@lang('general.NoFilter')" ? '' : selectedPersuasions;
            selectedEmotionalTriggers = selectedEmotionalTriggers === "@lang('general.NoFilter')" ? '' :
                selectedEmotionalTriggers;

            rows.forEach(function(row) {
                const rowTopic = row.getAttribute('data-topic');
                const rowPersuasions = row.cells[4].textContent.trim();
                const rowEmotionalTriggers = row.cells[5].textContent.trim();
                const checkbox = row.querySelector('.email-checkbox');
                const rowGroup = checkbox ? checkbox.getAttribute('data-email-id') : null;

                const topicMatch = !selectedTopic || rowTopic === selectedTopic;
                const persuasionMatch = !selectedPersuasions || rowPersuasions === selectedPersuasions;
                const emotionalTriggerMatch = !selectedEmotionalTriggers || rowEmotionalTriggers ===
                    selectedEmotionalTriggers;
                const groupMatch = !selectedGroup || selectedGroup.split(",").includes(rowGroup);

                if (topicMatch && persuasionMatch && emotionalTriggerMatch && groupMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            updateSelectedEmailsCount();
        };

    });
</script>
