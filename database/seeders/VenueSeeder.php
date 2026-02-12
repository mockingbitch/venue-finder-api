<?php

namespace Database\Seeders;

use App\Models\Space;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        $venues = [
            ['name' => 'Comfortably Large-scale Function Venue', 'category' => 'Function Venue', 'suburb' => 'Sydney CBD', 'lat' => -33.8688, 'lng' => 151.2093, 'capacity' => 100, 'area_sqm' => 250, 'rating' => 5, 'reviews_count' => 121, 'price_level' => 5, 'has_offer' => false],
            ['name' => 'Historic Jubilee Room NSW', 'category' => 'Ballroom', 'suburb' => 'Sydney CBD', 'lat' => -33.8712, 'lng' => 151.2054, 'capacity' => 90, 'area_sqm' => 200, 'rating' => 4.6, 'reviews_count' => 204, 'price_level' => 4, 'has_offer' => false],
            ['name' => 'Lumiere on Thirty Five', 'category' => 'Hotel', 'suburb' => 'Sydney CBD', 'lat' => -33.8675, 'lng' => 151.2078, 'capacity' => 120, 'area_sqm' => 300, 'rating' => 4.4, 'reviews_count' => 5146, 'price_level' => 5, 'has_offer' => true],
            ['name' => 'Ten Stories Restaurant', 'category' => 'Restaurant', 'suburb' => 'Sydney CBD', 'lat' => -33.8695, 'lng' => 151.2101, 'capacity' => 64, 'area_sqm' => 80, 'rating' => 4.2, 'reviews_count' => 892, 'price_level' => 3, 'has_offer' => false],
        ];

        foreach ($venues as $v) {
            $venue = Venue::create([
                'name' => $v['name'],
                'slug' => Str::slug($v['name']),
                'category' => $v['category'],
                'suburb' => $v['suburb'],
                'lat' => $v['lat'],
                'lng' => $v['lng'],
                'capacity' => $v['capacity'],
                'area_sqm' => $v['area_sqm'],
                'rating' => $v['rating'],
                'reviews_count' => $v['reviews_count'],
                'price_level' => $v['price_level'],
                'has_offer' => $v['has_offer'],
                'description' => 'Sample description for ' . $v['name'],
                'image_url' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400',
            ]);
            Space::create(['venue_id' => $venue->id, 'name' => 'Main Space', 'capacity' => $v['capacity'], 'area_sqm' => $v['area_sqm']]);
        }
    }
}
