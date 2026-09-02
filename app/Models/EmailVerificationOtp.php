<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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

    public static function generateForUser(User $user): self
    {
        $otp = static::create([
            'user_id' => $user->id,
            'otp_code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes(10),
        ]);

        $user->otps()->whereNull('verified_at')->update(['verified_at' => now()]);

        return $otp;
    }

    public static function findValidForUser(User $user, string $otpCode): ?self
    {
        return static::where('user_id', $user->id)
            ->whereNull('verified_at')
            ->where('otp_code', $otpCode)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }
}
