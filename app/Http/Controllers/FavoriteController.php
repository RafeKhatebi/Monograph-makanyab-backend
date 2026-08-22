<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Place;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        // get the authenticated user
        $user = $request->user();

        // get the user's favorites with pagination, including the category relationship
        $favorites = $user->favorites()
            ->with(['category:id,name,slug'])
            ->where('is_active', true)
            ->orderByDesc('favorites.created_at')
            ->paginate(min(max($request->integer('per_page', 15), 1), 50));

        return response()->json($favorites);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'place_id' => [
                'required',
                'uuid',
                Rule::exists('places', 'id')
                    ->whereNull('deleted_at')
                    ->where('is_active', true),
            ],
        ]);

        /** @var User $user */
        $user = $request->user();

        $exists = Favorite::where('user_id', $user->id)
            ->where('place_id', $validated['place_id'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => __('messages.api.favorite_exists')], 409);
        }

        try {
            $favorite = Favorite::create([
                'user_id' => $user->id,
                'place_id' => $validated['place_id'],
            ]);
        } catch (QueryException $exception) {
            if ($this->isUniqueConstraintViolation($exception)) {
                return response()->json(['message' => __('messages.api.favorite_exists')], 409);
            }

            throw $exception;
        }

        return response()->json($favorite, 201);
    }

    public function destroy(Request $request, Place $place): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $deleted = Favorite::where('user_id', $user->id)
            ->where('place_id', $place->id)
            ->delete();

        if (! $deleted) {
            return response()->json(['message' => __('messages.api.favorite_missing')], 404);
        }

        return response()->json(null, 204);
    }

    public function check(Request $request, Place $place): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $isFavorited = Favorite::where('user_id', $user->id)
            ->where('place_id', $place->id)
            ->exists();

        return response()->json(['is_favorited' => $isFavorited]);
    }

    private function isUniqueConstraintViolation(QueryException $exception): bool
    {
        return in_array($exception->errorInfo[0] ?? null, ['23000', '23505'], true);
    }
}
