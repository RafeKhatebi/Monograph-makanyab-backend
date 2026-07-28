<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\User;

class PlacePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Place $place): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    public function update(User $user, Place $place): bool
    {
        return $user->isAdmin() || $place->user_id === $user->id;
    }

    public function delete(User $user, Place $place): bool
    {
        return $user->isAdmin() || $place->user_id === $user->id;
    }

    public function restore(User $user, Place $place): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Place $place): bool
    {
        return $user->isAdmin();
    }
}
