<section>
    @if ($topics->isEmpty())
        <p class="text-center">@lang('phishing-campaign.partials.topic.noTopics')</p>
    @else
        <x-input-label for="topicIdSelect" class="text-md block font-medium text-sky-700" :value="__('email.filterBy.topic')" />
        <select id="topicIdSelect" name="topicId"
            class="mt-1 p-2 block w-full border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm min-w-[200px]">

            <option value="">-- @lang('general.NoFilter') --</option> <!-- Placeholder for no filter -->
            @foreach ($topics as $topic)
                <option value="{{ $topic->description }}" data-topic="{{ $topic->description }}">
                    {{ $topic->description }}
                </option>
            @endforeach
        </select>
    @endif
</section>
