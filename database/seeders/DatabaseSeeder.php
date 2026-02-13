<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Main database seeder. Runs UserSeeder and VenueSeeder.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            VenueSeeder::class,
        ]);
    }
}
