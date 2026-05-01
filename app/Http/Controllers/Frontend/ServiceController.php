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
        $services = Service::query()
            ->with(['category', 'media'])
            ->active()
            ->filterSearch($request->query('search'))
            ->filterCategorySlug($request->query('category'))
            ->when($request->city, fn ($q, $v) => $q->where('city', 'like', "%{$v}%"))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->price_level, fn ($q, $v) => $q->where('price_level', $v))
            ->filterVerified($request->boolean('verified'))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $categories = ServiceCategory::active()->orderBy('name')->get();

        return view('pages.services.index', compact('services', 'categories'));
    }

    public function show(Service $service)
    {
        abort_if(! $service->is_active, 404);

        $service->load(['category', 'media']);

        $similar = Service::with('media')
            ->where('service_category_id', $service->service_category_id)
            ->where('id', '!=', $service->id)
            ->active()
            ->limit(4)
            ->get();

        return view('pages.services.show', compact('service', 'similar'));
    }
}
