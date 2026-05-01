<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpeningHourRequest;
use App\Models\OpeningHour;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpeningHourController extends Controller
{
    public function index(Place $place): JsonResponse
    {
        $hours = $place->openingHours()
            ->orderBy('day_of_week')
            ->get();

        return response()->json($hours);
    }

    public function store(StoreOpeningHourRequest $request, Place $place): JsonResponse
    {
        $hour = $place->openingHours()->create($request->validated());

        return response()->json($hour, 201);
    }

    public function update(Request $request, Place $place, OpeningHour $openingHour): JsonResponse
    {
        if ($openingHour->place_id !== $place->id) {
            return response()->json(['message' => 'Not found for this place.'], 404);
        }

        /** @var User $user */
        $user = $request->user();

        if ($user->id !== $place->user_id && ! $user->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $openingHour->update($request->only(['open_time', 'close_time', 'is_closed']));

        return response()->json($openingHour);
    }

    public function destroy(Request $request, Place $place, OpeningHour $openingHour): JsonResponse
    {
        if ($openingHour->place_id !== $place->id) {
            return response()->json(['message' => 'Not found for this place.'], 404);
        }

        /** @var User $user */
        $user = $request->user();

        if ($user->id !== $place->user_id && ! $user->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $openingHour->delete();

        return response()->json(null, 204);
    }
}
