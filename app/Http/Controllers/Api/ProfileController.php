<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['current_password']);

        $oldPicture = $user->profile_picture;
        $newPicture = null;
        $emailChanged = $user->email !== $validated['email'];

        try {
            DB::transaction(function () use ($request, $validated, $user, &$newPicture, $emailChanged): void {
                if ($request->hasFile('profile_picture')) {
                    $newPicture = $request->file('profile_picture')->store('profiles', 'public');
                    $validated['profile_picture'] = $newPicture;
                }

                if ($emailChanged) {
                    $user->email_verified_at = null;
                }

                if (! empty($validated['password'])) {
                    $validated['password_set_at'] = now();
                }

                $user->fill($validated);
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

        return response()->json($user->fresh());
    }
}
