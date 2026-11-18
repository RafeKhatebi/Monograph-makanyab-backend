<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailVerificationOtpNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $otpCode) {}

    public function via(User $user): array
    {
        return ['mail'];
    }

    public function toMail(User $user): MailMessage
    {
        return (new MailMessage)
            ->subject(__('auth.mail.verification_subject'))
            ->greeting(__('auth.mail.greeting', ['name' => $user->name ?: __('common.user')]))
            ->line(__('auth.mail.verification_intro'))
            ->line('**'.$this->otpCode.'**')
            ->line(__('auth.verification_otp_expires', ['minutes' => 10]))
            ->action(__('auth.mail.verification_action'), route('verification.notice'))
            ->line(__('auth.verification_otp_ignore'))
            ->salutation(config('app.name'));
    }
}
