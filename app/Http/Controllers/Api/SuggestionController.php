<?php

namespace App\Http\Controllers\Api;

use App\Enums\SuggestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaceSuggestionRequest;
use App\Http\Requests\StoreServiceSuggestionRequest;
use App\Models\PlaceSuggestion;
use App\Models\ServiceSuggestion;
use App\Services\SuggestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function storePlace(StorePlaceSuggestionRequest $request, SuggestionService $suggestionService): JsonResponse
    {
        $suggestion = $suggestionService->createSuggestion(PlaceSuggestion::class, $request->validated());

        return response()->json($suggestion, 201);
    }

    public function storeService(StoreServiceSuggestionRequest $request, SuggestionService $suggestionService): JsonResponse
    {
        $suggestion = $suggestionService->createSuggestion(ServiceSuggestion::class, $request->validated());

        return response()->json($suggestion, 201);
    }

    public function myPlaceSuggestions(Request $request): JsonResponse
    {
        $suggestions = PlaceSuggestion::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($suggestions);
    }

    public function myServiceSuggestions(Request $request): JsonResponse
    {
        $suggestions = ServiceSuggestion::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($suggestions);
    }

    public function adminPlaceQueue(Request $request): JsonResponse
    {
        $status = $request->query('status', SuggestionStatus::Pending->value);
        $items = PlaceSuggestion::query()
            ->where('suggestion_status', $status)
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return response()->json($items);
    }

    public function adminServiceQueue(Request $request): JsonResponse
    {
        $status = $request->query('status', SuggestionStatus::Pending->value);
        $items = ServiceSuggestion::query()
            ->where('suggestion_status', $status)
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return response()->json($items);
    }
}
