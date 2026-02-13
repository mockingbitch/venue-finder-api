<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Seeder;

/**
 * Seeds 20 fake venues using VenueFactory.
 */
class VenueSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run(): void
    {
        Venue::factory()->count(20)->create();
    }
}
