<?php

namespace App\Jobs;

use App\Models\PhishingCampaign;
use App\Models\QuestionnaireCampaign;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckEvaluationCampaignsExpiry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Retrieve all Live Phishing campaigns that are not expired
        $phishingCampaigns = PhishingCampaign::where('state', 'Live')
            ->whereDate('expiration_date', '<', Carbon::now())
            ->get();

        foreach ($phishingCampaigns as $phishingCampaign) {
            $phishingCampaign->state = 'Completed';
            $phishingCampaign->save();
        }

        // Retrieve all Live Questionnaire campaigns that are not expired
        $questionnaireCampaigns = QuestionnaireCampaign::where('state', 'Live')
            ->whereDate('expiration_date', '<', Carbon::now())
            ->get();

        foreach ($questionnaireCampaigns as $questionnaireCampaign) {
            $questionnaireCampaign->state = 'Completed';
            $questionnaireCampaign->save();
        }
    }
}
