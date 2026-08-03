<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'place_id', 'service_id',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $favorite): void {
            if (filled($favorite->place_id) === filled($favorite->service_id)) {
                throw new InvalidArgumentException('A favorite must target exactly one place or service.');
            }
        });
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
