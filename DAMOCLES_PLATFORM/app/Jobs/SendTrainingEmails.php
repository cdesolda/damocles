<?php

namespace App\Jobs;

use App\Mail\TrainingCampaignInvited;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTrainingEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $trainingCampaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $trainingCampaign)
    {
        $this->user = $user;
        $this->trainingCampaign = $trainingCampaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user)->send(new TrainingCampaignInvited($this->user, $this->trainingCampaign));
    }
}
