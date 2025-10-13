<?php

namespace App\Jobs;

use App\Mail\PhishingMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPhishingEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userPhishingEmailId;
    protected $user;
    protected $subject;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userPhishingEmailId, $user, $subject, $body)
    {
        $this->userPhishingEmailId = $userPhishingEmailId;
        $this->user = $user;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new PhishingMailable($this->userPhishingEmailId, $this->user, $this->subject, $this->body));
    }
}
