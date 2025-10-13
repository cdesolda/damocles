<section class="text-sky-800">
    <!-- Delete phishing campaign -->
    <p class="text-xl font-semibold text-center">
        @lang('email.modals.delete-emails')
    </p>
    <div class="pt-4">
        <form id="deleteForm" data-id="" action="" method="POST">
            @csrf
            @method('DELETE')
            <p class="pb-4">@lang('email.modals.deleteMessage-emails')</p>
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">@lang('general.cancel')</x-secondary-button>
                <x-danger-button type="submit">@lang('general.confirm')</x-danger-button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButton = document.querySelectorAll('[data-id]');

        deleteButton.forEach(button => {
            button.addEventListener('click', function() {

                const ids = button.getAttribute('data-id');
                const deleteForm = document.getElementById('deleteForm');
                const actionUrl =
                    "{{ route('emails.multiple.destroy', ['emails' => 'ids']) }}";

                deleteForm.setAttribute('data-id', ids);
                deleteForm.action = actionUrl.replace('ids', ids);

            });
        });

        document.getElementById('deleteForm').addEventListener('submit', function(event) {

            const ids = this.getAttribute('data-id');
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
