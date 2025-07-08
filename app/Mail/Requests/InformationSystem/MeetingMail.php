<?php

namespace App\Mail\Requests\InformationSystem;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MeetingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public array $data, public string $mode)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->mode) {
            'create' => "Undangan Rapat: Pembahasan {$this->data['topic']}",
            'update' => "Update Undangan Rapat: Pembahasan {$this->data['topic']}",
            'delete' => "Mohon maaf, Undangan Rapat: Pembahasan {$this->data['topic']} dibatalkan",
            default => "Undangan Rapat: Pembahasan {$this->data['topic']}",
        };
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'components.mail.meeting-mail',
            with: [
                'data' => $this->data,
                'mode' => $this->mode
            ]
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
