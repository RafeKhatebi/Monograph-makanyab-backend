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
                ->subject('Verify your email address')
                ->line('Please verify your email address before continuing.')
                ->action('Verify Email Address', $url)
                ->line('This verification link will expire in '.config('auth.verification.expire', 60).' minutes.')
                ->line('If you did not create an account, no further action is required.');
        });

        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $broker = config('auth.defaults.passwords');
            $expires = config("auth.passwords.{$broker}.expire", 60);

            return (new MailMessage)
                ->subject('Reset your password')
                ->line('Use the button below to reset your password.')
                ->action('Reset Password', url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)))
                ->line('This password reset link will expire in '.$expires.' minutes.')
                ->line('If you did not request a password reset, no further action is required.');
        });

        Gate::policy(Place::class, PlacePolicy::class);
        Gate::policy(PlaceCategory::class, PlaceCategoryPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);

        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
