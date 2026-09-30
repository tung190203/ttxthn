<?php

namespace App\Mail;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvestorApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $guest;

    public function __construct(Guest $guest)
    {
        $this->guest = $guest;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tài khoản nhà đầu tư đã được phê duyệt - Bản đồ Đầu tư Hà Nội',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.investor-approved',
            with: [
                'guest' => $this->guest,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
