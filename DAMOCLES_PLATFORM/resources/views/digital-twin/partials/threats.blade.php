<section>
    <header>
        <p class="text-lg font-medium text-sky-900">
            *@lang('digital-twin.newCampaign.threat')
        </p>
    </header>

    @if ($threats->isEmpty())
        <p class="text-center">*@lang('digital-twin.noThreats')</p>
    @else
        <select id="threatIdSelect" name="threatId"
            class="mt-1 block w-full sm:w-1/2 py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
            @foreach ($threats as $threat)
                <option value="{{ $threat->id }}" data-threat="{{ $threat->name }}">{{ $threat->name }}
                </option>
            @endforeach
        </select>
    @endif

</section>
