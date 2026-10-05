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
        $suggestions = ServiceSuggestion::with(['category', 'user'])
            ->filterSuggestionStatus($status)
            ->searchSuggestion($request->query('search'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.service-suggestions.index', compact('suggestions', 'status'));
    }

    public function show(ServiceSuggestion $serviceSuggestion)
    {
        $serviceSuggestion->load(['category', 'user']);

        return view('admin.service-suggestions.show', compact('serviceSuggestion'));
    }

    public function approve(ProcessSuggestionRequest $request, ServiceSuggestion $serviceSuggestion, SuggestionAdminService $adminService)
    {
        if ($serviceSuggestion->suggestion_status !== SuggestionStatus::Pending) {
            return back()->with('error', __('messages.admin.suggestions.already_processed'));
        }

        $adminService->approve($serviceSuggestion, Service::class, $request->admin_note);

        return back()->with('success', __('messages.admin.suggestions.service_approved'));
    }

    public function reject(ProcessSuggestionRequest $request, ServiceSuggestion $serviceSuggestion, SuggestionAdminService $adminService)
    {
        if ($serviceSuggestion->suggestion_status !== SuggestionStatus::Pending) {
            return back()->with('error', __('messages.admin.suggestions.already_processed'));
        }

        $adminService->reject($serviceSuggestion, $request->admin_note);

        return back()->with('success', __('messages.admin.suggestions.rejected'));
    }
}
