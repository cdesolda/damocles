<section>
    <!-- Add new threat -->
    <div class="text-sky-800">
        <p class="text-lg font-medium">
            @lang('phishing-campaign.partials.threat.new'):
        </p>
        <form id="addThreatForm" action="{{ route('threat.create') }}" method="POST">
            @csrf
            @method('post')

            <div class="mb-4">
                <input required type="text" id="name" name="name"
                    class="p-2 border border-sky-800 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                    placeholder="@lang('phishing-campaign.partials.threat.placeholder')">
            </div>
            <div class="flex justify-end">
                <x-primary-button type="submit">@lang('phishing-campaign.partials.threat.add')</x-primary-button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addThreatForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
