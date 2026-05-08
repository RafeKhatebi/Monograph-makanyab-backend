<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = ServiceCategory::query()
            ->with('parent:id,name,slug')
            ->when($request->boolean('active', true), fn ($q) => $q->where('is_active', true))
            ->when($request->parent_id, fn ($q, $v) => $q->where('parent_id', $v))
            ->when($request->has('root'), fn ($q) => $q->whereNull('parent_id'))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    public function show(ServiceCategory $serviceCategory): JsonResponse
    {
        $serviceCategory->load('parent:id,name,slug');

        return response()->json($serviceCategory);
    }
}
