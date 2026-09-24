<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home', absolute: false));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('Failed to send verification email: '.$e->getMessage(), [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
            ]);

            return back()->withErrors([
                'email' => __('auth.verification_send_failed'),
            ]);
        }

        return back()->with('status', __('auth.verification_sent'));
    }
}
