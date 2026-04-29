<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::with(['category', 'media'])
            ->where('is_active', true)
            ->when($request->search, fn ($q, $v) => $q->where(function ($q) use ($v) {
                $q->where('name', 'like', "%{$v}%")
                  ->orWhere('tagline', 'like', "%{$v}%")
                  ->orWhere('city', 'like', "%{$v}%");
            }))
            ->when($request->category, fn ($q, $v) => $q->whereHas('category', fn ($q) => $q->where('slug', $v)))
            ->when($request->city, fn ($q, $v) => $q->where('city', 'like', "%{$v}%"))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->price_level, fn ($q, $v) => $q->where('price_level', $v))
            ->when($request->verified, fn ($q) => $q->where('is_verified', true))
            ->orderByDesc('created_at')
            ->paginate(12);

        $categories = ServiceCategory::where('is_active', true)->orderBy('name')->get();

        return view('pages.services.index', compact('services', 'categories'));
    }

    public function show(Service $service)
    {
        abort_if(! $service->is_active, 404);

        $service->load(['category', 'media']);

        $similar = Service::with('media')
            ->where('service_category_id', $service->service_category_id)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('pages.services.show', compact('service', 'similar'));
    }
}
