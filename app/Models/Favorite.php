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
        'user_id', 'place_id', 'service_id', 'post_id',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $favorite): void {
            $targets = collect([$favorite->place_id, $favorite->service_id, $favorite->post_id])
                ->filter(fn ($target) => filled($target))
                ->count();

            if ($targets !== 1) {
                throw new InvalidArgumentException('A favorite must target exactly one place, service, or post.');
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

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
