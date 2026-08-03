<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FavoriteWebController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()
            ->with(['category:id,name,slug,color_code', 'media'])
            ->where('is_active', true)
            ->latest()
            ->paginate(12);
        $favoriteServices = Auth::user()->favoriteServices()
            ->with(['category:id,name,slug', 'media'])
            ->where('is_active', true)
            ->latest()
            ->paginate(12, ['*'], 'services_page');

        return view('pages.favorites.index', compact('favorites', 'favoriteServices'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'place_id' => [
                'required',
                Rule::exists('places', 'id')
                    ->whereNull('deleted_at')
                    ->where('is_active', true),
            ],
        ]);

        $existing = Favorite::where('user_id', Auth::id())
            ->where('place_id', $request->place_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFavorited = false;
        } else {
            Favorite::create(['user_id' => Auth::id(), 'place_id' => $request->place_id]);
            $isFavorited = true;
        }

        if ($request->expectsJson()) {
            return response()->json(['is_favorited' => $isFavorited]);
        }

        return back()->with(
            'success',
            $isFavorited ? 'Added to favorites.' : 'Removed from favorites.'
        );
    }
}
