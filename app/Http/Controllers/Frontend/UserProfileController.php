<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $favorites = $user->favorites()
            ->with(['category:id,name,slug,color_code', 'media'])
            ->where('is_active', true)
            ->latest()
            ->get();

        $reviews = $user->reviews()
            ->with(['place:id,name,slug'])
            ->latest()
            ->get();

        return view('pages.profile.index', compact('favorites', 'reviews'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email'    => 'required|email|unique:users,email,' . auth()->id(),
            'phone'    => 'nullable|string|max:20',
            'bio'      => 'nullable|string|max:500',
        ]);

        auth()->user()->update($request->only('name', 'lastname', 'email', 'phone', 'bio'));

        return back()->with('success', 'Profile updated successfully.');
    }
}
