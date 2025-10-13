<section>
    @if ($persuasions->isEmpty())
        <p class="text-center">@lang('general.NoFilter')</p>
    @else
        <x-input-label for="persuasionSelect" class="text-md block font-medium text-sky-700" :value="__('email.filterBy.persuasion')" />
        <!-- Dropdown with checkboxes -->
        <div class="relative mt-1">
            <button type="button"
                class="block w-full p-2 h-10 text-left bg-white border border-sky-700 rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 min-w-[200px]"
                id="persuasionDropdownBtn">
                <span id="persuasionPreview">@lang('general.NoFilter')</span>
            </button>

            <div id="persuasionDropdown"
                class="absolute left-0 w-full mt-1 bg-white border border-sky-700 rounded-md shadow-lg hidden z-10">
                <div class="p-2">
                    @foreach ($persuasions as $persuasion)
                        <div class="tooltip w-full">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="persuasions[]" value="{{ $persuasion->description }}"
                                    class="persuasion-checkbox" data-name="{{ $persuasion->description }}">
                                <span>{{ $persuasion->description }}</span>
                            </label>
                            <span class="tooltiptext">{{ $persuasion->tooltip }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="p-2 flex justify-between">
                    <button type="button" id="persuasionSelectAllBtn" class="text-sm text-blue-600 hover:underline">
                        @lang('general.selectAll')
                    </button>
                    <button type="button" id="persuasionClearAllBtn" class="text-sm text-red-600 hover:underline">
                        @lang('general.clearAll')
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>

<script>
    // Toggle dropdown visibility
    document.getElementById('persuasionDropdownBtn').addEventListener('click', function() {
        const dropdown = document.getElementById('persuasionDropdown');
        dropdown.classList.toggle('hidden');
    });

    // Update preview when items are selected/deselected
    document.querySelectorAll('.persuasion-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedItems();
        });
    });

    // Select all checkboxes
    document.getElementById('persuasionSelectAllBtn').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.persuasion-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = true);
        updateSelectedItems();
    });

    // Clear all checkboxes
    document.getElementById('persuasionClearAllBtn').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.persuasion-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        updateSelectedItems();
    });

    // Update the selected items preview inside the button
    function updateSelectedItems() {
        const selectedItems = [];
        const checkboxes = document.querySelectorAll('.persuasion-checkbox:checked');

        checkboxes.forEach(checkbox => {
            selectedItems.push(checkbox.getAttribute('data-name'));
        });

        const preview = document.getElementById('persuasionPreview');
        if (selectedItems.length > 0) {
            preview.textContent = selectedItems.join(', ');
        } else {
            preview.textContent = '@lang('general.NoFilter')';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('persuasionDropdown');
        const dropdownBtn = document.getElementById('persuasionDropdownBtn');
        if (!dropdown.contains(event.target) && !dropdownBtn.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
