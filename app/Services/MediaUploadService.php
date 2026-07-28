<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MediaUploadService
{
    /**
     * @param  array<int, UploadedFile>  $images
     */
    public function attachImages(
        Model $model,
        array $images,
        string $directory,
        ?int $coverImageIndex = null
    ): void {
        $storedPaths = [];
        $hasCover = $model->media()->where('type', 'image')->where('is_cover', true)->exists();
        $nextSortOrder = (int) $model->media()->where('type', 'image')->max('sort_order') + 1;
        $knownHashes = $model->media()
            ->where('type', 'image')
            ->get(['disk', 'file_path'])
            ->mapWithKeys(function ($media): array {
                $disk = Storage::disk($media->disk ?: 'public');

                return $disk->exists($media->file_path)
                    ? [hash_file('sha256', $disk->path($media->file_path)) => true]
                    : [];
            })
            ->all();

        try {
            foreach (array_values($images) as $index => $image) {
                if (! $image instanceof UploadedFile || ! $image->isValid()) {
                    continue;
                }

                $hash = hash_file('sha256', $image->getRealPath());
                if (isset($knownHashes[$hash])) {
                    continue;
                }
                $knownHashes[$hash] = true;

                $path = $image->store($directory, 'public');
                $storedPaths[] = $path;
                $isCover = $coverImageIndex === $index || (! $hasCover && $index === 0);

                if ($isCover) {
                    $model->media()->where('type', 'image')->update(['is_cover' => false]);
                    $hasCover = true;
                }

                $model->media()->create([
                    'file_path' => $path,
                    'disk' => 'public',
                    'mime_type' => $image->getMimeType(),
                    'file_size' => $image->getSize(),
                    'type' => 'image',
                    'is_cover' => $isCover,
                    'sort_order' => $nextSortOrder++,
                ]);
            }
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedPaths);

            throw $exception;
        }
    }

    /**
     * Remove only media records that belong to the supplied model.
     *
     * Files are deleted after commit so a database rollback never leaves records
     * pointing at files that were already removed.
     *
     * @param  array<int, int|string>  $mediaIds
     */
    public function removeImages(Model $model, array $mediaIds): void
    {
        $media = $model->media()
            ->where('type', 'image')
            ->whereIn('id', $mediaIds)
            ->get();

        if ($media->isEmpty()) {
            return;
        }

        $removedCover = $media->contains('is_cover', true);
        $files = $media->map(fn (Media $item) => [
            'disk' => $item->disk ?: 'public',
            'path' => $item->file_path,
        ])->all();

        $model->media()->whereKey($media->modelKeys())->delete();

        if ($removedCover) {
            $model->media()
                ->where('type', 'image')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->limit(1)
                ->update(['is_cover' => true]);
        }

        DB::afterCommit(function () use ($files): void {
            foreach ($files as $file) {
                Storage::disk($file['disk'])->delete($file['path']);
            }
        });
    }

    public function setCoverImage(Model $model, int $mediaId): void
    {
        $media = $model->media()->where('type', 'image')->findOrFail($mediaId);

        $model->media()->where('type', 'image')->update(['is_cover' => false]);
        $media->update(['is_cover' => true]);
    }
}
