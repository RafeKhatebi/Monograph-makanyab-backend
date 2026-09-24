<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\SuggestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\UpdateUserProfileRequest;
use App\Models\PlaceSuggestion;
use App\Models\Post;
use App\Models\ServiceSuggestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $favorites = $user->favorites()
            ->with(['category:id,name,slug,color_code', 'media'])
            ->where('is_active', true)
            ->latest()
            ->limit(12)
            ->get();
        $favoriteServices = $user->favoriteServices()
            ->with(['category:id,name,slug', 'media'])
            ->where('is_active', true)
            ->latest()
            ->limit(12)
            ->get();
        $favoritePosts = $user->favoritePosts()
            ->published()
            ->latest()
            ->limit(12)
            ->get();

        $reviews = $user->reviews()
            ->with(['place:id,name,slug', 'service:id,name,slug'])
            ->latest()
            ->limit(20)
            ->get();

        $submissions = $this->userSubmissions($user->id);

        return view('pages.profile.index', compact('favorites', 'favoriteServices', 'favoritePosts', 'reviews', 'submissions'));
    }

    public function update(UpdateUserProfileRequest $request)
    {
        $validated = $request->validated();

        $payload = $request->only('name', 'lastname', 'username', 'email', 'phone', 'bio');

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($request->string('password')->toString());
        }

        $user = Auth::user();
        $oldPicture = $user->profile_picture;
        $newPicture = null;
        $emailChanged = $user->email !== $payload['email'];

        try {
            DB::transaction(function () use ($request, $payload, $user, &$newPicture, $emailChanged): void {
                if ($request->hasFile('profile_picture')) {
                    $newPicture = $request->file('profile_picture')->store('profiles', 'public');
                    $payload['profile_picture'] = $newPicture;
                }

                if ($emailChanged) {
                    $user->email_verified_at = null;
                }

                if ($request->filled('password')) {
                    $payload['password_set_at'] = now();
                }

                $user->fill($payload);
                if ($emailChanged) {
                    $user->email_verified_at = null;
                }
                $user->save();
            });
        } catch (Throwable $exception) {
            if ($newPicture) {
                Storage::disk('public')->delete($newPicture);
            }

            throw $exception;
        }

        if ($newPicture && $oldPicture) {
            Storage::disk('public')->delete($oldPicture);
        }

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', __('messages.profile_updated'));
    }

    private function userSubmissions(string $userId)
    {
        $places = PlaceSuggestion::with('category:id,name')
            ->where('user_id', $userId)
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn (PlaceSuggestion $suggestion) => [
                'type' => __('suggestions.types.place'),
                'title' => $suggestion->name,
                'category' => $suggestion->category?->name,
                'status' => $suggestion->suggestion_status?->label() ?? __('suggestions.status.draft'),
                'date' => $suggestion->created_at,
                'edit_url' => route('add.edit', ['type' => 'place', 'submission' => $suggestion->getKey()]),
                'can_edit' => ! in_array($suggestion->suggestion_status?->value, [
                    SuggestionStatus::Approved->value,
                    SuggestionStatus::Published->value,
                ], true),
            ]);

        $services = ServiceSuggestion::with('category:id,name')
            ->where('user_id', $userId)
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn (ServiceSuggestion $suggestion) => [
                'type' => __('suggestions.types.service'),
                'title' => $suggestion->name,
                'category' => $suggestion->category?->name,
                'status' => $suggestion->suggestion_status?->label() ?? __('suggestions.status.draft'),
                'date' => $suggestion->created_at,
                'edit_url' => route('add.edit', ['type' => 'service', 'submission' => $suggestion->getKey()]),
                'can_edit' => ! in_array($suggestion->suggestion_status?->value, [
                    SuggestionStatus::Approved->value,
                    SuggestionStatus::Published->value,
                ], true),
            ]);

        $posts = Post::where('user_id', $userId)
            ->latest()
            ->limit(20)
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
                    'edit_url' => route('add.edit', ['type' => 'post', 'submission' => $post->getKey()]),
                    'can_edit' => ! $post->is_published,
                ];
            });

        return collect()
            ->concat($places)
            ->concat($services)
            ->concat($posts)
            ->sortByDesc('date')
            ->take(30)
            ->values();
    }
}
