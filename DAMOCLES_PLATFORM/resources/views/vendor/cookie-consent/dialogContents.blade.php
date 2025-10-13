<div class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 pb-2 z-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="p-4 md:p-2 rounded-md bg-white border border-sky-800 shadow-lg">
            <div class="flex items-center justify-between flex-wrap">
                <div class="max-w-full flex-1 items-center md:w-0 md:inline">
                    <p class="md:ml-3 text-sky-800 cookie-consent__message">
                        {!! trans('cookie-consent::texts.message') !!}
                        <a href="{{ route('cookie-policy') }}" class="text-primary-600 underline">@lang('Cookie Policy')</a> and
                        <a href="{{ route('privacy-policy') }}" class="text-primary-600 underline">@lang('Privacy Policy')</a>.

                    </p>
                </div>
                <div class="mt-2 flex-shrink-0 w-full sm:mt-0 sm:w-auto">
                    <x-primary-button
                        class="js-cookie-consent-agree cookie-consent__agree">
                        {{ trans('cookie-consent::texts.agree') }}
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>
</div>
