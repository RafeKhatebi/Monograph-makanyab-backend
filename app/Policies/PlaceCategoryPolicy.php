<?php

namespace App\Policies;

use App\Models\PlaceCategory;
use App\Models\User;

class PlaceCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PlaceCategory $placeCategory): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, PlaceCategory $placeCategory): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, PlaceCategory $placeCategory): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, PlaceCategory $placeCategory): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, PlaceCategory $placeCategory): bool
    {
        return $user->isAdmin();
    }
}
