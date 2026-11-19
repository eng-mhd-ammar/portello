<?php

namespace Modules\IAM\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationCodeNotification extends Notification
{
    public function __construct(public string $code) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify your Portello account')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Use this verification code to confirm your email address:')
            ->line('**'.$this->code.'**')
            ->line('This code expires in 15 minutes. If you did not create an account, you can ignore this email.');
    }
}
