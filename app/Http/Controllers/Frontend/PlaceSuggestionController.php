<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaceSuggestionRequest;
use App\Models\PlaceCategory;
use App\Models\PlaceSuggestion;
use App\Services\SuggestionService;

class PlaceSuggestionController extends Controller
{
    public function create()
    {
        $categories = PlaceCategory::active()->orderBy('name')->get();

        return view('pages.places.suggest', compact('categories'));
    }

    public function store(StorePlaceSuggestionRequest $request, SuggestionService $suggestionService)
    {
        $suggestionService->createSuggestion(PlaceSuggestion::class, $request->validated());

        return redirect()->route('place-suggestions.create')
            ->with('success', 'Thank you! Your place suggestion has been submitted for review.');
    }
}
