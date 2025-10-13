<section class="text-sky-800">
    <!-- Stop training campaign -->
    <p class="text-xl font-semibold text-center">
        @lang('training-campaign.modals.stop')
    </p>
    <div class="pt-4">
        <form id="stopForm" data-id=""
            action="{{ route('training-campaign.change-state', ['trainingCampaign' => 'id', 'state' => 'Completed']) }}"
            method="POST">
            @csrf
            @method('post')
            <p class="pb-4">@lang('training-campaign.modals.stopMessage')</p>
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
                const stopForm = document.getElementById('stopForm');
                const actionUrl =
                    "{{ route('training-campaign.change-state', ['trainingCampaign' => 'id', 'state' => 'Completed']) }}";

                stopForm.setAttribute('data-id', id);
                stopForm.action = actionUrl.replace('id', id);
            });
        });

        document.getElementById('stopForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
