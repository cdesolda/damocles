<section>
    <header>
        <p class="text-lg font-medium text-sky-900">
            @lang('training-campaign.partials.prompt.prompt')
        </p>
    </header>

    @if ($prompts->isEmpty())
        <p class="text-center">@lang('training-campaign.partials.prompt.noPrompts')</p>
    @else
        <div class="space-y-4">
            <div>
                <select id="promptIdSelect" name="promptId"
                    class="mt-1 block w-full sm:w-1/2 py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm"
                    onchange="updatePromptDescriptionAndDetails()">
                    @foreach ($prompts as $prompt)
                        <option value="{{ $prompt->id }}" data-type="{{ $prompt->type }}"
                            data-description="{{ $prompt->description }}" data-pros="{{ $prompt->pros }}"
                            data-cons="{{ $prompt->cons }}">
                            {{ $prompt->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="promptDetails" class="flex flex-col md:flex-row">
                <p id="pros" class="text-base text-green-700 md:w-1/2 md:pr-4"></p>
                <p id="cons" class="text-base text-red-700 md:w-1/2 md:pl-4"></p>
            </div>

            <div>
                <label for="promptDescription" class="block text-sm font-bold text-sky-700">@lang('training-campaign.newTrainingCampaign.prompt'):</label>
                <textarea id="promptDescription" name="promptDescription"
                    class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm"
                    rows="10"></textarea>
            </div>
        </div>
    @endif
</section>

<script>
    var trainingCampaignThreat = @json($trainingCampaign->threat->name);

    function updatePromptDescriptionAndDetails() {
        var select = document.getElementById('promptIdSelect');
        var description = select.options[select.selectedIndex]?.getAttribute('data-description') || '';
        var pros = select.options[select.selectedIndex]?.getAttribute('data-pros') || '';
        var cons = select.options[select.selectedIndex]?.getAttribute('data-cons') || '';

        // Replace placeholders in the description
        description = replacePlaceholders(description);
        document.getElementById('promptDescription').value = description;

        // Update pros and cons display
        document.getElementById('pros').innerHTML = `<b>Pros</b>: <br>` + formatNewlines(pros);
        document.getElementById('cons').innerHTML = `<b>Cons</b>: <br>` + formatNewlines(cons);
    }

    function replacePlaceholders(description) {
        return description.replace(/-threat/g, trainingCampaignThreat);
    }

    // Convert newlines into <br> for proper HTML rendering
    function formatNewlines(text) {
        return text.replace(/\n/g, '<br>');
    }

    window.onload = function() {
        updatePromptDescriptionAndDetails();
    };
</script>
