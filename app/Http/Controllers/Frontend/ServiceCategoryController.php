<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('services')
            ->with('parent:id,name')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.service-categories.index', compact('categories'));
    }

    public function show(string $slug)
    {
        $category = ServiceCategory::withCount('services')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $services = Service::with(['category', 'media'])
            ->where('service_category_id', $category->id)
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->paginate(12);

        $subcategories = ServiceCategory::withCount('services')
            ->where('parent_id', $category->id)
            ->where('is_active', true)
            ->get();

        return view('pages.service-categories.show', compact('category', 'services', 'subcategories'));
    }
}
