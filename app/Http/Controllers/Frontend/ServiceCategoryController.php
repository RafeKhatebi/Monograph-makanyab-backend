<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = ServiceCategory::withCount([
            'services' => fn ($query) => $query->where('is_active', true),
        ])
            ->with('parent:id,name')
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->query('search');

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('keywords', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(24)
            ->withQueryString();

        return view('pages.service-categories.index', compact('categories'));
    }

    public function show(string $slug)
    {
        $category = ServiceCategory::withCount('services')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $categoryIds = $category->descendantIds(activeOnly: true)
            ->prepend($category->id)
            ->unique()
            ->values();

        $services = Service::with(['category', 'media'])
            ->withCount(['reviews as reviews_count' => fn ($query) => $query->approved()])
            ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->approved()], 'rating')
            ->whereIn('service_category_id', $categoryIds)
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $category->setAttribute('services_count', $services->total());

        $subcategories = ServiceCategory::withCount([
            'services' => fn ($query) => $query->where('is_active', true),
        ])
            ->where('parent_id', $category->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.service-categories.show', compact('category', 'services', 'subcategories'));
    }
}
