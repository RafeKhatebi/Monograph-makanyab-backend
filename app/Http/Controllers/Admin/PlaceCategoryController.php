<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaceCategory;
use Illuminate\Http\Request;
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
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:place_categories,name',
            'slug' => 'nullable|string|max:255|unique:place_categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:place_categories,id',
            'icon' => 'nullable|image|max:1024',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('categories', 'public');
        }

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
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, PlaceCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:place_categories,name,'.$category->id,
            'slug' => 'nullable|string|max:255|unique:place_categories,slug,'.$category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:place_categories,id',
            'icon' => 'nullable|image|max:1024',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('categories', 'public');
        }

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
