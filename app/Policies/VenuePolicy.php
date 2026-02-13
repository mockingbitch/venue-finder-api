<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Venue;

/**
 * Authorization policy for Venue model.
 * View/list: anyone. Create/update/delete: admin only.
 */
class VenuePolicy
{
    /**
     * Determine whether the user can view any venues (list).
     *
     * @param  User|null  $user
     * @return bool
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the venue.
     *
     * @param  User|null  $user
     * @param  Venue  $venue
     * @return bool
     */
    public function view(?User $user, Venue $venue): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create venues.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the venue.
     *
     * @param  User  $user
     * @param  Venue  $venue
     * @return bool
     */
    public function update(User $user, Venue $venue): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the venue.
     *
     * @param  User  $user
     * @param  Venue  $venue
     * @return bool
     */
    public function delete(User $user, Venue $venue): bool
    {
        return $user->isAdmin();
    }
}
