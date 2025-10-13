<section>
    <!-- Delete threat -->
    <div class="text-sky-800">
        <p class="text-lg font-medium">
            @lang('phishing-campaign.partials.threat.delete'):
        </p>
        @if ($threats->isEmpty())
            <p class="text-center">@lang('phishing-campaign.partials.threat.noThreatsDelete')</p>
        @else
            <form id="deleteThreatForm" action="{{ route('threat.destroy', ['threat' => 'threat']) }}" method="POST">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <x-input-label for="deleteThreatSelect" class="text-md block font-medium text-sky-700"
                        :value="__('phishing-campaign.partials.threat.valueDelete')" />
                    <select id="deleteThreatSelect" name="threat"
                        class="mt-1 block w-full py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
                        @foreach ($threats as $threat)
                            <option value="{{ $threat->id }}">{{ $threat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <x-primary-button type="submit">@lang('phishing-campaign.partials.threat.deleteButton')</x-primary-button>
                </div>
            </form>
        @endif

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteThreatForm');
        const select = document.getElementById('deleteThreatSelect');

        select.addEventListener('change', function() {
            const selectedThreatId = select.value;
            const actionUrl = "{{ route('threat.destroy', ['threat' => 'id']) }}";
            deleteForm.action = actionUrl.replace('id', selectedThreatId);
        });

        document.getElementById('deleteThreatForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
