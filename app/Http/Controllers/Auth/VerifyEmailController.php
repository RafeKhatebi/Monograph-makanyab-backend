<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationOtp;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
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
