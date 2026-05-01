<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class MediaUploadService
{
    /**
     * @param  array<int, UploadedFile>  $images
     */
    public function attachImages(Model $model, array $images, string $directory): void
    {
        foreach ($images as $image) {
            if (! $image instanceof UploadedFile) {
                continue;
            }

            $path = $image->store($directory, 'public');
            $model->media()->create([
                'file_path' => $path,
                'type' => 'image',
            ]);
        }
    }
}
