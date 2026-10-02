<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvestorLockedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $guest;
    public $reason;

    /**
     * Create a new message instance.
     */
    public function __construct($guest, $reason)
    {
        $this->guest = $guest;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông báo khóa tài khoản nhà đầu tư',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-locked-alert',
            with: [
                'guest' => $this->guest,
                'reason' => $this->reason,
                'ip' => request()->ip(), // passing IP to template
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
