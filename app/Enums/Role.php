<?php

namespace App\Enums;

/**
 * User role backed enum for authorization.
 * Only ADMIN can access /api/admin/* routes.
 */
enum Role: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    /**
     * Whether this role can access admin routes.
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
}
