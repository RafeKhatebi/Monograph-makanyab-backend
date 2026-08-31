<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\UpdateUserProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $favorites = $user->favorites()
            ->with(['category:id,name,slug,color_code', 'media'])
            ->where('is_active', true)
            ->latest()
            ->limit(12)
            ->get();
        $favoriteServices = $user->favoriteServices()
            ->with(['category:id,name,slug', 'media'])
            ->where('is_active', true)
            ->latest()
            ->limit(12)
            ->get();

        $reviews = $user->reviews()
            ->with(['place:id,name,slug', 'service:id,name,slug'])
            ->latest()
            ->limit(20)
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
        $newPicture = null;
        $emailChanged = $user->email !== $payload['email'];

        try {
            DB::transaction(function () use ($request, $payload, $user, &$newPicture, $emailChanged): void {
                if ($request->hasFile('profile_picture')) {
                    $newPicture = $request->file('profile_picture')->store('profiles', 'public');
                    $payload['profile_picture'] = $newPicture;
                }

                if ($emailChanged) {
                    $user->email_verified_at = null;
                }

                if ($request->filled('password')) {
                    $payload['password_set_at'] = now();
                }

                $user->fill($payload);
                if ($emailChanged) {
                    $user->email_verified_at = null;
                }
                $user->save();
            });
        } catch (Throwable $exception) {
            if ($newPicture) {
                Storage::disk('public')->delete($newPicture);
            }

            throw $exception;
        }

        if ($newPicture && $oldPicture) {
            Storage::disk('public')->delete($oldPicture);
        }

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', __('messages.profile_updated'));
    }
}
