<section>
    <!-- Delete human factor -->
    <div class="text-sky-800">
        <p class="text-lg font-medium">
            @lang('phishing-campaign.partials.humanfactor.delete'):
        </p>
        @if ($humanfactors->isEmpty())
            <p class="text-center">@lang('phishing-campaign.partials.humanfactor.noHumanFactorsDelete')</p>
        @else
            <form id="deleteHumanFactorForm" action="{{ route('humanFactor.destroy', ['humanfactor' => 'humanfactor']) }}"
                method="POST">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <x-input-label for="deleteHumanFactorSelect" class="text-md block font-medium text-sky-700"
                        :value="__('phishing-campaign.partials.humanfactor.valueDelete')" />
                    <select id="deleteHumanFactorSelect" name="humanfactor"
                        class="mt-1 block w-full py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
                        @foreach ($humanfactors as $humanfactor)
                            <option value="{{ $humanfactor->id }}">{{ $humanfactor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <x-primary-button type="submit">@lang('phishing-campaign.partials.humanfactor.deleteButton')</x-primary-button>
                </div>
            </form>
        @endif

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteHumanFactorForm');
        const select = document.getElementById('deleteHumanFactorSelect');

        select.addEventListener('change', function() {
            const selectedHumanFactorId = select.value;
            const actionUrl = "{{ route('humanFactor.destroy', ['humanfactor' => 'id']) }}";
            deleteForm.action = actionUrl.replace('id', selectedHumanFactorId);
        });

        document.getElementById('deleteHumanFactorForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
