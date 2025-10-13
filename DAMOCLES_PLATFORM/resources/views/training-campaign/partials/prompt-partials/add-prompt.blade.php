<section>
    <!-- Add new prompt -->
    <div class="text-sky-800">
        <p class="text-lg font-medium">
            @lang('training-campaign.partials.prompt.new'):
        </p>
        <form id="addPromptForm" action="{{ route('prompt.create') }}" method="POST">
            @csrf
            @method('post')

            <div class="mb-4">
                <x-input-label for="title" class="text-md block font-medium text-sky-700" :value="__('training-campaign.partials.prompt.title')" />
                <input required type="text" id="title" name="title"
                    class="p-2 border border-sky-800 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                    placeholder="@lang('training-campaign.partials.prompt.title')">

                <x-input-label for="description" class="text-md block font-medium text-sky-700" :value="__('training-campaign.partials.prompt.description')" />
                <textarea required id="description" name="description"
                    class="p-2 border border-sky-800 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                    placeholder="@lang('training-campaign.partials.prompt.description')"></textarea>

                <x-input-label for="pros" class="text-md block font-medium text-sky-700" :value="__('training-campaign.partials.prompt.valuePros')" />
                <textarea required id="pros" name="pros"
                    class="p-2 border border-sky-800 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                    placeholder="@lang('training-campaign.partials.prompt.pros')"></textarea>

                <x-input-label for="cons" class="text-md block font-medium text-sky-700" :value="__('training-campaign.partials.prompt.valueCons')" />
                <textarea required id="cons" name="cons"
                    class="p-2 border border-sky-800 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm w-full placeholder:text-sky-700"
                    placeholder="@lang('training-campaign.partials.prompt.cons')"></textarea>


            </div>
            <div class="flex justify-end">
                <x-primary-button type="submit">@lang('training-campaign.partials.prompt.add')</x-primary-button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addPromptForm').addEventListener('submit', function(event) {
            // Loading screen
            document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
        });
    });
</script>
