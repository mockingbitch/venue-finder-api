<?php

/**
 * Feature tests for admin venue API: POST/PUT/DELETE /api/admin/venues (JWT + admin role).
 */

use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

/**
 * Generate JWT token for the given user (for test requests).
 *
 * @param  User  $user
 * @return string
 */
function jwtToken(User $user): string
{
    return auth('api')->login($user);
}

describe('POST /api/admin/venues', function () {
    it('admin can create venue', function () {
        $payload = [
            'name' => 'New Venue',
            'description' => 'A great place',
            'address' => '123 Main St',
            'lat' => 40.7128,
            'lng' => -74.0060,
            'price' => 5000,
            'capacity' => 200,
            'rating' => 4.5,
            'images' => ['https://example.com/1.jpg'],
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.jwtToken($this->admin))
            ->postJson('/api/admin/venues', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'New Venue')
            ->assertJsonPath('data.capacity', 200);
        $this->assertDatabaseHas('venues', ['name' => 'New Venue']);
    });

    it('user cannot create venue', function () {
        $response = $this->withHeader('Authorization', 'Bearer '.jwtToken($this->user))
            ->postJson('/api/admin/venues', [
                'name' => 'New Venue',
                'price' => 1000,
                'capacity' => 100,
            ]);

        $response->assertForbidden();
    });

    it('guest cannot access admin routes', function () {
        $response = $this->postJson('/api/admin/venues', [
            'name' => 'New Venue',
            'price' => 1000,
            'capacity' => 100,
        ]);

        $response->assertUnauthorized();
    });

    it('guest cannot access admin update route', function () {
        $venue = Venue::factory()->create();
        $response = $this->putJson('/api/admin/venues/'.$venue->id, ['name' => 'Hacked']);

        $response->assertUnauthorized();
    });

    it('guest cannot access admin delete route', function () {
        $venue = Venue::factory()->create();
        $response = $this->deleteJson('/api/admin/venues/'.$venue->id);

        $response->assertUnauthorized();
    });

    it('validates required fields', function () {
        $response = $this->withHeader('Authorization', 'Bearer '.jwtToken($this->admin))
            ->postJson('/api/admin/venues', [
                'name' => '',
                'price' => -1,
                'capacity' => -1,
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'price', 'capacity']);
    });
});

describe('PUT /api/admin/venues/{id}', function () {
    it('allows admin to update venue', function () {
        $venue = Venue::factory()->create(['name' => 'Old Name']);

        $response = $this->withHeader('Authorization', 'Bearer '.jwtToken($this->admin))
            ->putJson('/api/admin/venues/'.$venue->id, [
                'name' => 'Updated Name',
                'price' => $venue->price,
                'capacity' => $venue->capacity,
            ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Name');
        $venue->refresh();
        $this->assertSame('Updated Name', $venue->name);
    });

    it('denies user role from updating venue', function () {
        $venue = Venue::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.jwtToken($this->user))
            ->putJson('/api/admin/venues/'.$venue->id, ['name' => 'Hacked']);

        $response->assertForbidden();
    });

    it('returns 404 for missing venue', function () {
        $response = $this->withHeader('Authorization', 'Bearer '.jwtToken($this->admin))
            ->putJson('/api/admin/venues/99999', ['name' => 'Test', 'price' => 100, 'capacity' => 50]);

        $response->assertNotFound();
    });
});

describe('DELETE /api/admin/venues/{id}', function () {
    it('allows admin to delete venue', function () {
        $venue = Venue::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.jwtToken($this->admin))
            ->deleteJson('/api/admin/venues/'.$venue->id);

        $response->assertNoContent();
        $this->assertDatabaseMissing('venues', ['id' => $venue->id]);
    });

    it('denies user role from deleting venue', function () {
        $venue = Venue::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer '.jwtToken($this->user))
            ->deleteJson('/api/admin/venues/'.$venue->id);

        $response->assertForbidden();
    });

    it('returns 404 for missing venue', function () {
        $response = $this->withHeader('Authorization', 'Bearer '.jwtToken($this->admin))
            ->deleteJson('/api/admin/venues/99999');

        $response->assertNotFound();
    });
});
