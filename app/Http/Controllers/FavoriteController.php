<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $favorites = $user->favorites()
            ->with(['category:id,name,slug'])
            ->where('is_active', true)
            ->orderByDesc('favorites.created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($favorites);
    }

    public function store(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $exists = Favorite::where('user_id', $user->id)
            ->where('place_id', $request->place_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already in favorites.'], 409);
        }

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'place_id' => $request->place_id,
        ]);

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
            return response()->json(['message' => 'Not found in favorites.'], 404);
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
}
