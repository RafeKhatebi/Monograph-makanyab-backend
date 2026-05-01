<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SuggestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProcessSuggestionRequest;
use App\Models\Service;
use App\Models\ServiceSuggestion;
use App\Services\SuggestionAdminService;
use Illuminate\Http\Request;

class ServiceSuggestionController extends Controller
{
    public function index(Request $request)
    {
        $suggestions = ServiceSuggestion::with(['category', 'user'])
            ->filterSuggestionStatus($request->query('status'))
            ->searchSuggestion($request->query('search'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.service-suggestions.index', compact('suggestions'));
    }

    public function show(ServiceSuggestion $serviceSuggestion)
    {
        $serviceSuggestion->load(['category', 'user']);

        return view('admin.service-suggestions.show', compact('serviceSuggestion'));
    }

    public function approve(ProcessSuggestionRequest $request, ServiceSuggestion $serviceSuggestion, SuggestionAdminService $adminService)
    {
        if ($serviceSuggestion->suggestion_status !== SuggestionStatus::Pending) {
            return back()->with('error', 'This suggestion has already been processed.');
        }

        $adminService->approve($serviceSuggestion, Service::class, $request->admin_note);

        return back()->with('success', 'Suggestion approved and service added to the catalogue.');
    }

    public function reject(ProcessSuggestionRequest $request, ServiceSuggestion $serviceSuggestion, SuggestionAdminService $adminService)
    {
        if ($serviceSuggestion->suggestion_status !== SuggestionStatus::Pending) {
            return back()->with('error', 'This suggestion has already been processed.');
        }

        $adminService->reject($serviceSuggestion, $request->admin_note);

        return back()->with('success', 'Suggestion rejected successfully.');
    }
}
