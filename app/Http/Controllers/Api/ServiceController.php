<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $services = Service::query()
            ->with(['category:id,name,slug', 'user:id,name'])
            ->where('is_active', true)
            ->when($request->city, fn ($q, $v) => $q->where('city', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->price_level, fn ($q, $v) => $q->where('price_level', $v))
            ->filterVerified($request->boolean('verified'))
            ->filterCategorySlug($request->query('category'))
            ->filterSearch($request->query('search'))
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($services);
    }

    public function show(Service $service): JsonResponse
    {
        $service->load([
            'category:id,name,slug',
            'user:id,name',
            'media',
        ]);

        return response()->json($service);
    }
}
