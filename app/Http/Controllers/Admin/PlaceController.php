<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePlaceRequest;
use App\Http\Requests\Admin\UpdatePlaceRequest;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Services\MediaUploadService;
use App\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Place::query()
            ->with(['category', 'user'])
            ->withCount(['reviews as reviews_count' => fn ($query) => $query->approved()])
            ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->approved()], 'rating');

        if ($request->query('trashed') === 'with') {
            $query->withTrashed();
        }

        if ($request->query('trashed') === 'only') {
            $query->onlyTrashed();
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->boolean('is_verified'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('category')) {
            $query->where('place_category_id', $request->integer('category'));
        }

        $query->filterSearch($request->query('search'));

        $places = $query->latest()->paginate(20)->withQueryString();
        $categories = PlaceCategory::active()->orderBy('name')->get();

        return view('admin.places.index', compact('places', 'categories'));
    }

    public function create()
    {
        $categories = PlaceCategory::active()->orderBy('name')->get();
        $locations = config('afghanistan_locations');

        return view('admin.places.create', compact('categories', 'locations'));
    }

    public function store(StorePlaceRequest $request, SlugService $slugService, MediaUploadService $mediaUploadService)
    {
        $validated = $request->validated();
        $validated['slug'] = $slugService->createUniqueSlug(Place::class, $validated['name']);
        $validated['user_id'] = Auth::id();
        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['country'] = $request->input('country', 'Afghanistan');
        $validated['city'] = $validated['city'] ?? $validated['province'];
        $validated['status'] = $validated['status'] ?? PlaceStatus::Open->value;
        $validated['price_level'] = $validated['price_level'] ?? PriceLevel::Medium->value;

        DB::transaction(function () use ($request, $validated, $mediaUploadService): void {
            $place = Place::create($validated);

            if ($request->hasFile('images')) {
                $mediaUploadService->attachImages(
                    $place,
                    $request->file('images'),
                    'places',
                    $request->filled('cover_image_index') ? $request->integer('cover_image_index') : null
                );
            }
        });

        return redirect()->route('admin.places.index')
            ->with('success', 'Place created successfully.');
    }

    public function show(Place $place)
    {
        $place->load(['category', 'user', 'media'])
            ->loadCount(['reviews as reviews_count' => fn ($query) => $query->approved()])
            ->loadAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->approved()], 'rating');

        return view('admin.places.show', compact('place'));
    }

    public function edit(Place $place)
    {
        $categories = PlaceCategory::active()->orderBy('name')->get();
        $locations = config('afghanistan_locations');
        $place->load('media');

        return view('admin.places.edit', compact('place', 'categories', 'locations'));
    }

    public function update(UpdatePlaceRequest $request, Place $place, SlugService $slugService, MediaUploadService $mediaUploadService)
    {
        $validated = $request->validated();
        $validated['slug'] = $slugService->createUniqueSlug(Place::class, $validated['name'], $place->id);
        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['country'] = $request->input('country', 'Afghanistan');
        $validated['city'] = $validated['city'] ?? $validated['province'];
        $validated['status'] = $validated['status'] ?? PlaceStatus::Open->value;
        $validated['price_level'] = $validated['price_level'] ?? PriceLevel::Medium->value;

        DB::transaction(function () use ($request, $validated, $place, $mediaUploadService): void {
            $place->update($validated);

            $mediaUploadService->removeImages($place, $request->input('remove_media', []));

            if ($request->filled('cover_media_id')
                && ! in_array($request->integer('cover_media_id'), $request->input('remove_media', []), true)) {
                $mediaUploadService->setCoverImage($place, $request->integer('cover_media_id'));
            }

            if ($request->hasFile('images')) {
                $mediaUploadService->attachImages(
                    $place,
                    $request->file('images'),
                    'places',
                    $request->filled('cover_image_index') ? $request->integer('cover_image_index') : null
                );
            }
        });

        return redirect()->route('admin.places.index')
            ->with('success', 'Place updated successfully.');
    }

    public function destroy(Place $place)
    {
        $place->delete();

        return redirect()->route('admin.places.index')
            ->with('success', 'Place deleted successfully.');
    }

    public function restore(string $place)
    {
        $place = Place::withTrashed()
            ->where('slug', $place)
            ->firstOrFail();

        if (! $place->trashed()) {
            return redirect()->route('admin.places.index')
                ->with('info', 'Place is already active.');
        }

        $place->restore();

        return redirect()->route('admin.places.index', ['trashed' => 'with'])
            ->with('success', 'Place restored successfully.');
    }

    public function toggleVerification(Place $place)
    {
        $place->update(['is_verified' => ! $place->is_verified]);

        return back()->with('success', 'Verification status updated.');
    }

    public function toggleActive(Place $place)
    {
        $place->update(['is_active' => ! $place->is_active]);

        return back()->with('success', 'Active status updated.');
    }
}
