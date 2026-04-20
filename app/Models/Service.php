<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id', 'service_category_id', 'name', 'slug', 'tagline', 'description',
        'phone_1', 'phone_2', 'whatsapp', 'website', 'social_links',
        'address', 'country', 'province', 'city', 'district', 'subdistrict',
        'village', 'rt_rw', 'neighborhood', 'postal_code',
        'latitude', 'longitude',
        'status', 'price_level', 'is_verified', 'is_active',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function coverImage(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')->where('is_cover', true);
    }

    public function getImagesAttribute()
    {
        return $this->media()->where('type', 'image')->pluck('file_path')->toArray();
    }
}
