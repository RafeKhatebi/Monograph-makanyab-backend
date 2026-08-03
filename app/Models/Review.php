<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class Review extends Model
{
    // HasUuids trait is used to automatically generate UUIDs for the model's primary key.
    use HasFactory, HasUuids;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id', 'place_id', 'service_id', 'rating', 'comment', 'is_approved', 'moderation_status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $review): void {
            if (filled($review->place_id) === filled($review->service_id)) {
                throw new InvalidArgumentException('A review must target exactly one place or service.');
            }

            if ($review->isDirty('moderation_status')) {
                $review->is_approved = $review->moderation_status === self::STATUS_APPROVED;

                return;
            }

            if ($review->isDirty('is_approved') || ! $review->moderation_status) {
                $review->moderation_status = $review->is_approved
                    ? self::STATUS_APPROVED
                    : self::STATUS_PENDING;
            }
        });
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query
            ->where('is_approved', true)
            ->where('moderation_status', self::STATUS_APPROVED);
    }

    public function markPending(): bool
    {
        return $this->update(['moderation_status' => self::STATUS_PENDING]);
    }

    public function markApproved(): bool
    {
        return $this->update(['moderation_status' => self::STATUS_APPROVED]);
    }

    public function markRejected(): bool
    {
        return $this->update(['moderation_status' => self::STATUS_REJECTED]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
