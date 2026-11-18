<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class EmailVerificationOtp extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Issue an OTP and return its plain-text value exactly once for delivery.
     * Only a password hash is persisted, so a database read cannot be used to
     * verify an email address.
     *
     * @return array{0: self, 1: string}
     */
    public static function issueForUser(User $user): array
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // A new code invalidates every outstanding code for this user.
        $user->otps()->whereNull('verified_at')->update(['verified_at' => now()]);

        $otp = static::create([
            'user_id' => $user->id,
            'otp_code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        return [$otp, $code];
    }

    public static function findValidForUser(User $user, string $otpCode): ?self
    {
        return static::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->get()
            ->first(fn (self $otp): bool => Hash::check($otpCode, $otp->otp_code));
    }
}
