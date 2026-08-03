<?php

namespace App\Http\Controllers\Api;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Services\SlugService;
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
        abort_if(! $service->is_active, 404);

        $service->load([
            'category:id,name,slug',
            'user:id,name',
            'media',
        ]);

        return response()->json($service);
    }

    public function store(StoreServiceRequest $request, SlugService $slugService): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $validated['slug'] = $slugService->createUniqueSlug(Service::class, $validated['name']);
        $validated['status'] = $validated['status'] ?? PlaceStatus::Open->value;
        $validated['price_level'] = $validated['price_level'] ?? PriceLevel::Medium->value;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_verified'] = false;

        $service = Service::create($validated)->load(['category:id,name,slug', 'user:id,name']);

        return response()->json($service, 201);
    }

    public function update(UpdateServiceRequest $request, Service $service, SlugService $slugService): JsonResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $slugService->createUniqueSlug(Service::class, $validated['name'], $service->id);
        $validated['status'] = $validated['status'] ?? PlaceStatus::Open->value;
        $validated['price_level'] = $validated['price_level'] ?? PriceLevel::Medium->value;

        if (! $request->user()->isAdmin()) {
            unset($validated['is_verified']);
        }

        $service->update($validated);
        $service->load(['category:id,name,slug', 'user:id,name']);

        return response()->json($service);
    }

    public function destroy(Request $request, Service $service): JsonResponse
    {
        abort_unless($request->user()->can('delete', $service), 403);

        $service->delete();

        return response()->json(null, 204);
    }
}
