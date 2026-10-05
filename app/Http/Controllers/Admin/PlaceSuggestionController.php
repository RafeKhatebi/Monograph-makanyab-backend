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
        return $this->listing($request, $request->query('status'));
    }

    public function pending(Request $request)
    {
        return $this->listing($request, SuggestionStatus::Pending->value);
    }

    public function approved(Request $request)
    {
        return $this->listing($request, SuggestionStatus::Approved->value);
    }

    public function rejected(Request $request)
    {
        return $this->listing($request, SuggestionStatus::Rejected->value);
    }

    private function listing(Request $request, ?string $status)
    {
        $suggestions = PlaceSuggestion::with(['category', 'user'])
            ->filterSuggestionStatus($status)
            ->searchSuggestion($request->query('search'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.place-suggestions.index', compact('suggestions', 'status'));
    }

    public function show(PlaceSuggestion $placeSuggestion)
    {
        $placeSuggestion->load(['category', 'user']);

        return view('admin.place-suggestions.show', compact('placeSuggestion'));
    }

    public function approve(ProcessSuggestionRequest $request, PlaceSuggestion $placeSuggestion, SuggestionAdminService $adminService)
    {
        if ($placeSuggestion->suggestion_status !== SuggestionStatus::Pending) {
            return back()->with('error', __('messages.admin.suggestions.already_processed'));
        }

        $adminService->approve($placeSuggestion, Place::class, $request->admin_note);

        return back()->with('success', __('messages.admin.suggestions.place_approved'));
    }

    public function reject(ProcessSuggestionRequest $request, PlaceSuggestion $placeSuggestion, SuggestionAdminService $adminService)
    {
        if ($placeSuggestion->suggestion_status !== SuggestionStatus::Pending) {
            return back()->with('error', __('messages.admin.suggestions.already_processed'));
        }

        $adminService->reject($placeSuggestion, $request->admin_note);

        return back()->with('success', __('messages.admin.suggestions.rejected'));
    }
}
