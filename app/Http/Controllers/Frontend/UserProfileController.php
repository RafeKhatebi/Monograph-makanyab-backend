<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

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

    public function update(UpdateUserProfileRequest $request)
    {
        $validated = $request->validated();

        $payload = $request->only('name', 'lastname', 'username', 'email', 'phone', 'bio');

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($request->string('password')->toString());
        }

        Auth::user()->update($payload);

        return back()->with('success', 'Profile updated successfully.');
    }
}
