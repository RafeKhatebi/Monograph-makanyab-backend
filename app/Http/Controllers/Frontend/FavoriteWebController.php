<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class FavoriteWebController extends Controller
{
    public function index()
    {
        $favorites = FacadesAuth::user()->favorites()
            ->with(['category:id,name,slug,color_code', 'media'])
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('pages.favorites.index', compact('favorites'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['place_id' => 'required|exists:places,id']);

        $existing = Favorite::where('user_id', FacadesAuth::id())
            ->where('place_id', $request->place_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFavorited = false;
        } else {
            Favorite::create(['user_id' => FacadesAuth::id(), 'place_id' => $request->place_id]);
            $isFavorited = true;
        }

        if ($request->expectsJson()) {
            return response()->json(['is_favorited' => $isFavorited]);
        }

        return back();
    }
}
