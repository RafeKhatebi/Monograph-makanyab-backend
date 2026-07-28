<?php

namespace App\Http\Requests\Admin\Concerns;

use Illuminate\Validation\Validator;

trait ValidatesPlaceLocationAndImages
{
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $locations = config('afghanistan_locations', []);
                $province = $this->string('province')->toString();
                $district = $this->string('district')->toString();

                if ($province !== '' && ! array_key_exists($province, $locations)) {
                    $validator->errors()->add('province', 'Select a valid Afghanistan province.');
                } elseif ($district !== '' && ! in_array($district, $locations[$province] ?? [], true)) {
                    $validator->errors()->add('district', 'The selected district does not belong to the selected province.');
                }

                $existingImages = $this->route('place')?->media()
                    ->where('type', 'image')
                    ->whereNotIn('id', $this->input('remove_media', []))
                    ->count() ?? 0;
                $newImages = count(array_filter((array) $this->file('images')));

                if ($existingImages + $newImages > 10) {
                    $validator->errors()->add('images', 'A place can have at most 10 images.');
                }

                if ($newImages > 0 && $this->filled('cover_image_index')
                    && $this->integer('cover_image_index') >= $newImages) {
                    $validator->errors()->add('cover_image_index', 'Select a cover image from the uploaded images.');
                }
            },
        ];
    }

    protected function preparePlaceLocation(): void
    {
        if (! $this->filled('city') && $this->filled('district')) {
            $this->merge(['city' => $this->input('district')]);
        }
    }
}
