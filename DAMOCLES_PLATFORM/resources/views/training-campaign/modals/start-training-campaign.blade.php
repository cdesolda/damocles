<section class="text-sky-800">
    <!-- Start training campaign -->
    <p class="text-xl font-semibold text-center">
        @lang('training-campaign.modals.start')
    </p>
    <div class="pt-4">
        <form id="startForm" data-id=""
            action="{{ route('training-campaign.change-state', ['trainingCampaign' => 'id', 'state' => 'Live']) }}"
            method="POST">
            @csrf
            @method('post')
            <p class="pb-4">@lang('training-campaign.modals.startMessage')</p>
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">@lang('general.cancel')</x-secondary-button>
                <x-primary-button type="submit">@lang('general.confirm')</x-primary-button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startButton = document.querySelectorAll('[data-id]');

        startButton.forEach(button => {
            button.addEventListener('click', function() {
                const id = button.getAttribute('data-id');
                const startForm = document.getElementById('startForm');
                const actionUrl =
                    "{{ route('training-campaign.change-state', ['trainingCampaign' => 'id', 'state' => 'Live']) }}";

                startForm.setAttribute('data-id', id);
                startForm.action = actionUrl.replace('id', id);
            });
        });

        document.getElementById('startForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
