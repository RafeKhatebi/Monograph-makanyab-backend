<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
use App\Enums\SuggestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserSubmissionRequest;
use App\Models\PlaceCategory;
use App\Models\PlaceSuggestion;
use App\Models\Post;
use App\Models\ServiceCategory;
use App\Models\ServiceSuggestion;
use App\Services\MediaUploadService;
use App\Services\SlugService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class SuggestionHubController extends Controller
{
    public function create(): View
    {
        return view('pages.suggestions.index', [
            'placeCategories' => PlaceCategory::active()->orderBy('name')->pluck('name', 'id'),
            'serviceCategories' => ServiceCategory::active()->orderBy('name')->pluck('name', 'id'),
            'submissions' => $this->userSubmissions(),
        ]);
    }

    public function store(
        StoreUserSubmissionRequest $request,
        MediaUploadService $mediaUploadService,
        SlugService $slugService
    ): RedirectResponse {
        $data = $request->validated();
        $type = $data['type'];
        $isReviewSubmission = $data['submit_action'] === 'send_review';

        if ($type === 'post') {
            $post = Post::create([
                'user_id' => $request->user()->id,
                'title' => $data['title'],
                'slug' => $slugService->createUniqueSlug(Post::class, $data['title']),
                'image' => $request->file('image')->store('post-submissions', 'public'),
                'excerpt' => $data['excerpt'] ?? null,
                'content' => $data['content'],
                'extra_information' => $data['extra_information'] ?? null,
                'submission_status' => $isReviewSubmission
                    ? SuggestionStatus::UnderReview->value
                    : SuggestionStatus::Draft->value,
                'is_published' => false,
                'published_at' => null,
            ]);

            return redirect()
                ->route('suggest.create')
                ->with('success', __($isReviewSubmission ? 'messages.post_suggestion_sent' : 'messages.post_suggestion_saved'))
                ->with('submission_id', $post->id);
        }

        $modelClass = $type === 'service' ? ServiceSuggestion::class : PlaceSuggestion::class;
        $categoryField = $type === 'service' ? 'service_category_id' : 'place_category_id';
        $payload = Arr::except($data, ['type', 'submit_action', 'images', 'image', 'title', 'content', 'excerpt']);

        $suggestion = $modelClass::create(array_merge($payload, [
            'user_id' => $request->user()->id,
            'submitted_by_name' => $request->user()->name,
            'submitted_by_email' => $request->user()->email,
            'country' => $payload['country'] ?? 'Afghanistan',
            'status' => $payload['status'] ?? PlaceStatus::Open->value,
            'price_level' => $payload['price_level'] ?? PriceLevel::Medium->value,
            'suggestion_status' => $isReviewSubmission
                ? SuggestionStatus::Pending->value
                : SuggestionStatus::Draft->value,
            $categoryField => $payload[$categoryField],
        ]));

        $mediaUploadService->attachImages($suggestion, $request->file('images', []), "{$type}-suggestions");

        return redirect()
            ->route('suggest.create')
            ->with('success', __($isReviewSubmission ? 'messages.suggestion_sent_for_review' : 'messages.suggestion_draft_saved'))
            ->with('submission_id', $suggestion->id);
    }

    private function userSubmissions()
    {
        $userId = auth()->id();

        $places = PlaceSuggestion::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (PlaceSuggestion $suggestion) => [
                'type' => __('suggestions.types.place'),
                'title' => $suggestion->name,
                'category' => $suggestion->category?->name,
                'status' => $suggestion->suggestion_status?->label() ?? __('suggestions.status.draft'),
                'date' => $suggestion->created_at,
            ]);

        $services = ServiceSuggestion::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (ServiceSuggestion $suggestion) => [
                'type' => __('suggestions.types.service'),
                'title' => $suggestion->name,
                'category' => $suggestion->category?->name,
                'status' => $suggestion->suggestion_status?->label() ?? __('suggestions.status.draft'),
                'date' => $suggestion->created_at,
            ]);

        $posts = Post::where('user_id', $userId)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function (Post $post) {
                $status = $post->is_published
                    ? SuggestionStatus::Published
                    : ($post->submission_status ?? SuggestionStatus::Draft);

                return [
                    'type' => __('suggestions.types.post'),
                    'title' => $post->title,
                    'category' => __('content.posts.default_category'),
                    'status' => $status instanceof SuggestionStatus
                        ? $status->label()
                        : __('suggestions.status.'.$status),
                    'date' => $post->created_at,
                ];
            });

        return $places->merge($services)->merge($posts)->sortByDesc('date')->take(20)->values();
    }
}
