<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\UpdateUserProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        $favoriteServices = $user->favoriteServices()
            ->with(['category:id,name,slug', 'media'])
            ->where('is_active', true)
            ->latest()
            ->get();

        $reviews = $user->reviews()
            ->with(['place:id,name,slug', 'service:id,name,slug'])
            ->latest()
            ->get();

        return view('pages.profile.index', compact('favorites', 'favoriteServices', 'reviews'));
    }

    public function update(UpdateUserProfileRequest $request)
    {
        $validated = $request->validated();

        $payload = $request->only('name', 'lastname', 'username', 'email', 'phone', 'bio');

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($request->string('password')->toString());
        }

        $user = Auth::user();
        $oldPicture = $user->profile_picture;

        if ($request->hasFile('profile_picture')) {
            $payload['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user->update($payload);

        if (isset($payload['profile_picture']) && $oldPicture) {
            Storage::disk('public')->delete($oldPicture);
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
