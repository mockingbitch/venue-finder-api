<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds admin and demo user (admin@venuefinder.test, user@venuefinder.test).
 */
class UserSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@venuefinder.test',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'user@venuefinder.test',
            'password' => Hash::make('password'),
        ]);
    }
}
