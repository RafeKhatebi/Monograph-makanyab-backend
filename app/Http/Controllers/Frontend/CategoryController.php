<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = PlaceCategory::withCount([
            'places' => fn ($query) => $query->where('is_active', true),
        ])
            ->with('parent:id,name')
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->query('search');

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('keywords', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(24)
            ->withQueryString();

        return view('pages.categories.index', compact('categories'));
    }

    public function show(string $slug)
    {
        $category = PlaceCategory::withCount('places')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $categoryIds = $category->descendantIds(activeOnly: true)
            ->prepend($category->id)
            ->unique()
            ->values();

        $places = Place::with(['category', 'media'])
            ->whereIn('place_category_id', $categoryIds)
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $category->setAttribute('places_count', $places->total());

        $subcategories = PlaceCategory::withCount([
            'places' => fn ($query) => $query->where('is_active', true),
        ])
            ->where('parent_id', $category->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.categories.show', compact('category', 'places', 'subcategories'));
    }
}
