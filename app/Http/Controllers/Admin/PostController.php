<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SuggestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct(private readonly SlugService $slugService) {}

    public function index(Request $request)
    {
        return $this->listing($request, 'all');
    }

    public function pending(Request $request)
    {
        return $this->listing($request, 'pending');
    }

    public function approved(Request $request)
    {
        return $this->listing($request, 'approved');
    }

    public function rejected(Request $request)
    {
        return $this->listing($request, 'rejected');
    }

    public function changesRequested(Request $request)
    {
        return $this->listing($request, 'changes_requested');
    }

    private function listing(Request $request, string $section)
    {
        $posts = Post::with('user')
            ->when($request->search, fn ($q, $v) => $q->where(function ($query) use ($v) {
                $query->where('title', 'like', "%{$v}%")
                    ->orWhere('excerpt', 'like', "%{$v}%");
            }))
            ->when($section === 'pending', fn ($q) => $q->where('submission_status', SuggestionStatus::UnderReview))
            ->when($section === 'approved', fn ($q) => $q->where('is_published', true))
            ->when($section === 'rejected', fn ($q) => $q->where('submission_status', SuggestionStatus::Rejected))
            ->when($section === 'changes_requested', fn ($q) => $q->where('submission_status', SuggestionStatus::ChangesRequested))
            ->when($section === 'all' && $request->filled('is_published'), fn ($q) => $q->where('is_published', $request->boolean('is_published')))
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'section'));
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        if (array_key_exists('title', $data)) {
            $data['slug'] = $this->slugService->createUniqueSlug(Post::class, $data['title'], (string) $post->getKey());
        }

        $data['is_published'] = $request->boolean('is_published');

        if ($data['is_published'] && ! $post->published_at) {
            $data['published_at'] = now();
        }

        if (! $data['is_published']) {
            $data['published_at'] = null;
        }

        if ($data['is_published']) {
            $data['submission_status'] = SuggestionStatus::Published->value;
        } elseif ($post->is_published) {
            $data['submission_status'] = SuggestionStatus::Draft->value;
        }

        if ($request->hasFile('image')) {

            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        $destination = match (true) {
            $post->is_published => 'approved',
            $post->submission_status === SuggestionStatus::UnderReview => 'pending',
            $post->submission_status === SuggestionStatus::Rejected => 'rejected',
            $post->submission_status === SuggestionStatus::ChangesRequested => 'changes_requested',
            default => 'index',
        };

        return redirect()
            ->route('admin.posts.'.$destination)
            ->with('success', __('messages.admin.posts.updated'));
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', __('messages.admin.posts.deleted'));
    }

    public function approve(Post $post)
    {
        if ($post->submission_status !== SuggestionStatus::UnderReview) {
            return back()->with('error', __('messages.admin.suggestions.already_processed'));
        }

        $post->update([
            'is_published' => true,
            'published_at' => $post->published_at ?? now(),
            'submission_status' => SuggestionStatus::Published->value,
            'admin_note' => null,
        ]);

        return back()->with('success', __('messages.admin.posts.updated'));
    }

    public function reject(Request $request, Post $post)
    {
        $data = $request->validate(['admin_note' => ['nullable', 'string', 'max:2000']]);

        if ($post->submission_status !== SuggestionStatus::UnderReview) {
            return back()->with('error', __('messages.admin.suggestions.already_processed'));
        }

        $post->update([
            'is_published' => false,
            'published_at' => null,
            'submission_status' => SuggestionStatus::Rejected->value,
            'admin_note' => $data['admin_note'] ?? null,
        ]);

        return back()->with('success', __('messages.admin.suggestions.rejected'));
    }

    public function requestChanges(Request $request, Post $post)
    {
        if ($post->submission_status !== SuggestionStatus::UnderReview) {
            return back()->with('error', __('messages.admin.suggestions.already_processed'));
        }

        $data = $request->validate(['admin_note' => ['required', 'string', 'max:2000']]);

        $post->update([
            'is_published' => false,
            'published_at' => null,
            'submission_status' => SuggestionStatus::ChangesRequested->value,
            'admin_note' => $data['admin_note'],
        ]);

        return back()->with('success', __('messages.admin.suggestions.changes_requested'));
    }
}
