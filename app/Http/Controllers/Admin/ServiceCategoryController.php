<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceCategoryRequest;
use App\Http\Requests\UpdateServiceCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::with('parent')
            ->withCount('services')
            ->latest()
            ->paginate(20);

        return view('admin.service-categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.service-categories.create', compact('categories'));
    }

    public function store(StoreServiceCategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        ServiceCategory::create($validated);

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category created successfully.');
    }

    public function show(ServiceCategory $serviceCategory)
    {
        $serviceCategory->loadCount(['services', 'children']);

        return view('admin.service-categories.show', compact('serviceCategory'));
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        $categories = ServiceCategory::whereNull('parent_id')
            ->where('id', '!=', $serviceCategory->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.service-categories.edit', compact('serviceCategory', 'categories'));
    }

    public function update(UpdateServiceCategoryRequest $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $serviceCategory->update($validated);

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category updated successfully.');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        if ($serviceCategory->services()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated services.');
        }

        if ($serviceCategory->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with subcategories.');
        }

        $serviceCategory->delete();

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category deleted successfully.');
    }
}
