<?php

namespace App\Repositories\Contracts;

use App\Models\Venue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contract for venue data access (repository pattern).
 */
interface VenueRepositoryInterface
{
    /**
     * Get all venues with optional filters and pagination.
     *
     * @param  array  $filters
     * @return Collection|LengthAwarePaginator
     */
    public function all(array $filters = []): Collection|LengthAwarePaginator;

    /**
     * Find a venue by ID.
     *
     * @param  int  $id
     * @return Venue|null
     */
    public function find(int $id): ?Venue;

    /**
     * Create a new venue.
     *
     * @param  array  $data
     * @return Venue
     */
    public function create(array $data): Venue;

    /**
     * Update an existing venue.
     *
     * @param  Venue  $venue
     * @param  array  $data
     * @return Venue
     */
    public function update(Venue $venue, array $data): Venue;

    /**
     * Delete a venue.
     *
     * @param  Venue  $venue
     * @return bool
     */
    public function delete(Venue $venue): bool;

    /**
     * Get venues within a geographic bounding box.
     *
     * @param  float|null  $minLat
     * @param  float|null  $maxLat
     * @param  float|null  $minLng
     * @param  float|null  $maxLng
     * @param  array  $filters
     * @return Collection|LengthAwarePaginator
     */
    public function getByBoundingBox(
        ?float $minLat,
        ?float $maxLat,
        ?float $minLng,
        ?float $maxLng,
        array $filters = []
    ): Collection|LengthAwarePaginator;
}
