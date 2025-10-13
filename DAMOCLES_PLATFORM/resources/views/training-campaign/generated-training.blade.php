<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row item-center gap-3">
            <!-- Back to training campaign -->
            <a href="{{ route('training-campaign.simulation', ['trainingCampaign' => $trainingCampaign->id]) }}"
                class="cursor-pointer">
                <x-primary-button>
                    @lang('general.back')
                </x-primary-button>
            </a>
            <!-- Breadcrumb -->
            <ul class="flex flex-row gap-1 flex-wrap break-words text-sky-800">
                <li><a href="{{ route('training-campaign.index') }}">@lang('training-campaign.trainingCampaigns')</a></li>
                <li>/</li>
                <li><a href="{{ route('training-campaign.new') }}">@lang('training-campaign.new')</a></li>
                <li>/</li>
                <li>@lang('training-campaign.generated.trainingGenerated')</li>
            </ul>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-sky-900">

                <div class="flex flex-row justify-around items-center pb-6">
                    <!-- Circles which indicates the steps of the creation -->
                    <div class="flex flex-row justify-center items-center py-2">
                        <span class="status"></span>
                        <span class="status"></span>
                        <span class="status active"></span>
                        <span class="status"></span>
                    </div>
                </div>

                <!-- Handle no generated trainings -->
                @if (empty($generatedTrainings))
                    <x-modal name="error-modal" id="error-modal" title="@lang('training-campaign.generated.errorTitle')" :show="true">
                        <div class="p-4 rounded-lg text-center text-sky-800">
                            <p class="text-xl font-semibold text-red-700 pb-8">
                                @lang('training-campaign.generated.errorGeneratedModal')
                            </p>
                            <x-secondary-button x-on:click="$dispatch('close')">
                                @lang('general.close')
                            </x-secondary-button>
                        </div>
                    </x-modal>
                @else
                    {{-- <!-- Check if not all trainings were generated -->
                    @if (count($generatedTrainings) < count($users))
                        <x-modal name="error-not-all-modal" id="error-not-all-modal" title="@lang('training-campaign.generated.notAllGeneratedTitle')"
                            :show="true">
                            <div class="p-4 rounded-lg text-center text-sky-800">
                                <p class="text-xl font-semibold text-red-700 pb-8">
                                    @lang('training-campaign.generated.notAllGeneratedModal')
                                </p>
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    @lang('general.close')
                                </x-secondary-button>
                            </div>
                        </x-modal>
                    @endif --}}

                    <!-- Trainings Summary -->
                    <p class="font-semibold text-xl">
                        @lang('training-campaign.generated.details'){{ count($generatedTrainings) > 1 ? __('training-campaign.generated.s') : '' }}
                        @lang('training-campaign.generated.generated') (3/4)
                    </p>

                    <!-- Training Details -->
                    <div class="flex flex-col pt-4">
                        @foreach ($generatedTrainings as $index => $generatedTraining)
                            <div
                                class="training-container tab flex flex-col @if ($loop->index > 0) hidden @endif">

                                <!-- User Information -->
                                <div class="flex flex-col gap-2 pb-4 border-b border-gray-200">
                                    <div class="flex flex-row gap-2">
                                        <p class="font-semibold">@lang('training-campaign.generated.name'):</p>
                                        <p>{{ $generatedTraining['userData']['user']['name'] }}</p>
                                    </div>
                                    <div class="flex flex-row gap-2">
                                        <p class="font-semibold">@lang('training-campaign.generated.surname'):</p>
                                        <p>{{ $generatedTraining['userData']['user']['surname'] }}</p>
                                    </div>
                                    <div class="flex flex-row gap-2">
                                        <p class="font-semibold">@lang('training-campaign.generated.dob'):</p>
                                        <p>{{ \Carbon\Carbon::parse($generatedTraining['userData']['user']['dob'])->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Human Factors -->
                                <div class="flex flex-col gap-2 pt-4 pb-4 border-b border-gray-200">
                                    @if (!empty($generatedTraining['userData']['humanFactors']))
                                        @foreach ($generatedTraining['userData']['humanFactors'] as $factorCollection)
                                            @foreach ($factorCollection as $factor)
                                                <div class="flex flex-row gap-2">
                                                    <p class="font-semibold">@lang('training-campaign.generated.hf'):</p>
                                                    <p>{{ $factor['humanFactor'] }}</p>
                                                    <p class="font-semibold">-</p>
                                                    <p class="font-semibold">@lang('training-campaign.generated.education'):</p>
                                                    <p>{{ $factor['educationLevel'] }}</p>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @else
                                        <p class="text-red-500">@lang('training-campaign.generated.noHumanFactors')</p>
                                    @endif
                                </div>

                                <!-- Training Content/Error -->
                                <div class="pt-4">
                                    <p class="font-semibold">@lang('training-campaign.generated.training')
                                        ({{ $index + 1 }}/{{ count($generatedTrainings) }})
                                        :</p>
                                    @if (isset($generatedTraining['trainingContent']['error']) && $generatedTraining['trainingContent']['error'])
                                        <div class="text-red-500">
                                            <p>@lang('training-campaign.generated.error'):</p>
                                            <p>{{ $generatedTraining['trainingContent']['message'] ?? 'Unknown error occurred.' }}
                                            </p>
                                        </div>
                                    @else
                                        <textarea id="generatedTraining-{{ $index }}"
                                            class="w-full p-2 border border-sky-700 focus:border-sky-800 focus:ring-sky-800 rounded-md shadow-sm tab-content"
                                            rows="10">{{ $generatedTraining['trainingContent'] }}</textarea>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <!-- Navigation for Multiple Trainings -->
                        @if (count($generatedTrainings) > 1)
                            <div class="flex w-full items-center py-4">
                                <div class="flex w-1/3 justify-center">
                                    <x-primary-button type="button" id="prevBtn"
                                        onclick="nextPrev(-1)">@lang('training-campaign.generated.previousTraining')</x-primary-button>
                                </div>
                                <div class="flex w-1/3 justify-center">
                                    @for ($i = 0; $i < count($generatedTrainings); $i++)
                                        <span class="step"></span>
                                    @endfor
                                </div>
                                <div class="flex w-1/3 justify-center">
                                    <x-primary-button type="button" id="nextBtn"
                                        onclick="nextPrev(1)">@lang('training-campaign.generated.nextTraining')</x-primary-button>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-row justify-around items-center pt-6">
                            <div class="flex w-1/3 justify-center">
                            </div>
                            <!-- Circles which indicates the steps of the creation -->
                            <div class="flex flex-row w-1/3 justify-center items-center py-2">
                                <span class="status"></span>
                                <span class="status"></span>
                                <span class="status active"></span>
                                <span class="status"></span>
                            </div>
                            <div class="flex w-1/3 justify-center">
                                <a href="{{ route('training-campaign.users', ['trainingCampaign' => $trainingCampaign->id]) }}"
                                    id="chooseUserButton" class="flex justify-end pt-2">
                                    <x-primary-button type="button">@lang('general.chooseUsers')</x-primary-button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-loading-screen />
</x-app-layout>

<script>
    var currentTab = 0;
    showTab(currentTab);

    function showTab(n) {
        var x = document.getElementsByClassName("training-container");
        if (x.length === 0) return; // Exit if no tabs are found

        x[n].style.display = "block";

        if (n === 0) {
            if (document.getElementById("prevBtn")) {
                document.getElementById("prevBtn").style.display = "none";
            }
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }

        if (n === (x.length - 1)) {
            if (document.getElementById("nextBtn")) {
                document.getElementById("nextBtn").style.display = "none";
            }
        } else {
            document.getElementById("nextBtn").style.display = "inline";
        }

        fixStepIndicator(n);
    }

    function nextPrev(n) {
        var x = document.getElementsByClassName("training-container");
        if (x.length === 0) return;

        x[currentTab].style.display = "none";
        currentTab = currentTab + n;

        if (currentTab >= x.length) {
            currentTab = x.length - 1;
        } else if (currentTab < 0) {
            currentTab = 0;
        }

        showTab(currentTab);
    }

    function fixStepIndicator(n) {
        var i, x = document.getElementsByClassName("step");
        if (x.length === 0) return;

        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        x[n].className += " active";
    }

    document.addEventListener('DOMContentLoaded', function() {
        var chooseUserButton = document.getElementById('chooseUserButton');

        if (chooseUserButton) {
            chooseUserButton.addEventListener('click', function(event) {
                // Loading screen
                document.getElementById('loadingOverlay').classList.replace('hidden', 'flex');
            });
        }
    });
</script>
