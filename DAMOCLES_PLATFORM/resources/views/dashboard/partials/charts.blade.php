<section class="py-4 sm:pt-10">

    <div class="flex flex-wrap space-y-6 justify-center md:space-y-0 md:justify-around w-full items-center">
        @if ($totalPhishingCampaigns != 0)
            <div class="flex flex-col items-center w-80 md:w-1/4">
                <canvas id="phishingCampaigns"></canvas>
                <p class="text-sm">@lang('dashboard.charts.totalCampaigns'): {{ $totalPhishingCampaigns }} </p>
            </div>
        @endif

        @if ($totalQuestionnaireCampaigns != 0)
            <div class="flex flex-col items-center w-80 md:w-1/4">
                <canvas id="questionnairesCampaigns"></canvas>
                <p class="text-sm">@lang('dashboard.charts.totalCampaigns'): {{ $totalQuestionnaireCampaigns }} </p>
            </div>
        @endif

        @if ($totalTrainingCampaigns != 0)
            <div class="flex flex-col items-center w-80 md:w-1/4">
                <canvas id="trainingCampaigns"></canvas>
                <p class="text-sm">@lang('dashboard.charts.totalCampaigns'): {{ $totalTrainingCampaigns }} </p>
            </div>
        @endif
    </div>

</section>

<script>
    if ({{ $totalPhishingCampaigns }} > 0) {
        const dataPhishingCampaigns = [{
                status: 'Draft',
                count: {!! $phishingCampaignsDraft !!}
            },
            {
                status: 'Ready',
                count: {!! $phishingCampaignsReady !!}
            },
            {
                status: 'Live',
                count: {!! $phishingCampaignsLive !!}
            },
            {
                status: 'Completed',
                count: {!! $phishingCampaignsCompleted !!}
            },

        ];

        new Chart(
            document.getElementById('phishingCampaigns'), {
                type: 'doughnut',
                data: {
                    labels: dataPhishingCampaigns.map(row => row.status),
                    datasets: [{
                        data: dataPhishingCampaigns.map(row => row.count)
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'left',
                        },
                        title: {
                            display: true,
                            text: 'Phishing campaigns'
                        }
                    }
                }
            }
        );
    }

    if ({{ $totalQuestionnaireCampaigns }} > 0) {
        const dataQuestionnairesCampaigns = [{
                status: 'Draft',
                count: {!! $questionnairesCampaignsDraft !!}
            },
            {
                status: 'Ready',
                count: {!! $questionnairesCampaignsReady !!}
            },
            {
                status: 'Live',
                count: {!! $questionnairesCampaignsLive !!}
            },
            {
                status: 'Completed',
                count: {!! $questionnairesCampaignsCompleted !!}
            },

        ];

        new Chart(
            document.getElementById('questionnairesCampaigns'), {
                type: 'doughnut',
                data: {
                    labels: dataQuestionnairesCampaigns.map(row => row.status),
                    datasets: [{
                        data: dataQuestionnairesCampaigns.map(row => row.count)
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'left',
                        },
                        title: {
                            display: true,
                            text: 'Questionnaire campaigns'
                        }
                    }
                }
            }
        );
    }

    if ({{ $totalTrainingCampaigns }} > 0) {
        const dataTrainingCampaigns = [{
                status: 'Draft',
                count: {!! $trainingCampaignsDraft !!}
            },
            {
                status: 'Ready',
                count: {!! $trainingCampaignsReady !!}
            },
            {
                status: 'Live',
                count: {!! $trainingCampaignsLive !!}
            },
            {
                status: 'Completed',
                count: {!! $trainingCampaignsCompleted !!}
            },

        ];

        new Chart(
            document.getElementById('trainingCampaigns'), {
                type: 'doughnut',
                data: {
                    labels: dataTrainingCampaigns.map(row => row.status),
                    datasets: [{
                        data: dataTrainingCampaigns.map(row => row.count)
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'left',
                        },
                        title: {
                            display: true,
                            text: 'Training campaigns'
                        }
                    }
                }
            }
        );
    }
</script>
