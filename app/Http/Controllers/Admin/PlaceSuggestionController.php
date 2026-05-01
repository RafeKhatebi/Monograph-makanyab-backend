<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SuggestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProcessSuggestionRequest;
use App\Models\Place;
use App\Models\PlaceSuggestion;
use App\Services\SuggestionAdminService;
use Illuminate\Http\Request;

class PlaceSuggestionController extends Controller
{
    public function index(Request $request)
    {
        $suggestions = PlaceSuggestion::with(['category', 'user'])
            ->filterSuggestionStatus($request->query('status'))
            ->searchSuggestion($request->query('search'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.place-suggestions.index', compact('suggestions'));
    }

    public function show(PlaceSuggestion $placeSuggestion)
    {
        $placeSuggestion->load(['category', 'user']);

        return view('admin.place-suggestions.show', compact('placeSuggestion'));
    }

    public function approve(ProcessSuggestionRequest $request, PlaceSuggestion $placeSuggestion, SuggestionAdminService $adminService)
    {
        if ($placeSuggestion->suggestion_status !== SuggestionStatus::Pending) {
            return back()->with('error', 'This suggestion has already been processed.');
        }

        $adminService->approve($placeSuggestion, Place::class, $request->admin_note);

        return back()->with('success', 'Suggestion approved and place added to the catalogue.');
    }

    public function reject(ProcessSuggestionRequest $request, PlaceSuggestion $placeSuggestion, SuggestionAdminService $adminService)
    {
        if ($placeSuggestion->suggestion_status !== SuggestionStatus::Pending) {
            return back()->with('error', 'This suggestion has already been processed.');
        }

        $adminService->reject($placeSuggestion, $request->admin_note);

        return back()->with('success', 'Suggestion rejected successfully.');
    }
}
