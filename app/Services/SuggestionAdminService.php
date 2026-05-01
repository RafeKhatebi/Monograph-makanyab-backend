<?php

namespace App\Services;

use App\Enums\SuggestionStatus;
use App\Models\Place;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class SuggestionAdminService
{
    public function __construct(
        private readonly SlugService $slugService
    ) {}

    private const BASE_FIELDS = [
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
    ];

    private const PLACE_FIELDS = [
        'place_category_id',
    ];

    private const SERVICE_FIELDS = [
        'service_category_id',
    ];

    public function approve(Model $suggestion, string $targetClass, ?string $adminNote = null): Model
    {
        $target = $targetClass::create($this->buildTargetPayload($suggestion, $targetClass));

        $suggestion->update([
            'suggestion_status' => SuggestionStatus::Approved,
            'admin_note' => $adminNote,
            'approved_at' => now(),
        ]);

        return $target;
    }

    public function reject(Model $suggestion, ?string $adminNote = null): Model
    {
        $suggestion->update([
            'suggestion_status' => SuggestionStatus::Rejected,
            'admin_note' => $adminNote,
            'rejected_at' => now(),
        ]);

        return $suggestion;
    }

    private function buildTargetPayload(Model $suggestion, string $targetClass): array
    {
        $fields = $this->getFieldsForTarget($targetClass);

        return array_merge(
            Arr::only($suggestion->toArray(), $fields),
            [
                'user_id' => $suggestion->user_id,
                'slug' => $this->slugService->createUniqueSlug($targetClass, $suggestion->name),
            ]
        );
    }

    private function getFieldsForTarget(string $targetClass): array
    {
        if ($targetClass === Place::class) {
            return array_merge(self::BASE_FIELDS, self::PLACE_FIELDS);
        }

        if ($targetClass === Service::class) {
            return array_merge(self::BASE_FIELDS, self::SERVICE_FIELDS);
        }

        throw new InvalidArgumentException("Unsupported target class [{$targetClass}] for suggestion approval.");
    }
}
