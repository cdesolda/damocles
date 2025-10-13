<nav x-data="{ open: false }" class="bg-white border-b border-sky-100 w-full">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <p class="text-xl font-extrabold text-sky-900">DAMOCLES</p>
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="md:flex flex-row gap-4 hidden">

                <x-nav-link class="!text-base" :href="url('/') . '#hero'" @click="open = false">
                    @lang('welcome.hero')
                </x-nav-link>
                
                <x-nav-link class="!text-base" :href="url('/') . '#about'" @click="open = false">
                    @lang('welcome.about')
                </x-nav-link>
                
                <x-nav-link class="!text-base" :href="url('/') . '#features'" @click="open = false">
                    @lang('welcome.features')
                </x-nav-link>

                @if (Route::has('login'))
                    @auth
                        <x-nav-link class="!text-base" :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            @lang('dashboard.dashboard')
                        </x-nav-link>
                    @else
                        <x-nav-link class="!text-base" :href="route('login')" :active="request()->routeIs('login')">
                            @lang('welcome.login')
                        </x-nav-link>

                        @if (Route::has('register'))
                            <x-nav-link class="!text-base" :href="route('register')" :active="request()->routeIs('register')">
                                @lang('welcome.register')
                            </x-nav-link>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-sky-800 hover:text-sky-600 hover:bg-sky-100 focus:outline-none focus:bg-sky-100 focus:text-sky-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:px-8">
        <div class="pt-2 pb-1 space-y-1">

            <!-- Navigation Links -->
            <x-responsive-nav-link :href="'#hero'" @click="open = false">
                @lang('welcome.hero')
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="'#about'" @click="open = false">
                @lang('welcome.about')
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="'#features'" @click="open = false">
                @lang('welcome.features')
            </x-responsive-nav-link>

            @if (Route::has('login'))
                @auth
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        @lang('dashboard.dashboard')
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        @lang('welcome.login')
                    </x-responsive-nav-link>

                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                            @lang('welcome.register')
                        </x-responsive-nav-link>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>
