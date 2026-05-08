<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlaceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $places = Place::query()
            ->with(['category:id,name,slug', 'user:id,name'])
            ->active()
            ->when($request->city, fn ($q, $v) => $q->where('city', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->price_level, fn ($q, $v) => $q->where('price_level', $v))
            ->filterVerified($request->boolean('verified'))
            ->filterCategorySlug($request->query('category'))
            ->filterSearch($request->query('search'))
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($places);
    }

    public function store(StorePlaceRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $place = Place::create(array_merge(
            $request->validated(),
            [
                'user_id' => $user->id,
                'slug' => $request->validated('slug') ?? Str::slug($request->validated('name')),
            ]
        ));

        $place->load(['category:id,name,slug', 'user:id,name']);

        return response()->json($place, 201);
    }

    public function show(Place $place): JsonResponse
    {
        $place->load([
            'category:id,name,slug',
            'user:id,name',
            'openingHours',
            'media',
            'reviews' => fn ($q) => $q->where('is_approved', true)
                ->with('user:id,name')
                ->latest()
                ->limit(10),
        ]);

        return response()->json($place);
    }

    public function update(UpdatePlaceRequest $request, Place $place): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->id !== $place->user_id && ! $user->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $place->update($request->validated());
        $place->load(['category:id,name,slug', 'user:id,name']);

        return response()->json($place);
    }

    public function destroy(Request $request, Place $place): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->id !== $place->user_id && ! $user->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $place->delete();

        return response()->json(null, 204);
    }
}
