<?php

namespace App\Http\Requests;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Service|null $service */
        $service = $this->route('service');

        return $service !== null
            && ($this->user()?->isAdmin() || $service->user_id === $this->user()?->id);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'service_category_id' => 'required|exists:service_categories,id',
            'description' => 'required|string',
            'tagline' => 'nullable|string|max:255',
            'phone_1' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'country' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'subdistrict' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'rt_rw' => 'nullable|string|max:20',
            'neighborhood' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => ['nullable', Rule::enum(PlaceStatus::class)],
            'price_level' => ['nullable', Rule::enum(PriceLevel::class)],
            'is_active' => 'nullable|boolean',
            'images' => 'sometimes|array|max:10',
            'images.*' => 'required|file|image|mimetypes:image/jpeg,image/png,image/webp|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_media' => 'sometimes|array',
            'remove_media.*' => [
                'integer',
                Rule::exists('media', 'id')->where(fn ($query) => $query
                    ->where('mediable_type', Service::class)
                    ->where('mediable_id', $this->route('service')?->id)),
            ],
            'cover_media_id' => [
                'nullable',
                'integer',
                Rule::exists('media', 'id')->where(fn ($query) => $query
                    ->where('mediable_type', Service::class)
                    ->where('mediable_id', $this->route('service')?->id)),
            ],
            'cover_image_index' => 'nullable|integer|min:0|max:9',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $existing = $this->route('service')->media()
                    ->where('type', 'image')
                    ->whereNotIn('id', $this->input('remove_media', []))
                    ->count();

                $newImages = count(array_filter((array) $this->file('images')));
                if ($existing + $newImages > 10) {
                    $validator->errors()->add('images', 'A service can have at most 10 images.');
                }

                if ($newImages > 0 && $this->filled('cover_image_index')
                    && $this->integer('cover_image_index') >= $newImages) {
                    $validator->errors()->add('cover_image_index', 'Select a cover image from the uploaded images.');
                }
            },
        ];
    }
}
