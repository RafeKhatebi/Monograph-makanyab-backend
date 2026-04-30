<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceSuggestionController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceSuggestion::with(['category', 'user']);

        if ($request->filled('status')) {
            $query->where('suggestion_status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('city', 'like', '%'.$request->search.'%')
                    ->orWhere('submitted_by_name', 'like', '%'.$request->search.'%')
                    ->orWhere('submitted_by_email', 'like', '%'.$request->search.'%');
            });
        }

        $suggestions = $query->latest()->paginate(20);

        return view('admin.service-suggestions.index', compact('suggestions'));
    }

    public function show(ServiceSuggestion $serviceSuggestion)
    {
        $serviceSuggestion->load(['category', 'user']);

        return view('admin.service-suggestions.show', compact('serviceSuggestion'));
    }

    public function approve(Request $request, ServiceSuggestion $serviceSuggestion)
    {
        if ($serviceSuggestion->suggestion_status !== 'pending') {
            return back()->with('error', 'This suggestion has already been processed.');
        }

        $request->validate([
            'admin_note' => 'nullable|string|max:2000',
        ]);

        Service::create([
            'user_id' => $serviceSuggestion->user_id ?? Auth::id(),
            'service_category_id' => $serviceSuggestion->service_category_id,
            'name' => $serviceSuggestion->name,
            'slug' => $this->createUniqueSlug($serviceSuggestion->name),
            'tagline' => $serviceSuggestion->tagline,
            'description' => $serviceSuggestion->description,
            'phone_1' => $serviceSuggestion->phone_1,
            'phone_2' => $serviceSuggestion->phone_2,
            'whatsapp' => $serviceSuggestion->whatsapp,
            'website' => $serviceSuggestion->website,
            'social_links' => $serviceSuggestion->social_links,
            'address' => $serviceSuggestion->address,
            'country' => $serviceSuggestion->country,
            'province' => $serviceSuggestion->province,
            'city' => $serviceSuggestion->city,
            'district' => $serviceSuggestion->district,
            'subdistrict' => $serviceSuggestion->subdistrict,
            'village' => $serviceSuggestion->village,
            'rt_rw' => $serviceSuggestion->rt_rw,
            'neighborhood' => $serviceSuggestion->neighborhood,
            'postal_code' => $serviceSuggestion->postal_code,
            'latitude' => $serviceSuggestion->latitude,
            'longitude' => $serviceSuggestion->longitude,
            'status' => $serviceSuggestion->status,
            'price_level' => $serviceSuggestion->price_level,
            'is_verified' => false,
            'is_active' => true,
        ]);

        $serviceSuggestion->update([
            'suggestion_status' => 'approved',
            'admin_note' => $request->admin_note,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Suggestion approved and service added to the catalogue.');
    }

    public function reject(Request $request, ServiceSuggestion $serviceSuggestion)
    {
        if ($serviceSuggestion->suggestion_status !== 'pending') {
            return back()->with('error', 'This suggestion has already been processed.');
        }

        $request->validate([
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $serviceSuggestion->update([
            'suggestion_status' => 'rejected',
            'admin_note' => $request->admin_note,
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Suggestion rejected successfully.');
    }

    private function createUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Service::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        return $slug;
    }
}
