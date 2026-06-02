<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GuestResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('guest_password_reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject(__('app.reset_password_email_subject'))
            ->line(__('app.reset_password_email_intro'))
            ->action(__('app.reset_password'), $url)
            ->line(__('app.reset_password_email_expire'))
            ->line(__('app.reset_password_email_ignore'));
    }
}
