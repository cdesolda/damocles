<?php

namespace App\Jobs;

use App\Models\PhishingCampaign;
use App\Models\PhishingEmailPhishingCampaign;
use App\Models\UserPhishingEmail;
use App\Jobs\SendPhishingEmails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SendPeriodicPhishingEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Retrieve all live phishing campaigns that have not expired
        $liveCampaigns = PhishingCampaign::where('state', 'Live')
            ->where('expiration_date', '>', now())
            ->get();

        foreach ($liveCampaigns as $phishingCampaign) {
            // Check the last email sent time for the campaign
            $lastEmailSentAt = UserPhishingEmail::whereHas('phishingEmail', function ($query) use ($phishingCampaign) {
                $query->where('phishing_campaign_id', $phishingCampaign->id);
            })->max('sent');

            // Calculate the next send time
            $nextSendTime = $lastEmailSentAt ? Carbon::parse($lastEmailSentAt)->addDays($phishingCampaign->timing_email) : now();

            // If the next send time is in after today, skip this campaign
            if ($nextSendTime->toDateString() > now()->toDateString()) {
                continue;
            }

            // Collect the phishingEmailPhishingCampaigns IDs associated with the campaign
            $phishingEmailPhishingCampaigns = $phishingCampaign->phishingEmailPhishingCampaign;
            $phishingEmailPhishingCampaignIds = $phishingEmailPhishingCampaigns->pluck('id')->toArray();

            // Retrieve all email not sent
            $emailsNotSent = UserPhishingEmail::whereIn('pe_pc_id', $phishingEmailPhishingCampaignIds)
                ->whereNull('sent')
                ->get();

            if ($emailsNotSent->isEmpty()) {
                $this->changeState($phishingCampaign->id, 'Completed');
                continue;
            }

            // Get the first email not sent ID
            $emailId = $emailsNotSent->first();

            // Retrieve the details of the first email not sent
            $email = PhishingEmailPhishingCampaign::find($emailId->pe_pc_id)->email;

            // Retrieve the user IDs associated with that email
            $userPhishingEmails = UserPhishingEmail::where('pe_pc_id', $emailId->pe_pc_id)->get();

            if ($phishingCampaign->state == "Live") {
                DB::beginTransaction();
                try {
                    foreach ($userPhishingEmails as $userPhishingEmail) {
                        try {
                            $subject = $this->replacePlaceholders($userPhishingEmail->id, $email->subject, $userPhishingEmail->user);
                            $body = $this->replacePlaceholders($userPhishingEmail->id, $email->body, $userPhishingEmail->user);

                            // Dispatch the job
                            SendPhishingEmails::dispatch($userPhishingEmail->id, $userPhishingEmail->user, $subject, $body);

                            if ($userPhishingEmail) {
                                $userPhishingEmail->sent = Carbon::now();
                                $userPhishingEmail->save();
                            }
                        } catch (\Exception $e) {
                            // Handle exception if necessary
                        }
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'Failed to dispatch jobs: ' . $e->getMessage()], 500);
                }
            }

            // Check if all the emails are sent
            $remainingEmailsNotSent = UserPhishingEmail::whereIn('pe_pc_id', $phishingEmailPhishingCampaignIds)
                ->whereNull('sent')
                ->get();

            if ($remainingEmailsNotSent == 0) {
                $this->changeState($phishingCampaign->id, 'Completed');
            }
        }
    }

    private function replacePlaceholders($text, $user)
    {
        $placeholders = [
            '-name' => $user->name,
            '-surname' => $user->surname,
            '-email' => $user->email,
            '-dob' => $user->dob,
        ];

        foreach ($placeholders as $placeholder => $value) {
            $text = str_replace($placeholder, $value, $text);
        }

        return $text;
    }

    private function changeState($phishingCampaignId, $state)
    {
        $phishingCampaign = PhishingCampaign::findOrFail($phishingCampaignId);
        $phishingCampaign->state = $state;
        $phishingCampaign->save();
    }
}
