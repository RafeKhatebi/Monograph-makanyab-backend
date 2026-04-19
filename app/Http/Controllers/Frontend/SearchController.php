<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Place::with(['category', 'media']);

        // keyword search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // category
        if ($request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // city
        if ($request->city) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        // status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // market level
        if ($request->market_level) {
            $query->where('market_level', $request->market_level);
        }

        // verified
        if ($request->verified) {
            $query->where('is_verified', 1);
        }

        $places = $query->latest()->paginate(10);

        $categories = PlaceCategory::all();

        return view('pages.search.index', compact('places', 'categories'));
    }
}