<?php

namespace App\Models;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use App\Enums\SuggestionStatus;
use App\Models\Traits\SuggestionFilterable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceSuggestion extends Model
{
    use HasFactory, HasUuids, SuggestionFilterable;

    protected $fillable = [
        'user_id',
        'place_category_id',
        'name',
        'tagline',
        'description',
        'phone_1',
        'phone_2',
        'whatsapp',
        'website',
        'social_links',
        'address',
        'country',
        'province',
        'city',
        'district',
        'subdistrict',
        'village',
        'rt_rw',
        'neighborhood',
        'postal_code',
        'latitude',
        'longitude',
        'status',
        'price_level',
        'submitted_by_name',
        'submitted_by_email',
        'suggestion_status',
        'admin_note',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'social_links' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'status' => PlaceStatus::class,
        'price_level' => PriceLevel::class,
        'suggestion_status' => SuggestionStatus::class,
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PlaceCategory::class, 'place_category_id');
    }
}
