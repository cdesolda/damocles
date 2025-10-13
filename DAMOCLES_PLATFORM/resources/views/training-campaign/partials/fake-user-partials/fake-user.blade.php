<!-- Fake Users Section -->
<section>
    <h2 class="font-semibold text-xl mb-4">
        *@lang('training-campaign.newTrainingCampaign.fakeUser')
    </h2>
    <p class="font-bold pb-2 pl-2">@lang('general.totalUser'): <span id="totalSelectedUsers">0</span></p>

    <div id="user-container" class="flex flex-wrap gap-4">
        @foreach ($users as $user)
            <div class="user-card border border-sky-700 rounded-lg p-4 bg-white shadow-sm w-64 transition-all cursor-pointer"
                data-user-id="{{ $user['user']['id'] }}">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold user-name">{{ $user['user']['name'] }} {{ $user['user']['surname'] }}</h3>
                    <input id="user{{ $user['user']['id'] }}" type="checkbox" class="user-checkbox hidden"
                        data-user-id="{{ $user['user']['id'] }}">
                </div>
                <p class="text-sm">@lang('training-campaign.users.dob'):
                    {{ \Carbon\Carbon::parse($user['user']['dob'])->format('d/m/Y') }}</p>
                <p class="text-sm">@lang('training-campaign.users.gender'): {{ $user['user']['gender'] }}</p>
                <p class="text-sm">@lang('training-campaign.users.companyRole'): {{ $user['user']['company_role'] }}</p>

                <div class="mt-2">
                    @foreach ($user['threats'] as $hfId => $hfThreats)
                        <div class="hf-group mt-2">
                            <div class="flex flex-col">
                                <p class="font-semibold">@lang('training-campaign.partials.fakeUser.humanFactor'):</p>
                                <p>{{ $hfThreats->first()['hf'] }}</p>
                            </div>
                            @foreach ($hfThreats as $hfThreat)
                                <div class="threat-details">
                                    <div class="flex flex-row gap-2">
                                        <p class="font-semibold">@lang('training-campaign.partials.fakeUser.threat'):</p>
                                        <p>{{ $hfThreat['threat'] }}</p>
                                    </div>
                                    <div class="flex flex-row gap-2">
                                        <p class="font-semibold">@lang('training-campaign.partials.fakeUser.severityLevel'):</p>
                                        <p>{{ $hfThreat['severityLevel'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userCards = document.querySelectorAll('.user-card');

        userCards.forEach(card => {
            card.addEventListener('click', function() {
                const checkbox = card.querySelector('.user-checkbox');
                checkbox.checked = !checkbox.checked;

                if (checkbox.checked) {
                    card.style.backgroundColor = '#0369a1';
                    card.style.color = 'white';
                } else {
                    card.style.backgroundColor = 'white';
                    card.style.color = '#0369a1';
                }

                updateSelectedUsersCount();
            });
        });

        function updateSelectedUsersCount() {
            const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
            document.getElementById('totalSelectedUsers').textContent = selectedCount;
        }

    });
</script>
