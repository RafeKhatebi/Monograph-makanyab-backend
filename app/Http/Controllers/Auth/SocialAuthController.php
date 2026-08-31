<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialAuthenticationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use RuntimeException;
use Throwable;

class SocialAuthController extends Controller
{
    public function __construct(
        private readonly SocialAuthenticationService $socialAuthenticationService
    ) {}

    public function redirect(string $provider): RedirectResponse
    {
        try {
            $this->socialAuthenticationService->ensureSupportedProvider($provider);
            $this->ensureProviderConfigured($provider);

            return Socialite::driver($provider)->redirect();
        } catch (RuntimeException $exception) {
            return redirect()->route('login')->withErrors([
                'social' => $exception->getMessage(),
            ]);
        }
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()->route('login')->withErrors([
                'social' => __('auth.social_failed'),
            ]);
        }

        try {
            $this->socialAuthenticationService->ensureSupportedProvider($provider);
            $this->ensureProviderConfigured($provider);

            $socialUser = Socialite::driver($provider)->user();
            $user = $this->socialAuthenticationService->findOrCreateUser($provider, $socialUser);

            if (! $user->is_active) {
                return redirect()->route('login')->withErrors([
                    'social' => __('auth.inactive'),
                ]);
            }

            Auth::login($user, remember: true);
            $request->session()->regenerate();

            return redirect()
                ->intended($user->isAdmin() ? route('admin.dashboard') : route('home', absolute: false))
                ->with('status', __('auth.login_success'));
        } catch (RuntimeException $exception) {
            return redirect()->route('login')->withErrors([
                'social' => $exception->getMessage(),
            ]);
        } catch (Throwable $exception) {
            Log::warning('Social authentication failed.', [
                'provider' => $provider,
                'exception' => $exception::class,
            ]);

            return redirect()->route('login')->withErrors([
                'social' => __('auth.social_failed'),
            ]);
        }
    }

    public function connectRedirect(string $provider): RedirectResponse
    {
        try {
            $this->socialAuthenticationService->ensureSupportedProvider($provider);
            $this->ensureProviderConfigured($provider);

            return Socialite::driver($provider)
                ->redirectUrl(route('social.connect.callback', $provider))
                ->redirect();
        } catch (RuntimeException $exception) {
            return redirect()->route('profile.index')->withErrors([
                'social' => $exception->getMessage(),
            ]);
        }
    }

    public function connectCallback(Request $request, string $provider): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()->route('profile.index')->withErrors([
                'social' => __('auth.social_failed'),
            ]);
        }

        try {
            $this->socialAuthenticationService->ensureSupportedProvider($provider);
            $this->ensureProviderConfigured($provider);

            $socialUser = Socialite::driver($provider)
                ->redirectUrl(route('social.connect.callback', $provider))
                ->user();
            $this->socialAuthenticationService->linkToUser($request->user(), $provider, $socialUser);

            return redirect()->route('profile.index')->with('status', __('auth.social_linked'));
        } catch (RuntimeException $exception) {
            return redirect()->route('profile.index')->withErrors([
                'social' => $exception->getMessage(),
            ]);
        } catch (Throwable $exception) {
            Log::warning('Social account linking failed.', [
                'provider' => $provider,
                'user_id' => $request->user()?->id,
                'exception' => $exception::class,
            ]);

            return redirect()->route('profile.index')->withErrors([
                'social' => __('auth.social_failed'),
            ]);
        }
    }

    public function disconnect(Request $request, string $provider): RedirectResponse
    {
        try {
            $this->socialAuthenticationService->ensureSupportedProvider($provider);
            $user = $request->user();
            $account = $user->socialAccounts()->where('provider', $provider)->first();

            if (! $account) {
                return redirect()->route('profile.index')->withErrors([
                    'social' => __('auth.social_failed'),
                ]);
            }

            if (! $user->hasUsablePassword() && $user->socialAccounts()->count() === 1) {
                return redirect()->route('profile.index')->withErrors([
                    'social' => __('auth.social_last_login_method'),
                ]);
            }

            $account->delete();

            return redirect()->route('profile.index')->with('status', __('auth.social_unlinked'));
        } catch (RuntimeException $exception) {
            return redirect()->route('profile.index')->withErrors(['social' => $exception->getMessage()]);
        }
    }

    private function ensureProviderConfigured(string $provider): void
    {
        $config = config("services.{$provider}");

        if (empty($config['client_id']) || empty($config['client_secret']) || empty($config['redirect'])) {
            throw new RuntimeException(__('auth.social_not_configured'));
        }
    }
}
