<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationOtp;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
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

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();
        $otp = EmailVerificationOtp::findValidForUser($user, $request->input('otp_code'));

        if (! $otp) {
            return back()->withErrors([
                'otp_code' => __('auth.verification_otp_invalid'),
            ]);
        }

        $otp->update(['verified_at' => now()]);

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(route('home', absolute: false))
            ->with('status', __('auth.verification_otp_verified'));
    }
}

