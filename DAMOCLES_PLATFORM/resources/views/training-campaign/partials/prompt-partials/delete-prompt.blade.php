<section>
    <!-- Delete prompt -->
    <div class="text-sky-800">
        <p class="text-lg font-medium">
            @lang('training-campaign.partials.prompt.delete'):
        </p>
        @if ($prompts->isEmpty())
            <p class="text-center">@lang('training-campaign.partials.prompt.noPromptsDelete')</p>
        @else
            <form id="deletePromptForm" action="{{ route('prompt.destroy', ['prompt' => 'prompt']) }}" method="POST">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <x-input-label for="deletePromptSelect" class="text-md block font-medium text-sky-700"
                        :value="__('training-campaign.partials.prompt.valueDelete')" />
                    <select id="deletePromptSelect" name="prompt"
                        class="mt-1 block w-full py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
                        @foreach ($prompts as $prompt)
                            <option value="{{ $prompt->id }}">{{ $prompt->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <x-primary-button type="submit">@lang('training-campaign.partials.prompt.deleteButton')</x-primary-button>
                </div>
            </form>
        @endif

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deletePromptForm');
        const select = document.getElementById('deletePromptSelect');

        select.addEventListener('change', function() {
            const selectedPromptId = select.value;
            const actionUrl = "{{ route('prompt.destroy', ['prompt' => 'id']) }}";
            deleteForm.action = actionUrl.replace('id', selectedPromptId);
        });

        document.getElementById('deletePromptForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
