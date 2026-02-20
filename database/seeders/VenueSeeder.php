<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds ~50 fake venues using VenueFactory.
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
        DB::table('venues')->truncate();
        Venue::factory()->count(50)->create();
    }
}
