<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FavoriteWebController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()
            ->with(['category:id,name,slug,color_code', 'media'])
            ->where('is_active', true)
            ->orderByDesc('favorites.created_at')
            ->paginate(12);
        $favoriteServices = Auth::user()->favoriteServices()
            ->with(['category:id,name,slug', 'media'])
            ->where('is_active', true)
            ->orderByDesc('favorites.created_at')
            ->paginate(12, ['*'], 'services_page');
        $favoritePosts = Auth::user()->favoritePosts()
            ->published()
            ->orderByDesc('favorites.created_at')
            ->paginate(12, ['*'], 'posts_page');

        return view('pages.favorites.index', compact('favorites', 'favoriteServices', 'favoritePosts'));
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'place_id' => [
                'nullable',
                Rule::exists('places', 'id')
                    ->whereNull('deleted_at')
                    ->where('is_active', true),
            ],
            'service_id' => [
                'nullable',
                Rule::exists('services', 'id')
                    ->whereNull('deleted_at')
                    ->where('is_active', true),
            ],
            'post_id' => [
                'nullable',
                Rule::exists('posts', 'id')
                    ->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now()),
            ],
        ]);

        $targets = collect(['place_id', 'service_id', 'post_id'])
            ->filter(fn ($field) => filled($validated[$field] ?? null))
            ->values();

        if ($targets->count() !== 1) {
            throw ValidationException::withMessages([
                'favorite' => __('favorites.invalid_target'),
            ]);
        }

        $targetField = $targets->first();
        $targetValue = $validated[$targetField];

        $existing = Favorite::where('user_id', Auth::id())
            ->where($targetField, $targetValue)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFavorited = false;
        } else {
            Favorite::create(['user_id' => Auth::id(), $targetField => $targetValue]);
            $isFavorited = true;
        }

        if ($request->expectsJson()) {
            return response()->json(['is_favorited' => $isFavorited]);
        }

        return back()->with(
            'success',
            $isFavorited ? __('favorites.added') : __('favorites.removed')
        );
    }
}
