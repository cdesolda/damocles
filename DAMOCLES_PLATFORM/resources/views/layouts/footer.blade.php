
<footer class="flex flex-col py-4 md:py-6 w-full mx-auto items-center shadow bg-white text-center">
    <p class="px-4">PRIN PNRR 2022 - DAMOCLES: Detection And Mitigation Of Cyber attacks that exploit
        human vuLnerabilitiES, CUP: H53D23008140001 Funded by the European Union – Next Generation EU</p>
    <div>
        <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
        <p>Project version: {{ git_version() }}</p>
    </div>
    <img class="py-2" src="{{ asset('asset/images/logo_prin_pnrr_uniba.jpg') }}" alt="Logo PRIN PNRR UNIBA">

    <div class="pt-2 flex flex-row justify-center gap-4">
        <a href="{{ route('privacy-policy') }}">@lang('welcome.privacyPolicy')</a>
        <p> - </p>
        <a href="{{ route('cookie-policy') }}">@lang('welcome.cookiePolicy')</a>
    </div>
</footer>