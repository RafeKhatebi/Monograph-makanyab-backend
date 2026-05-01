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

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Place::query()
            ->with(['category', 'user'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

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

        return view('admin.places.create', compact('categories'));
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

        $place = Place::create($validated);

        if ($request->hasFile('images')) {
            $mediaUploadService->attachImages($place, $request->file('images'), 'places');
        }

        return redirect()->route('admin.places.index')
            ->with('success', 'Place created successfully.');
    }

    public function show(Place $place)
    {
        $place->load(['category', 'user', 'media'])
            ->loadCount('reviews')
            ->loadAvg('reviews', 'rating');

        return view('admin.places.show', compact('place'));
    }

    public function edit(Place $place)
    {
        $categories = PlaceCategory::active()->orderBy('name')->get();

        return view('admin.places.edit', compact('place', 'categories'));
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

        $place->update($validated);

        if ($request->hasFile('images')) {
            $mediaUploadService->attachImages($place, $request->file('images'), 'places');
        }

        return redirect()->route('admin.places.index')
            ->with('success', 'Place updated successfully.');
    }

    public function destroy(Place $place)
    {
        $place->delete();

        return redirect()->route('admin.places.index')
            ->with('success', 'Place deleted successfully.');
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
