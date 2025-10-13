<section>
    <header>
        <p class="text-lg font-medium text-sky-900">
            *@lang('digital-twin.newCampaign.humanFactors'):
        </p>
    </header>

    @if ($humanFactors->isEmpty())
        <p class="text-center">*@lang('digital-twin.noHumanFactors')</p>
    @else
        <!-- Dropdown with checkboxes -->
        <div class="relative mt-1">
            <button type="button"
                class="block w-full p-2 h-10 sm:w-1/2 text-left bg-white border border-sky-700 rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 min-w-[200px]"
                id="humanFactorsDropdownBtn">
                <div class="w-full max-w-1/2 overflow-hidden">
                    <span id="humanFactorsPreview" class="block truncate">@lang('digital-twin.noHumanFactorsSelected')</span>
                </div>
            </button>

            <div id="humanFactorsDropdown"
                class="absolute left-0 w-full sm:w-1/2 mt-1 bg-white border border-sky-700 rounded-md shadow-lg hidden z-10 max-h-[300px] overflow-y-auto">
                <div class="p-2">
                    @foreach ($humanFactors as $humanFactor)
                        <div class="tooltip w-full">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="humanFactors[]" value="{{ $humanFactor->id }}"
                                    class="humanFactors-checkbox" data-name="{{ $humanFactor->name }}">
                                <span>{{ $humanFactor->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="p-2 flex justify-between">
                    <button type="button" id="humanFactorsSelectAllBtn" class="text-sm text-blue-600 hover:underline">
                        @lang('general.selectAll')
                    </button>
                    <button type="button" id="humanFactorsClearAllBtn" class="text-sm text-red-600 hover:underline">
                        @lang('general.clearAll')
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>

<script>
    // Toggle dropdown visibility
    document.getElementById('humanFactorsDropdownBtn').addEventListener('click', function() {
        const dropdown = document.getElementById('humanFactorsDropdown');
        dropdown.classList.toggle('hidden');
    });

    // Update preview when items are selected/deselected
    document.querySelectorAll('.humanFactors-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedHumanFactors();
        });
    });

    // Select all checkboxes
    document.getElementById('humanFactorsSelectAllBtn').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.humanFactors-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = true);
        updateSelectedHumanFactors();
    });

    // Clear all checkboxes
    document.getElementById('humanFactorsClearAllBtn').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.humanFactors-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        updateSelectedHumanFactors();
    });

    // Update the selected items preview inside the button
    function updateSelectedHumanFactors() {
        const selectedItems = [];
        const checkboxes = document.querySelectorAll('.humanFactors-checkbox:checked');

        checkboxes.forEach(checkbox => {
            selectedItems.push(checkbox.getAttribute('data-name'));
        });

        const preview = document.getElementById('humanFactorsPreview');
        if (selectedItems.length > 0) {
            preview.textContent = selectedItems.join(', ');
        } else {
            preview.textContent = '@lang('digital-twin.noHumanFactorsSelected')';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('humanFactorsDropdown');
        const dropdownBtn = document.getElementById('humanFactorsDropdownBtn');
        if (!dropdown.contains(event.target) && !dropdownBtn.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
