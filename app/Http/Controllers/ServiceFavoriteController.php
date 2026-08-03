<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceFavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $request->user()->favoriteServices()
                ->with(['category:id,name,slug', 'media'])
                ->where('is_active', true)
                ->latest()
                ->paginate(min(max($request->integer('per_page', 15), 1), 50))
        );
    }

    public function store(Request $request, Service $service): JsonResponse
    {
        abort_if(! $service->is_active, 404);

        $attributes = [
            'user_id' => $request->user()->id,
            'service_id' => $service->id,
        ];

        if (Favorite::where($attributes)->exists()) {
            return response()->json(['message' => 'Already in favorites.'], 409);
        }

        $favorite = Favorite::create($attributes);

        return response()->json($favorite, 201);
    }

    public function destroy(Request $request, Service $service): JsonResponse
    {
        $deleted = Favorite::where('user_id', $request->user()->id)
            ->where('service_id', $service->id)
            ->delete();

        return $deleted
            ? response()->json(null, 204)
            : response()->json(['message' => 'Not found in favorites.'], 404);
    }

    public function check(Request $request, Service $service): JsonResponse
    {
        return response()->json([
            'is_favorited' => Favorite::where('user_id', $request->user()->id)
                ->where('service_id', $service->id)
                ->exists(),
        ]);
    }
}
