<section>
    <header>
        <p class="text-lg font-medium text-sky-900">
            *@lang('phishing-campaign.partials.persuasion.persuasionText'):
        </p>
    </header>

    @if ($persuasions->isEmpty())
        <p class="text-center">@lang('phishing-campaign.partials.persuasion.noPersuasions')</p>
    @else
        <div id="persuasionButtons" class="flex flex-wrap gap-2 py-2">
            @foreach ($persuasions as $persuasion)
                <div class="tooltip">
                    <button type="button"
                        class="persuasion-button py-2 px-4 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm"
                        value="{{ $persuasion->id }}" data-persuasion="{{ $persuasion->description }}">
                        {{ $persuasion->description }}
                    </button>
                    <span class="tooltiptext">{{ $persuasion->tooltip }}</span>
                </div>
            @endforeach
        </div>
    @endif

</section>
