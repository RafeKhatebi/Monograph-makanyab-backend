<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Place extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id', 'place_categories_id', 'name', 'slug', 'tagline', 'description',
        'phone_1', 'phone_2', 'whatsapp', 'website', 'social_links',
        'address', 'country', 'province', 'city', 'district', 'subdistrict',
        'village', 'rt_rw', 'neighborhood', 'postal_code',
        'latitude', 'longitude', 'cover_image', 'gallery',
        'status', 'price_level', 'is_verified', 'is_active',
    ];

    protected $casts = [
        'social_links' => 'array',
        'gallery' => 'array',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PlaceCategory::class, 'place_categories_id');
    }
}
