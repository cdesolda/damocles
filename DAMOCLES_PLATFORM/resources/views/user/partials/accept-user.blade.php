<section class="text-sky-800">
    <!-- Accept or Decline user -->
    <p class="text-xl font-semibold text-center">@lang('user.partials.accept.accept')</p>
    @if (isset($user))
        <div class="pt-4">
            <form id="acceptForm" action="{{ route('user.updateAccept') }}" method="POST">
                @csrf
                @method('post')

                <p class="pb-4">@lang('user.partials.accept.acceptMessage')</p>

                <div class="hidden">
                    <x-input-label for="id" :value="__('user.partials.update.value.id')" />
                    <x-text-input name="id" id="id" type="text" class="mt-1 block w-full"
                        :value="old('id', $user->id)" required autofocus autocomplete="id" />
                    <x-input-error class="mt-2" :messages="$errors->get('id')" />
                </div>

                <div>
                    <select id="value" name="value"
                        class="mt-1 block border-sky-800 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full"
                        required autofocus autocomplete="value">
                        <option value="1" {{ $user->value === true ? 'selected' : '' }}>
                            @lang('user.partials.accept.accept')
                        </option>
                        <option value="0" {{ $user->value === false ? 'selected' : '' }}>
                            @lang('user.partials.accept.reject')
                        </option>
                    </select>
                    <x-input-error :messages="$errors->get('value')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 mt-4">
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
    function fillAcceptModal(user) {
        const idInput = document.getElementById('id');
        const valueInput = document.getElementById('value');

        idInput.value = user.id;
        valueInput.value = user.value;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('acceptForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
