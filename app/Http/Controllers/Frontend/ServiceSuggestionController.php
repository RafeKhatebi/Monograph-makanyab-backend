<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceSuggestionRequest;
use App\Models\ServiceCategory;
use App\Models\ServiceSuggestion;
use App\Services\SuggestionService;

class ServiceSuggestionController extends Controller
{
    public function create()
    {
        $categories = ServiceCategory::active()->orderBy('name')->get();

        return view('pages.services.suggest', compact('categories'));
    }

    public function store(StoreServiceSuggestionRequest $request, SuggestionService $suggestionService)
    {
        $suggestionService->createSuggestion(ServiceSuggestion::class, $request->validated());

        return redirect()->route('service-suggestions.create')
            ->with('success', 'Thank you! Your service suggestion has been submitted for review.');
    }
}
