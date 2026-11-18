<?php

namespace App\Mail;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvestorRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $guest;
    public $reason;

    public function __construct(Guest $guest, string $reason = '')
    {
        $this->guest = $guest;
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông báo kết quả xét duyệt tài khoản - Bản đồ Đầu tư Hà Nội',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.investor-rejected',
            with: [
                'guest'  => $this->guest,
                'reason' => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
