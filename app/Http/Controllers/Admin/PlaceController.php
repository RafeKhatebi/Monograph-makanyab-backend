<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Place::with(['category', 'user'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('category')) {
            $query->where('place_category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('address', 'like', '%'.$request->search.'%');
            });
        }

        $places = $query->latest()->paginate(20);
        $categories = PlaceCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.places.index', compact('places', 'categories'));
    }

    public function create()
    {
        $categories = PlaceCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.places.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'place_category_id' => 'required|exists:place_categories,id',
            'address' => 'required|string|max:500',
            'phone_1' => 'required|string|max:20',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'website' => 'nullable|url|max:255',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['user_id'] = Auth::id();
        $validated['is_verified'] = $request->has('is_verified');
        $validated['is_active'] = $request->has('is_active');
        $validated['country'] = 'Afghanistan';
        $validated['province'] = 'Herat';
        $validated['city'] = 'Herat';
        $validated['district'] = 'Unknown';

        $place = Place::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('places', 'public');
                $place->media()->create(['file_path' => $path, 'type' => 'image']);
            }
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
        $categories = PlaceCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.places.edit', compact('place', 'categories'));
    }

    public function update(Request $request, Place $place)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'place_category_id' => 'required|exists:place_categories,id',
            'address' => 'required|string|max:500',
            'phone_1' => 'required|string|max:20',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'website' => 'nullable|url|max:255',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_verified'] = $request->has('is_verified');
        $validated['is_active'] = $request->has('is_active');

        $place->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('places', 'public');
                $place->media()->create(['file_path' => $path, 'type' => 'image']);
            }
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
