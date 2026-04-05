<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PlaceCategory;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = PlaceCategory::withCount('places')
            ->with('parent:id,name')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.categories.index', compact('categories'));
    }
}
