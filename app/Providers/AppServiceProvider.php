<?php

namespace App\Providers;

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Service;
use App\Policies\PlaceCategoryPolicy;
use App\Policies\PlacePolicy;
use App\Policies\ServicePolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        VerifyEmail::toMailUsing(function ($notifiable, string $url) {
            return (new MailMessage)
                ->subject(__('auth.mail.verification_subject'))
                ->greeting(__('auth.mail.greeting', ['name' => $notifiable->name ?: __('common.user')]))
                ->line(__('auth.ui.verify_email_intro'))
                ->action(__('auth.mail.verification_action'), $url)
                ->line(__('auth.verification_otp_expires', ['minutes' => config('auth.verification.expire', 60)]))
                ->line(__('auth.verification_otp_ignore'))
                ->salutation(config('app.name'));
        });

        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $broker = config('auth.defaults.passwords');
            $expires = config("auth.passwords.{$broker}.expire", 60);

            return (new MailMessage)
                ->subject(__('auth.mail.reset_subject'))
                ->greeting(__('auth.mail.greeting', ['name' => $notifiable->name ?: __('common.user')]))
                ->line(__('auth.mail.reset_intro'))
                ->action(__('auth.mail.reset_action'), url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)))
                ->line(__('auth.mail.reset_expires', ['minutes' => $expires]))
                ->line(__('auth.mail.reset_ignore'))
                ->salutation(config('app.name'));
        });

        Gate::policy(Place::class, PlacePolicy::class);
        Gate::policy(PlaceCategory::class, PlaceCategoryPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);

        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
