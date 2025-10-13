<section>
    <header>
        <p class="text-lg font-medium text-sky-900">
            *@lang('phishing-campaign.partials.emotionalTrigger.emotionalTriggerText'):
        </p>
    </header>

    @if ($emotionalTriggers->isEmpty())
        <p class="text-center">@lang('phishing-campaign.partials.emotionalTrigger.noEmotionalTrigger')</p>
    @else
        <div id="emotionalTriggerButtons" class="flex flex-wrap gap-2 py-2">
            @foreach ($emotionalTriggers as $emotionalTrigger)
                <div class="tooltip">
                    <button type="button"
                        class="emotional-trigger-button py-2 px-4 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm"
                        value="{{ $emotionalTrigger->id }}" data-emotional-trigger="{{ $emotionalTrigger->description }}">
                        {{ $emotionalTrigger->description }}
                    </button>
                    <span class="tooltiptext">{{ $emotionalTrigger->tooltip }}</span>
                </div>
            @endforeach
        </div>
    @endif

</section>
