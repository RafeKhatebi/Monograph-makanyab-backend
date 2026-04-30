<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceSuggestionController extends Controller
{
    public function create()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.services.suggest', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'phone_1' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string|max:500',
            'country' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'subdistrict' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'rt_rw' => 'nullable|string|max:20',
            'neighborhood' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:open,closed,temporarily_closed',
            'price_level' => 'required|in:low,medium,high,luxury',
        ];

        if (! Auth::check()) {
            $rules['submitted_by_name'] = 'required|string|max:255';
            $rules['submitted_by_email'] = 'required|email|max:255';
        }

        $validated = $request->validate($rules);

        $validated['user_id'] = Auth::id();
        $validated['submitted_by_name'] = Auth::check()
            ? Auth::user()->name
            : $validated['submitted_by_name'] ?? null;
        $validated['submitted_by_email'] = Auth::check()
            ? Auth::user()->email
            : $validated['submitted_by_email'] ?? null;
        $validated['suggestion_status'] = 'pending';

        ServiceSuggestion::create($validated);

        return redirect()->route('service-suggestions.create')
            ->with('success', 'Thank you! Your service suggestion has been submitted for review.');
    }
}
