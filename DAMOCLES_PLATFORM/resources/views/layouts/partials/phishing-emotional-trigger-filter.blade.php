<section>
    @if ($emotionalTriggers->isEmpty())
        <p class="text-center">@lang('phishing-campaign.partials.emotionalTrigger.noEmotionalTrigger')</p>
    @else
        <x-input-label for="emotionalTriggerSelect" class="text-md block font-medium text-sky-700" :value="__('email.filterBy.emotionalTrigger')" />

        <!-- Dropdown with checkboxes -->
        <div class="relative mt-1">
            <button type="button"
                class="block w-full p-2 text-left bg-white border border-sky-700 rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 min-w-[200px]"
                id="emotionalTriggerDropdownBtn">
                <span id="emotionalTriggerPreview">@lang('general.NoFilter')</span>
            </button>

            <div id="emotionalTriggerDropdown"
                class="absolute left-0 w-full mt-1 bg-white border border-sky-700 rounded-md shadow-lg hidden z-10">
                <div class="p-2">
                    @foreach ($emotionalTriggers as $emotionalTrigger)
                        <div class="tooltip w-full">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="emotionalTriggers[]"
                                    value="{{ $emotionalTrigger->description }}" class="emotional-trigger-checkbox"
                                    data-name="{{ $emotionalTrigger->description }}">
                                <span>{{ $emotionalTrigger->description }}</span>
                            </label>
                            <span class="tooltiptext">{{ $emotionalTrigger->tooltip }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="p-2 flex justify-between">
                    <button type="button" id="emotionalTriggersSelectAllBtn"
                        class="text-sm text-blue-600 hover:underline">
                        @lang('general.selectAll')
                    </button>
                    <button type="button" id="emotionalTriggersClearAllBtn"
                        class="text-sm text-red-600 hover:underline">
                        @lang('general.clearAll')
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>

<script>
    // Toggle dropdown visibility for Emotional Trigger
    document.getElementById('emotionalTriggerDropdownBtn').addEventListener('click', function() {
        const dropdown = document.getElementById('emotionalTriggerDropdown');
        dropdown.classList.toggle('hidden');
    });

    // Update preview when items are selected/deselected for Emotional Trigger
    document.querySelectorAll('.emotional-trigger-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedItemsEmotionalTrigger();
        });
    });

    // Select all checkboxes for Emotional Trigger
    document.getElementById('emotionalTriggersSelectAllBtn').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.emotional-trigger-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = true);
        updateSelectedItemsEmotionalTrigger();
    });

    // Clear all checkboxes for Emotional Trigger
    document.getElementById('emotionalTriggersClearAllBtn').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.emotional-trigger-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        updateSelectedItemsEmotionalTrigger();
    });

    // Update the selected items preview inside the button for Emotional Trigger
    function updateSelectedItemsEmotionalTrigger() {
        const selectedItems = [];
        const checkboxes = document.querySelectorAll('.emotional-trigger-checkbox:checked');

        checkboxes.forEach(checkbox => {
            selectedItems.push(checkbox.getAttribute('data-name'));
        });

        const preview = document.getElementById('emotionalTriggerPreview');
        if (selectedItems.length > 0) {
            preview.textContent = selectedItems.join(', ');
        } else {
            preview.textContent = '@lang('general.NoFilter')';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('emotionalTriggerDropdown');
        const dropdownBtn = document.getElementById('emotionalTriggerDropdownBtn');
        if (!dropdown.contains(event.target) && !dropdownBtn.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
