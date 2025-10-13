<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainingCampaignInvited extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $trainingCampaign;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $trainingCampaign)
    {
        $this->user = $user;
        $this->trainingCampaign = $trainingCampaign;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invited to a training campaign',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.user.training-campaign-invited',
            with: [
                'user' => $this->user,
                'trainingCampaign' => $this->trainingCampaign,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
