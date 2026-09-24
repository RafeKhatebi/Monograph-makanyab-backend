<?php

namespace App\Notifications;

use App\Models\EmailVerificationOtp;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailVerificationOtpNotification extends Notification
{
    use Queueable;

    public function via(User $user): array
    {
        return ['mail'];
    }

    public function toMail(User $user): MailMessage
    {
        $otp = EmailVerificationOtp::generateForUser($user);

        return (new MailMessage)
            ->subject(__('auth.ui.verify_email_title') . ' - ' . config('app.name'))
            ->greeting(__('auth.ui.verify_email_title') . ' ' . ($user->name ?: ''))
            ->line(__('auth.ui.verify_email_intro'))
            ->line('<div style="text-align: center; margin: 20px 0;">
                <span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #2563eb;">' . $otp->otp_code . '</span>
            </div>')
            ->line(__('auth.verification_otp_expires', ['minutes' => 10]))
            ->action(__('auth.ui.resend_verification'), route('verification.notice'))
            ->line(__('auth.verification_otp_ignore'));
    }
}
