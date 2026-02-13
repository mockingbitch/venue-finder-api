<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Factory for User model. States: admin(), unverified().
 */
class UserFactory extends Factory
{
    /** @var string|null Cached default password for all factory users. */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user has admin role.
     * Role is set in afterCreating because it is not mass assignable.
     *
     * @return static
     */
    public function admin(): static
    {
        return $this->afterCreating(function (User $user): void {
            $user->role = Role::ADMIN;
            $user->save();
        });
    }

    /**
     * Indicate that the user's email address is not verified.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => ['email_verified_at' => null]);
    }
}
