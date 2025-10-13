<section>
    <header>
        <p class="text-lg font-medium text-sky-900">
            *@lang('digital-twin.newCampaign.demographicAttributes'):
        </p>
    </header>

    @if (empty($demographicAttributes))
        <p class="text-center">@lang('digital-twin.noDemographicAttributes')</p>
    @else
        <!-- Dropdown with checkboxes -->
        <div class="relative mt-1">
            <button type="button"
                class="block w-full p-2 h-10 sm:w-1/2 text-left bg-white border border-sky-700 rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 min-w-[200px]"
                id="demographicDropdownBtn">
                <div class="w-full max-w-1/2 overflow-hidden">
                    <span id="demographicPreview" class="block truncate">@lang('digital-twin.noDemographicAttributesSelected')</span>
                </div>
            </button>

            <div id="demographicDropdown"
                class="absolute left-0 w-full sm:w-1/2 mt-1 bg-white border border-sky-700 rounded-md shadow-lg hidden z-10 max-h-[300px] overflow-y-auto">
                <div class="p-2">
                    @foreach ($demographicAttributes as $demographicAttribute)
                        <div class="tooltip w-full">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="demographicAttributes[]"
                                    value="{{ $demographicAttribute }}" class="demographic-checkbox"
                                    data-name="{{ $demographicAttribute }}">
                                <span>@lang('digital-twin.newCampaign.' . $demographicAttribute)</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="p-2 flex justify-between">
                    <button type="button" id="demographicSelectAllBtn" class="text-sm text-blue-600 hover:underline">
                        @lang('general.selectAll')
                    </button>
                    <button type="button" id="demographicClearAllBtn" class="text-sm text-red-600 hover:underline">
                        @lang('general.clearAll')
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>

<script>
    document.getElementById('demographicDropdownBtn').addEventListener('click', function() {
        const dropdown = document.getElementById('demographicDropdown');
        dropdown.classList.toggle('hidden');
    });

    document.querySelectorAll('.demographic-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedDemographics();
        });
    });

    document.getElementById('demographicSelectAllBtn').addEventListener('click', function() {
        document.querySelectorAll('.demographic-checkbox').forEach(checkbox => checkbox.checked = true);
        updateSelectedDemographics();
    });

    document.getElementById('demographicClearAllBtn').addEventListener('click', function() {
        document.querySelectorAll('.demographic-checkbox').forEach(checkbox => checkbox.checked = false);
        updateSelectedDemographics();
    });

    function updateSelectedDemographics() {
        const selectedItems = [];
        document.querySelectorAll('.demographic-checkbox:checked').forEach(checkbox => {
            selectedItems.push(checkbox.getAttribute('data-name'));
        });

        const preview = document.getElementById('demographicPreview');
        preview.textContent = selectedItems.length > 0 ? selectedItems.join(', ') : '@lang('digital-twin.noDemographicAttributesSelected')';
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('demographicDropdown');
        const dropdownBtn = document.getElementById('demographicDropdownBtn');
        if (!dropdown.contains(event.target) && !dropdownBtn.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
