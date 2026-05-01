<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\MediaUploadService;
use App\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query()->with(['category', 'user']);

        if ($request->filled('service_category')) {
            $query->where('service_category_id', $request->integer('service_category'));
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->boolean('is_verified'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $query->filterSearch($request->query('search'));

        $services = $query->latest()->paginate(20)->withQueryString();
        $categories = ServiceCategory::active()->orderBy('name')->get();

        return view('admin.services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::active()
            ->orderBy('name')
            ->get();

        return view('admin.services.create', compact('categories'));
    }

    public function store(StoreServiceRequest $request, SlugService $slugService, MediaUploadService $mediaUploadService)
    {
        $validated = $request->validated();
        $validated['slug'] = $slugService->createUniqueSlug(Service::class, $validated['name']);
        $validated['user_id'] = Auth::id();
        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['status'] = $validated['status'] ?? PlaceStatus::Open->value;
        $validated['price_level'] = $validated['price_level'] ?? PriceLevel::Medium->value;

        $service = Service::create($validated);

        if ($request->hasFile('images')) {
            $mediaUploadService->attachImages($service, $request->file('images'), 'services');
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        $service->load(['category', 'user', 'media']);

        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::active()
            ->orderBy('name')
            ->get();

        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(UpdateServiceRequest $request, Service $service, SlugService $slugService, MediaUploadService $mediaUploadService)
    {
        $validated = $request->validated();
        $validated['slug'] = $slugService->createUniqueSlug(Service::class, $validated['name'], $service->id);
        $validated['is_verified'] = $request->boolean('is_verified');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['status'] = $validated['status'] ?? PlaceStatus::Open->value;
        $validated['price_level'] = $validated['price_level'] ?? PriceLevel::Medium->value;

        $service->update($validated);

        if ($request->hasFile('images')) {
            $mediaUploadService->attachImages($service, $request->file('images'), 'services');
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    public function toggleVerification(Service $service)
    {
        $service->update(['is_verified' => ! $service->is_verified]);

        return back()->with('success', 'Verification status updated.');
    }

    public function toggleActive(Service $service)
    {
        $service->update(['is_active' => ! $service->is_active]);

        return back()->with('success', 'Active status updated.');
    }
}
