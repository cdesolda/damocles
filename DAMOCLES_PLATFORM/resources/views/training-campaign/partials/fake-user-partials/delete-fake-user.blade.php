<section>
    <!-- Delete fake user -->
    <div class="text-sky-800">
        <p class="text-lg font-medium">
            @lang('training-campaign.partials.fakeUser.delete'):
        </p>
        @if ($users->isEmpty())
            <p class="text-center">@lang('training-campaign.partials.fakeUser.noFakeUsersDelete')</p>
        @else
            <form id="deleteFakeUserForm" action="{{ route('fake-user.destroy', ['user' => 'user']) }}" method="POST">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <x-input-label for="deleteFakeUserSelect" class="text-md block font-medium text-sky-700"
                        :value="__('training-campaign.partials.fakeUser.valueDelete')" />
                    <select id="deleteFakeUserSelect" name="fakeUser"
                        class="mt-1 block w-full py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} {{ $user->surname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <x-primary-button type="submit">@lang('training-campaign.partials.fakeUser.deleteButton')</x-primary-button>
                </div>
            </form>
        @endif

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteFakeUserForm');
        const select = document.getElementById('deleteFakeUserSelect');

        select.addEventListener('change', function() {
            const selectedFakeUserId = select.value;
            const actionUrl = "{{ route('fake-user.destroy', ['user' => 'id']) }}";
            deleteForm.action = actionUrl.replace('id', selectedFakeUserId);
        });

        document.getElementById('deleteFakeUserForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
