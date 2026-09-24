<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendEmailVerificationOtpNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class AuthMailPreviewController extends Controller
{
    public function index(): View
    {
        return view('auth.preview.index');
    }

    public function verification(Request $request): View
    {
        $user = User::factory()->unverified()->create();

        return view('auth.preview.show', [
            'title' => 'Email Verification Preview',
            'preview' => $this->previewData((new SendEmailVerificationOtpNotification)->toMail($user)),
        ]);
    }

    public function passwordReset(Request $request): View
    {
        $token = Password::broker()->createToken($request->user());

        return view('auth.preview.show', [
            'title' => 'Password Reset Preview',
            'preview' => $this->previewData((new ResetPassword($token))->toMail($request->user())),
        ]);
    }

    /**
     * @return array{
     *     subject: string|null,
     *     introLines: array<int, string>,
     *     actionText: string|null,
     *     actionUrl: string|null,
     *     outroLines: array<int, string>
     * }
     */
    private function previewData(MailMessage $message): array
    {
        return [
            'subject' => $message->subject,
            'introLines' => $message->introLines,
            'actionText' => $message->actionText,
            'actionUrl' => $message->actionUrl,
            'outroLines' => $message->outroLines,
        ];
    }
}
