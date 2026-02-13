<?php

namespace App\Repositories;

use App\Models\Venue;
use App\Repositories\Contracts\VenueRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class VenueRepository implements VenueRepositoryInterface
{
    /**
     * Get all venues with optional filters and pagination.
     *
     * @param  array  $filters  per_page, paginate, search, min_lat, max_lat, min_lng, max_lng, min_capacity, max_price
     * @return Collection|LengthAwarePaginator
     */
    public function all(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = Venue::query();

        $this->applyBoundingBox($query, $filters);
        $this->applyOtherFilters($query, $filters);

        $perPage = $filters['per_page'] ?? 15;
        $paginate = $filters['paginate'] ?? true;

        if ($paginate && $perPage > 0) {
            return $query->orderBy('name')->paginate($perPage);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Find a venue by ID.
     *
     * @param  int  $id  Venue ID
     * @return Venue|null
     */
    public function find(int $id): ?Venue
    {
        return Venue::find($id);
    }

    /**
     * Create a new venue record.
     *
     * @param  array  $data  Valid venue attributes
     * @return Venue
     */
    public function create(array $data): Venue
    {
        return Venue::create($data);
    }

    /**
     * Update an existing venue.
     *
     * @param  Venue  $venue  Venue model
     * @param  array  $data  Attributes to update
     * @return Venue  Refreshed model
     */
    public function update(Venue $venue, array $data): Venue
    {
        $venue->update($data);

        return $venue->fresh();
    }

    /**
     * Delete a venue.
     *
     * @param  Venue  $venue  Venue model
     * @return bool
     */
    public function delete(Venue $venue): bool
    {
        return $venue->delete();
    }

    /**
     * Get venues within a geographic bounding box.
     *
     * @param  float|null  $minLat  Minimum latitude
     * @param  float|null  $maxLat  Maximum latitude
     * @param  float|null  $minLng  Minimum longitude
     * @param  float|null  $maxLng  Maximum longitude
     * @param  array  $filters  Additional filters
     * @return Collection|LengthAwarePaginator
     */
    public function getByBoundingBox(
        ?float $minLat,
        ?float $maxLat,
        ?float $minLng,
        ?float $maxLng,
        array $filters = []
    ): Collection|LengthAwarePaginator {
        $filters['min_lat'] = $minLat;
        $filters['max_lat'] = $maxLat;
        $filters['min_lng'] = $minLng;
        $filters['max_lng'] = $maxLng;

        return $this->all($filters);
    }

    /**
     * Apply latitude/longitude bounding box filters to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  Eloquent query builder
     * @param  array  $filters  min_lat, max_lat, min_lng, max_lng
     * @return void
     */
    private function applyBoundingBox($query, array $filters): void
    {
        if (isset($filters['min_lat'])) {
            $query->where('lat', '>=', $filters['min_lat']);
        }
        if (isset($filters['max_lat'])) {
            $query->where('lat', '<=', $filters['max_lat']);
        }
        if (isset($filters['min_lng'])) {
            $query->where('lng', '>=', $filters['min_lng']);
        }
        if (isset($filters['max_lng'])) {
            $query->where('lng', '<=', $filters['max_lng']);
        }
    }

    /**
     * Apply search and other filters (min_capacity, max_price) to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  Eloquent query builder
     * @param  array  $filters  search, min_capacity, max_price
     * @return void
     */
    private function applyOtherFilters($query, array $filters): void
    {
        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%'.$filters['search'].'%')
                    ->orWhere('description', 'like', '%'.$filters['search'].'%')
                    ->orWhere('address', 'like', '%'.$filters['search'].'%');
            });
        }
        if (isset($filters['min_capacity'])) {
            $query->where('capacity', '>=', $filters['min_capacity']);
        }
        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
    }
}
