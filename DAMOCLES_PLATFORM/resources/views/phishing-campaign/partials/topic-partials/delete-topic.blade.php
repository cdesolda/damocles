<section>
    <!-- Delete topuic -->
    <div class="text-sky-800">
        <p class="text-lg font-medium">
            @lang('phishing-campaign.partials.topic.delete'):
        </p>
        @if ($topics->isEmpty())
            <p class="text-center">@lang('phishing-campaign.partials.topic.noTopicsDelete')</p>
        @else
            <form id="deleteTopicForm" action="{{ route('topic.destroy', ['topic' => 'topic']) }}" method="POST">
                @csrf
                @method('delete')

                <div class="mb-4">
                    <x-input-label for="deleteTopicSelect" class="text-md block font-medium text-sky-700"
                        :value="__('phishing-campaign.partials.topic.valueDelete')" />
                    <select id="deleteTopicSelect" name="topic"
                        class="mt-1 block w-full py-2 px-3 border border-sky-800 bg-white rounded-md shadow-sm focus:outline-none focus:ring-sky-800 focus:border-sky-800 sm:text-sm">
                        @foreach ($topics as $topic)
                            <option value="{{ $topic->id }}">{{ $topic->description }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <x-primary-button type="submit">@lang('phishing-campaign.partials.topic.deleteButton')</x-primary-button>
                </div>
            </form>
        @endif

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteTopicForm');
        const select = document.getElementById('deleteTopicSelect');

        select.addEventListener('change', function() {
            const selectedTopicId = select.value;
            const actionUrl = "{{ route('topic.destroy', ['topic' => 'id']) }}";
            deleteForm.action = actionUrl.replace('id', selectedTopicId);
        });

        document.getElementById('deleteTopicForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
