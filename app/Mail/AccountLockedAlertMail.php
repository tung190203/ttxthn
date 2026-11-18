<?php

namespace App\Mail;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountLockedAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $guest;
    public $reason;
    public $ip;

    /**
     * Create a new message instance.
     */
    public function __construct(Guest $guest, string $reason, string $ip)
    {
        $this->guest = $guest;
        $this->reason = $reason;
        $this->ip = $ip;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[CẢNH BÁO AN NINH] Tài khoản nhà đầu tư bị tạm khóa do bất thường',
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
                'ip' => $this->ip,
            ],
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
