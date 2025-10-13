<section>
    <!-- Add new topic -->
    <div class="text-sky-800">
        <p class="text-lg font-medium">
            @lang('phishing-campaign.partials.topic.new'):
        </p>
        <form id="addTopicForm" action="{{ route('topic.create') }}" method="POST">
            @csrf
            @method('post')

            <div class="mb-4">
                <input required type="text" id="description" name="description"
                    class="p-2 border border-sky-800 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                    placeholder="@lang('phishing-campaign.partials.topic.placeholder')">
            </div>
            <div class="flex justify-end">
                <x-primary-button type="submit">@lang('phishing-campaign.partials.topic.add')</x-primary-button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addTopicForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
