<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to digital twins campaign -->
            <a href="{{ route('digital-twins.index') }}" class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('digital-twins.index') }}">@lang('digital-twin.digitalTwinsCampaigns')</a></li>
                <li>/</li>
                <li>@lang('digital-twin.digitalTwinCampaignDetails')</li>
            </ul>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">
                <p class="font-semibold text-xl">@lang('digital-twin.digitalTwinCampaignDetails')</p>

                @if ($digitalTwinsCampaign)
                    <div class="flex flex-col">
                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">
                                @lang('digital-twin.title'):
                            </p>
                            <p>{{ $digitalTwinsCampaign->title }}</p>
                        </div>
                    </div>

                    @if (!empty($digitalTwinsCampaign->description))
                        <div class="flex flex-col">
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">
                                    @lang('digital-twin.description'):
                                </p>
                                <p>{{ $digitalTwinsCampaign->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Tabs -->
                    <div class="flex justify-center border-b border-gray-300">
                        <button id="tab-profilePrompt"
                            class="tab-btn active-tab px-4 py-2 font-semibold text-sky-700 border-b-2 border-sky-700 hover:text-sky-700">
                            @lang('digital-twin.profilePrompt.profilePrompt')
                        </button>
                        <button id="tab-threatPrompt"
                            class="tab-btn px-4 py-2 font-semibold text-gray-600 border-b-2 hover:text-sky-700">
                            @lang('digital-twin.threatPrompt.threatPrompt')
                        </button>
                    </div>
                    <div id="profilePrompt-content" class="tab-content flex flex-col gap-2 pt-4">
                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">@lang('digital-twin.typeOfPrompt.typeOfPrompt'):</p>
                            <p>@lang('digital-twin.typeOfPrompt.' . $digitalTwinsCampaign->user_prompt_type)</p>
                        </div>
                        @if (!empty($demographics))
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('digital-twin.demographicAttributes'):</p>
                                <p>
                                    @foreach ($demographics as $index => $demographic)
                                        {{ $demographic }}{{ $index < count($demographics) - 1 ? ',' : '' }}
                                    @endforeach
                                </p>
                            </div>
                        @endif

                        @if ($humanfactors->isNotEmpty())
                            <div class="flex flex-row gap-2">
                                <p class="font-semibold">@lang('digital-twin.humanFactors'):</p>
                                <p>
                                    @foreach ($humanfactors as $index => $humanfactor)
                                        {{ $humanfactor->name }}{{ $index < $humanfactors->count() - 1 ? ',' : '' }}
                                    @endforeach
                                </p>
                            </div>
                        @endif
                        <textarea id="prompt" name="prompt"
                            class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm" rows="9"
                            readonly>{{ $digitalTwinsCampaign->user_prompt }}</textarea>
                    </div>
                    <div id="threatPrompt-content" class="tab-content hidden flex flex-col gap-2 pt-4">
                        <div class="flex flex-row gap-2">
                            <p class="font-semibold">@lang('digital-twin.typeOfPrompt.typeOfPrompt'):</p>
                            <p>@lang('digital-twin.typeOfPrompt.' . $digitalTwinsCampaign->threat_prompt_type)</p>
                        </div>
                        <textarea id="prompt" name="prompt"
                            class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm" rows="12"
                            readonly>{{ $digitalTwinsCampaign->threat_prompt }}</textarea>
                    </div>
                    <div class="mt-8">
                        <p class="font-semibold text-LG">@lang('email.emails'):</p>
                        <table id="email-table" class="text-center w-full">
                            <thead class="bg-gray-100">
                                <tr class="border-b-2 border-gray-300">
                                    <th class="py-2 px-4">@lang('email.subject')</th>
                                    <th class="py-2 px-4">@lang('email.body')</th>
                                    <th class="py-2 px-4">@lang('email.topic')</th>
                                    <th class="py-2 px-4">@lang('email.persuasions')</th>
                                    <th class="py-2 px-4">@lang('email.emotionalTriggers')</th>
                                    <th class="py-2 px-4">@lang('general.updatedAt')</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($emails as $email)
                                    <tr class="hover:bg-gray-100 border-b border-gray-200">
                                        <td class="py-2 px-4">{{ Str::limit($email->subject, 100) }}</td>
                                        <td class="py-2 px-4">{{ Str::limit($email->body, 100) }}</td>
                                        <td class="py-2 px-4">{{ $email->topic ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">
                                            {{ is_array($email->persuasions) ? implode(', ', $email->persuasions) : $email->persuasions ?? 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4">
                                            {{ is_array($email->emotional_triggers) ? implode(', ', $email->emotional_triggers) : $email->emotional_triggers ?? 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4">{{ $email->updated_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Paginator controls -->
                        @include('layouts.partials.pagination-controls')
                    </div>
                @else
                    <div class="text-center text-xl">
                        <p>@lang('general.errorRetrievingCampaign')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script src="{{ asset('js/tabulationTable.js') }}"></script>
<script>
    // Tabulation
    document.addEventListener('DOMContentLoaded', function() {
        setupPagination(
            'email-table', // Table ID
            'Total emails' // Personalized text for the total label
        );
    });

    document.addEventListener("DOMContentLoaded", () => {
        const tabs = document.querySelectorAll(".tab-btn");
        const contents = document.querySelectorAll(".tab-content");

        tabs.forEach((tab, index) => {
            tab.addEventListener("click", () => {
                // Remove active styles from all tabs
                tabs.forEach(t => t.classList.remove("active-tab", "text-sky-700",
                    "border-sky-700"));
                // Hide all contents
                contents.forEach(content => content.classList.add("hidden"));

                // Add active styles to the clicked tab
                tab.classList.add("active-tab", "text-sky-700", "border-sky-700");
                // Show the corresponding content
                contents[index].classList.remove("hidden");
            });
        });
    });
</script>
