<?php

namespace App\Services;

use App\Models\Venue;
use App\Repositories\Contracts\VenueRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Application service for venue operations.
 * Delegates persistence to VenueRepository.
 */
class VenueService
{
    public function __construct(
        private readonly VenueRepositoryInterface $venueRepository
    ) {}

    /**
     * List venues with optional filters (search, bounding box, pagination).
     *
     * @param  array  $filters  Optional: per_page, paginate, search, min_lat, max_lat, min_lng, max_lng, min_capacity, max_price
     * @return Collection|LengthAwarePaginator
     */
    public function listVenues(array $filters = []): Collection|LengthAwarePaginator
    {
        return $this->venueRepository->all($filters);
    }

    /**
     * List venues within a geographic bounding box.
     *
     * @param  float|null  $minLat  Minimum latitude
     * @param  float|null  $maxLat  Maximum latitude
     * @param  float|null  $minLng  Minimum longitude
     * @param  float|null  $maxLng  Maximum longitude
     * @param  array  $filters  Additional filters (per_page, search, etc.)
     * @return Collection|LengthAwarePaginator
     */
    public function listVenuesInBoundingBox(
        ?float $minLat,
        ?float $maxLat,
        ?float $minLng,
        ?float $maxLng,
        array $filters = []
    ): Collection|LengthAwarePaginator {
        return $this->venueRepository->getByBoundingBox(
            $minLat,
            $maxLat,
            $minLng,
            $maxLng,
            $filters
        );
    }

    /**
     * Get a single venue by ID.
     *
     * @param  int  $id  Venue ID
     * @return Venue|null
     */
    public function getVenue(int $id): ?Venue
    {
        return $this->venueRepository->find($id);
    }

    /**
     * Create a new venue.
     *
     * @param  array  $data  Venue attributes (name, description, address, lat, lng, price, capacity, rating, images)
     * @return Venue
     */
    public function createVenue(array $data): Venue
    {
        return $this->venueRepository->create($data);
    }

    /**
     * Update an existing venue.
     *
     * @param  Venue  $venue  Venue model instance
     * @param  array  $data  Attributes to update
     * @return Venue  Updated venue (refreshed from DB)
     */
    public function updateVenue(Venue $venue, array $data): Venue
    {
        return $this->venueRepository->update($venue, $data);
    }

    /**
     * Delete a venue.
     *
     * @param  Venue  $venue  Venue model instance
     * @return bool
     */
    public function deleteVenue(Venue $venue): bool
    {
        return $this->venueRepository->delete($venue);
    }
}
