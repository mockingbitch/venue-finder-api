<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * User model for authentication and authorization.
 * Implements JWTSubject for tymon/jwt-auth. Roles: admin, user.
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /** @var string Admin role (full CRUD venues). */
    public const ROLE_ADMIN = 'admin';

    /** @var string Default user role (view venues only). */
    public const ROLE_USER = 'user';

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array of custom claims to add to the JWT payload.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'role' => $this->role,
        ];
    }

    /**
     * Check if the user has admin role.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if the user has user role.
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }
}
