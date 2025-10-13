<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuestionnaireCampaignInvited extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $questionnaireCampaign;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $questionnaireCampaign)
    {
        $this->user = $user;
        $this->questionnaireCampaign = $questionnaireCampaign;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invited to a questionnaire campaign',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.user.questionnaire-campaign-invited',
            with: [
                'user' => $this->user,
                'questionnaireCampaign' => $this->questionnaireCampaign,
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
