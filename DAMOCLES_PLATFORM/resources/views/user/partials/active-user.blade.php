<section class="text-sky-800">
    <!-- Active user change -->
    <p class="text-xl font-semibold text-center">@lang('user.partials.active.active')</p>
    @if (isset($user))
        <div class="pt-4">
            <form id="activeForm" data-id="" action="{{ route('user.updateActive', ['user' => 'id']) }}" method="GET">
                <p class="pb-4">@lang('user.partials.active.activeMessage')</p>

                <div class="flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">@lang('general.cancel')</x-secondary-button>
                    <x-primary-button type="submit">@lang('general.confirm')</x-primary-button>
                </div>
            </form>
        </div>
    @else
        <p class="text-center text-lg text-sky-700">@lang('user.partials.update.noUsers')</p>
    @endif
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('[data-id]');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const id = button.getAttribute('data-id');
                const activeForm = document.getElementById('activeForm');
                let actionUrl = "{{ route('user.updateActive', ['user' => 'id']) }}";

                activeForm.setAttribute('data-id', id);
                activeForm.action = actionUrl.replace('id', id);
            });
        });

        document.getElementById('activeForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
