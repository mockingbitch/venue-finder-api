<?php

/**
 * Feature tests for public venue API: GET /api/venues, GET /api/venues/{id}, bounding box.
 */

use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

describe('GET /api/venues', function () {
    it('returns paginated venues', function () {
        Venue::factory()->count(3)->create();

        $response = $this->getJson('/api/venues');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'address', 'lat', 'lng', 'price', 'capacity', 'rating', 'images'],
                ],
                'links',
                'meta',
            ]);
        $this->assertCount(3, $response->json('data'));
    });

    it('filters venues by bounding box', function () {
        Venue::factory()->create(['lat' => 40.5, 'lng' => -74.0]);
        Venue::factory()->create(['lat' => 41.0, 'lng' => -73.5]);
        Venue::factory()->create(['lat' => 35.0, 'lng' => -120.0]); // outside box

        $response = $this->getJson('/api/venues?min_lat=40&max_lat=42&min_lng=-75&max_lng=-73');

        $response->assertOk();
        $data = $response->json('data');
        $this->assertCount(2, $data);
        $lats = array_column($data, 'lat');
        $this->assertTrue(min($lats) >= 40 && max($lats) <= 42);
    });

    it('returns empty when no venues in bounding box', function () {
        Venue::factory()->create(['lat' => 40.5, 'lng' => -74.0]);

        $response = $this->getJson('/api/venues?min_lat=50&max_lat=52&min_lng=10&max_lng=12');

        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    });
});

describe('GET /api/venues/{id}', function () {
    it('returns a single venue', function () {
        $venue = Venue::factory()->create(['name' => 'Grand Hall']);

        $response = $this->getJson('/api/venues/'.$venue->id);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Grand Hall')
            ->assertJsonPath('data.id', $venue->id);
    });

    it('returns 404 for missing venue', function () {
        $response = $this->getJson('/api/venues/99999');

        $response->assertNotFound();
    });
});
