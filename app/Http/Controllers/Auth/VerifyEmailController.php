<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey()) ||
            ! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            return redirect()->route('verification.notice')->withErrors([
                'email' => __('auth.verification_invalid'),
            ]);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home', absolute: false))
                ->with('status', __('auth.email_verified'));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('home', absolute: false))
            ->with('status', __('auth.email_verified'));
    }
}
