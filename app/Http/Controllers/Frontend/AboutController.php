<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Review;
use App\Models\User;

class AboutController extends Controller
{
    public function index()
    {
        $stats = [
            'places'     => Place::where('is_active', true)->count(),
            'categories' => PlaceCategory::where('is_active', true)->count(),
            'reviews'    => Review::where('is_approved', true)->count(),
            'users'      => User::where('is_active', true)->count(),
        ];

        return view('pages.about.index', compact('stats'));
    }
}
