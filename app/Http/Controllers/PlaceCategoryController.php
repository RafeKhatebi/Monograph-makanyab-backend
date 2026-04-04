<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlaceCategoryRequest;
use App\Http\Requests\UpdatePlaceCategoryRequest;
use App\Models\PlaceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlaceCategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = PlaceCategory::query()
            ->with('parent:id,name,slug')
            ->when($request->boolean('active', true), fn ($q) => $q->where('is_active', true))
            ->when($request->parent_id, fn ($q, $v) => $q->where('parent_id', $v))
            ->when($request->has('root'), fn ($q) => $q->whereNull('parent_id'))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    public function store(StorePlaceCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        $category = PlaceCategory::create($validated);

        return response()->json($category, 201);
    }

    public function show(PlaceCategory $placeCategory): JsonResponse
    {
        $placeCategory->load('parent:id,name,slug');

        return response()->json($placeCategory);
    }

    public function update(UpdatePlaceCategoryRequest $request, PlaceCategory $placeCategory): JsonResponse
    {
        $placeCategory->update($request->validated());

        return response()->json($placeCategory);
    }

    public function destroy(PlaceCategory $placeCategory): JsonResponse
    {
        if ($placeCategory->places()->exists()) {
            return response()->json([
                'message' => 'Cannot delete a category that has places assigned to it.',
            ], 409);
        }

        $placeCategory->delete();

        return response()->json(null, 204);
    }
}
