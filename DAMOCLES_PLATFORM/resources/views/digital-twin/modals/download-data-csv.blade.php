<section class="text-sky-800">
    <!-- Download data csv -->
    <p class="text-xl font-semibold text-center">
        @lang('digital-twin.downloadModalMessage')
    </p>
    <div class="pt-4">
        <form id="downloadForm" data-id=""
            action="{{ route('digital-twins.download-data-csv', ['digitalTwinsCampaign' => 'id']) }}" method="GET">
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">@lang('general.cancel')</x-secondary-button>
                <x-primary-button type="submit">@lang('general.confirm')</x-primary-button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stopButton = document.querySelectorAll('[data-id]');

        stopButton.forEach(button => {
            button.addEventListener('click', function() {

                const id = button.getAttribute('data-id');

                const downloadForm = document.getElementById('downloadForm');
                const actionUrl =
                    "{{ route('digital-twins.download-data-csv', ['digitalTwinsCampaign' => 'id']) }}";

                downloadForm.setAttribute('data-id', id);
                downloadForm.action = actionUrl.replace('id', id);
            });
        });

        document.getElementById('downloadForm').addEventListener('submit', function(event) {
            const closeModalEvent = new CustomEvent('close-modal', {
                detail: 'download-data-csv-modal'
            });
            window.dispatchEvent(closeModalEvent);

            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');

            setTimeout(function() {
                // Loading screen
                document.getElementById('loadingOverlay').classList.replace('flex', 'hidden');
            }, 2000);
        });
    });
</script>
