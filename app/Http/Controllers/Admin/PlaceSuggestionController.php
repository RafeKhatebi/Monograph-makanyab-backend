<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlaceSuggestionController extends Controller
{
    public function index(Request $request)
    {
        $query = PlaceSuggestion::with(['category', 'user']);

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

        return view('admin.place-suggestions.index', compact('suggestions'));
    }

    public function show(PlaceSuggestion $placeSuggestion)
    {
        $placeSuggestion->load(['category', 'user']);

        return view('admin.place-suggestions.show', compact('placeSuggestion'));
    }

    public function approve(Request $request, PlaceSuggestion $placeSuggestion)
    {
        if ($placeSuggestion->suggestion_status !== 'pending') {
            return back()->with('error', 'This suggestion has already been processed.');
        }

        $request->validate([
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $place = Place::create([
            'user_id' => $placeSuggestion->user_id ?? Auth::id(),
            'place_category_id' => $placeSuggestion->place_category_id,
            'name' => $placeSuggestion->name,
            'slug' => $this->createUniqueSlug($placeSuggestion->name),
            'tagline' => $placeSuggestion->tagline,
            'description' => $placeSuggestion->description,
            'phone_1' => $placeSuggestion->phone_1,
            'phone_2' => $placeSuggestion->phone_2,
            'whatsapp' => $placeSuggestion->whatsapp,
            'website' => $placeSuggestion->website,
            'social_links' => $placeSuggestion->social_links,
            'address' => $placeSuggestion->address,
            'country' => $placeSuggestion->country,
            'province' => $placeSuggestion->province,
            'city' => $placeSuggestion->city,
            'district' => $placeSuggestion->district,
            'subdistrict' => $placeSuggestion->subdistrict,
            'village' => $placeSuggestion->village,
            'rt_rw' => $placeSuggestion->rt_rw,
            'neighborhood' => $placeSuggestion->neighborhood,
            'postal_code' => $placeSuggestion->postal_code,
            'latitude' => $placeSuggestion->latitude,
            'longitude' => $placeSuggestion->longitude,
            'status' => $placeSuggestion->status,
            'price_level' => $placeSuggestion->price_level,
            'is_verified' => false,
            'is_active' => true,
        ]);

        $placeSuggestion->update([
            'suggestion_status' => 'approved',
            'admin_note' => $request->admin_note,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Suggestion approved and place added to the catalogue.');
    }

    public function reject(Request $request, PlaceSuggestion $placeSuggestion)
    {
        if ($placeSuggestion->suggestion_status !== 'pending') {
            return back()->with('error', 'This suggestion has already been processed.');
        }

        $request->validate([
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $placeSuggestion->update([
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

        while (Place::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        return $slug;
    }
}
