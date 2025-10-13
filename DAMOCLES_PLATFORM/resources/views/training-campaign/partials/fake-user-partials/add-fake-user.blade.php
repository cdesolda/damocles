<section>
    <!-- Add new fake user -->
    <div class="text-sky-800">
        <p class="text-lg font-medium">
            @lang('training-campaign.partials.fakeUser.new'):
        </p>

        <form id="addFakeUserForm" method="post" action="{{ route('fake-user.create') }}" class="space-y-2">
            @csrf
            @method('post')

            <div class="flex flex-col gap-2">
                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('auth.register.name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                        required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Surname -->
                <div>
                    <x-input-label for="surname" :value="__('auth.register.surname')" />
                    <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname"
                        :value="old('surname')" required autofocus autocomplete="surname" />
                    <x-input-error :messages="$errors->get('surname')" class="mt-2" />
                </div>

                <!-- Gender -->
                <div>
                    <x-input-label for="gender" :value="__('auth.register.gender')" />
                    <select id="gender" name="gender"
                        class="mt-1 block border-sky-800 focus:border-sky-900 focus:ring-sky-800 rounded-md shadow-sm w-full"
                        required autofocus autocomplete="gender">
                        <option value="Male">@lang('auth.register.male')</option>
                        <option value="Female">@lang('auth.register.female')</option>
                        <option value="Other">@lang('auth.register.other')</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

                <!-- Date of Birth -->
                <div>
                    <x-input-label for="dob" :value="__('auth.register.dob')" />
                    <x-text-input id="dob" class="block mt-1 w-full" type="text" name="dob"
                        :value="old('dob')" required autofocus autocomplete="dob" placeholder="DD/MM/YYYY" />
                    <x-input-error :messages="$errors->get('dob')" class="mt-2" />
                </div>

                <!-- Company Role -->
                <div>
                    <x-input-label for="company_role" :value="__('auth.register.companyRole')" />
                    <x-text-input id="company_role" class="block mt-1 w-full" type="text" name="company_role"
                        :value="old('company_role')" required autocomplete="company_role" />
                    <x-input-error :messages="$errors->get('company_role')" class="mt-2" />
                </div>

                <!-- Type -->
                <div class="hidden">
                    <x-input-label for="type" :value="__('auth.register.type')" />
                    <x-text-input id="type" class="block mt-1 w-full" type="text" name="type"
                        :value="'Real'" required autocomplete="type" />
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('auth.register.email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

            </div>
            <div class="flex items-center justify-end ">
                <x-primary-button class="ms-4">
                    @lang('auth.register.register')
                </x-primary-button>
            </div>
        </form>
    </div>
</section>

<x-loading-screen />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addFakeUserForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
