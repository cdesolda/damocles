<section class="text-sky-800">
    <!-- Duplicate training campaign -->
    <p class="text-xl font-semibold text-center">
        @lang('training-campaign.modals.duplicate')
    </p>
    <div class="pt-4">
        <form id="duplicateForm" data-id=""
            action="{{ route('training-campaign.duplicate', ['trainingCampaign' => 'id']) }}" method="GET">
            <p class="pb-4">@lang('training-campaign.modals.duplicateMessage')</p>
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">@lang('general.cancel')</x-secondary-button>
                <x-primary-button type="submit">@lang('general.confirm')</x-primary-button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const duplicateButton = document.querySelectorAll('[data-id]');

        duplicateButton.forEach(button => {
            button.addEventListener('click', function() {
                const id = button.getAttribute('data-id');
                const duplicateForm = document.getElementById('duplicateForm');
                const actionUrl =
                    "{{ route('training-campaign.duplicate', ['trainingCampaign' => 'id']) }}";

                duplicateForm.setAttribute('data-id', id);
                duplicateForm.action = actionUrl.replace('id', id);
            });
        });

        document.getElementById('duplicateForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
