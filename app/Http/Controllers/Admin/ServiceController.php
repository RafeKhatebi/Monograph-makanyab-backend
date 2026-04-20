<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['category', 'user']);

        if ($request->filled('service_category')) {
            $query->where('service_category_id', $request->service_category);
        }

        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('address', 'like', '%'.$request->search.'%');
            });
        }

        $services = $query->latest()->paginate(20);
        $categories = ServiceCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.services.index', compact('services', 'categories'));

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.services.create', compact('categories'));
    }

    public function store(StoreServiceRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['user_id'] = Auth::id();
        $validated['is_verified'] = $request->has('is_verified');
        $validated['is_active'] = $request->has('is_active');
        $validated['status'] = $validated['status'] ?? 'open';
        $validated['price_level'] = $validated['price_level'] ?? 'medium';

        $service = Service::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('services', 'public');
                $service->media()->create(['file_path' => $path, 'type' => 'image']);
            }
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
        $categories = ServiceCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_verified'] = $request->has('is_verified');
        $validated['is_active'] = $request->has('is_active');
        $validated['status'] = $validated['status'] ?? 'open';
        $validated['price_level'] = $validated['price_level'] ?? 'medium';

        $service->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('services', 'public');
                $service->media()->create(['file_path' => $path, 'type' => 'image']);
            }
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
