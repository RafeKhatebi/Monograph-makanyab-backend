<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaceCategoryRequest;
use App\Http\Requests\UpdatePlaceCategoryRequest;
use App\Models\PlaceCategory;
use Illuminate\Support\Str;

class PlaceCategoryController extends Controller
{
    public function index()
    {
        $categories = PlaceCategory::with('parent')
            ->withCount('places')
            ->latest()
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = PlaceCategory::whereNull('parent_id')
            ->active()
            ->orderBy('name')
            ->get();
        // will route to create a category in admin section

        return view('admin.categories.create', compact('categories'));
    }

    public function store(StorePlaceCategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        PlaceCategory::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(PlaceCategory $category)
    {
        $category->loadCount(['places', 'children']);

        return view('admin.categories.show', compact('category'));
    }

    public function edit(PlaceCategory $category)
    {
        $categories = PlaceCategory::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->active()
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(UpdatePlaceCategoryRequest $request, PlaceCategory $category)
    {
        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(PlaceCategory $category)
    {
        if ($category->places()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated places.');
        }

        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with subcategories.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
