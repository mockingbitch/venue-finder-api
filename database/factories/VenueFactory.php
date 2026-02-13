<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for Venue model. Generates fake name, description, address, lat/lng, price, capacity, rating, images.
 */
class VenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lat = fake()->latitude();
        $lng = fake()->longitude();

        return [
            'name' => fake()->company().' Hall',
            'description' => fake()->paragraphs(2, true),
            'address' => fake()->streetAddress().', '.fake()->city(),
            'lat' => $lat,
            'lng' => $lng,
            'price' => fake()->randomFloat(2, 500, 50000),
            'capacity' => fake()->numberBetween(50, 2000),
            'rating' => fake()->randomFloat(2, 0, 5),
            'images' => [
                'https://picsum.photos/800/600?random='.fake()->numberBetween(1, 1000),
                'https://picsum.photos/800/600?random='.fake()->numberBetween(1, 1000),
            ],
        ];
    }
}
