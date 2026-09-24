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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SuggestionHubController extends Controller
{
    public function create(): View
    {
        return $this->formView();
    }

    public function edit(string $type, string $submission): View
    {
        $record = $this->resolveUserSubmission($type, $submission);

        abort_if($this->isLocked($type, $record), 403);

        return $this->formView($type, $record);
    }

    private function formView(?string $type = null, mixed $record = null): View
    {
        if ($record && $type !== 'post') {
            $record->loadMissing('media');
        }

        return view('pages.suggestions.index', [
            'placeCategories' => $this->categoryOptions(PlaceCategory::class),
            'serviceCategories' => $this->categoryOptions(ServiceCategory::class),
            'editingType' => $type,
            'editingSubmission' => $record,
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
                'image' => $request->hasFile('image')
                    ? $request->file('image')->store('post-submissions', 'public')
                    : null,
                'excerpt' => $data['excerpt'] ?? null,
                'content' => $data['content'] ?? null,
                'extra_information' => $data['extra_information'] ?? null,
                'submission_status' => $isReviewSubmission
                    ? SuggestionStatus::UnderReview->value
                    : SuggestionStatus::Draft->value,
                'is_published' => false,
                'published_at' => null,
            ]);

            return redirect()
                ->route('add.create', ['type' => 'post'])
                ->with('success', __($isReviewSubmission ? 'messages.post_suggestion_sent' : 'messages.post_suggestion_saved'))
                ->with('submission_id', $post->id);
        }

        $modelClass = $type === 'service' ? ServiceSuggestion::class : PlaceSuggestion::class;
        $categoryField = $type === 'service' ? 'service_category_id' : 'place_category_id';
        $payload = Arr::except($data, ['type', 'submit_action', 'images', 'image', 'cover_image_index', 'title', 'content', 'excerpt']);

        $suggestion = DB::transaction(function () use ($modelClass, $payload, $request, $isReviewSubmission, $categoryField, $mediaUploadService, $type) {
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
                $categoryField => $payload[$categoryField] ?? null,
            ]));

            $mediaUploadService->attachImages(
                $suggestion,
                $request->file('images', []),
                "{$type}-suggestions",
                $request->filled('cover_image_index') ? $request->integer('cover_image_index') : null
            );

            return $suggestion;
        });

        return redirect()
            ->route('add.create', ['type' => $type])
            ->with('success', __($isReviewSubmission ? 'messages.suggestion_sent_for_review' : 'messages.suggestion_draft_saved'))
            ->with('submission_id', $suggestion->id);
    }

    public function update(
        StoreUserSubmissionRequest $request,
        string $type,
        string $submission,
        MediaUploadService $mediaUploadService,
        SlugService $slugService
    ): RedirectResponse {
        $record = $this->resolveUserSubmission($type, $submission);

        abort_if($this->isLocked($type, $record), 403);

        $data = $request->validated();
        abort_unless($data['type'] === $type, 422);

        $isReviewSubmission = $data['submit_action'] === 'send_review';

        if ($type === 'post') {
            $payload = [
                'title' => $data['title'],
                'excerpt' => $data['excerpt'] ?? null,
                'content' => $data['content'] ?? null,
                'submission_status' => $isReviewSubmission
                    ? SuggestionStatus::UnderReview->value
                    : SuggestionStatus::Draft->value,
                'is_published' => false,
                'published_at' => null,
            ];

            if ($request->hasFile('image')) {
                if ($record->image) {
                    Storage::disk('public')->delete($record->image);
                }
                $payload['image'] = $request->file('image')->store('post-submissions', 'public');
            }

            if ($record->title !== $data['title']) {
                $payload['slug'] = $slugService->createUniqueSlug(Post::class, $data['title'], (string) $record->getKey());
            }

            $record->update($payload);

            return redirect()
                ->route('add.edit', ['type' => 'post', 'submission' => $record->getKey()])
                ->with('success', __($isReviewSubmission ? 'messages.post_suggestion_resubmitted' : 'messages.post_suggestion_updated'));
        }

        $categoryField = $type === 'service' ? 'service_category_id' : 'place_category_id';
        $payload = Arr::except($data, ['type', 'submit_action', 'images', 'image', 'cover_image_index', 'title', 'content', 'excerpt']);
        $payload = array_merge($payload, [
            'country' => $payload['country'] ?? 'Afghanistan',
            'status' => $payload['status'] ?? PlaceStatus::Open->value,
            'price_level' => $payload['price_level'] ?? PriceLevel::Medium->value,
            'suggestion_status' => $isReviewSubmission
                ? SuggestionStatus::Pending->value
                : SuggestionStatus::Draft->value,
            $categoryField => $payload[$categoryField] ?? null,
            'submitted_by_name' => $request->user()->name,
            'submitted_by_email' => $request->user()->email,
        ]);

        DB::transaction(function () use ($record, $payload, $request, $mediaUploadService, $type): void {
            $record->update($payload);
            $mediaUploadService->attachImages(
                $record,
                $request->file('images', []),
                "{$type}-suggestions",
                $request->filled('cover_image_index') ? $request->integer('cover_image_index') : null
            );
        });

        return redirect()
            ->route('add.edit', ['type' => $type, 'submission' => $record->getKey()])
            ->with('success', __($isReviewSubmission ? 'messages.suggestion_resubmitted_for_review' : 'messages.suggestion_updated'));
    }

    private function resolveUserSubmission(string $type, string $submission): PlaceSuggestion|ServiceSuggestion|Post
    {
        $modelClass = match ($type) {
            'place' => PlaceSuggestion::class,
            'service' => ServiceSuggestion::class,
            'post' => Post::class,
            default => abort(404),
        };

        return $modelClass::query()
            ->whereKey($submission)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    private function categoryOptions(string $modelClass): array
    {
        return $modelClass::query()
            ->with('parent:id,name')
            ->active()
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn ($category) => [
                $category->id => $category->parent
                    ? $category->parent->name.' / '.$category->name
                    : $category->name,
            ])
            ->all();
    }

    private function isLocked(string $type, PlaceSuggestion|ServiceSuggestion|Post $record): bool
    {
        if ($type === 'post') {
            return (bool) $record->is_published;
        }

        $status = $record->suggestion_status instanceof SuggestionStatus
            ? $record->suggestion_status->value
            : (string) $record->suggestion_status;

        return in_array($status, [SuggestionStatus::Approved->value, SuggestionStatus::Published->value], true);
    }
}
