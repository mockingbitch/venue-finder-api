<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVenueRequest;
use App\Http\Requests\Admin\UpdateVenueRequest;
use App\Http\Resources\VenueResource;
use App\Models\Venue;
use App\Services\VenueService;
use Illuminate\Http\JsonResponse;

/**
 * Admin API controller for venue CRUD operations.
 * Requires JWT authentication and admin role (enforced by policy).
 */
class VenueController extends Controller
{
    public function __construct(
        private readonly VenueService $venueService
    ) {}

    /**
     * Create a new venue.
     *
     * POST /api/admin/venues
     *
     * @param  StoreVenueRequest  $request  Validated request (name, description, address, lat, lng, price, capacity, rating, images)
     * @return VenueResource|JsonResponse  Created venue resource (201) or error response
     */
    public function store(StoreVenueRequest $request): VenueResource|JsonResponse
    {
        $this->authorize('create', Venue::class);

        $venue = $this->venueService->createVenue($request->validated());

        return (new VenueResource($venue))->response()->setStatusCode(201);
    }

    /**
     * Update an existing venue by ID.
     *
     * PUT /api/admin/venues/{id}
     *
     * @param  UpdateVenueRequest  $request  Validated request (partial venue fields)
     * @param  int  $id  Venue ID
     * @return VenueResource|JsonResponse  Updated venue resource or 404 if not found
     */
    public function update(UpdateVenueRequest $request, int $id): VenueResource|JsonResponse
    {
        $venue = $this->venueService->getVenue($id);

        if (! $venue) {
            return response()->json(['message' => 'Venue not found.'], 404);
        }

        $this->authorize('update', $venue);

        $venue = $this->venueService->updateVenue($venue, $request->validated());

        return new VenueResource($venue);
    }

    /**
     * Delete a venue by ID.
     *
     * DELETE /api/admin/venues/{id}
     *
     * @param  int  $id  Venue ID
     * @return JsonResponse  Empty 204 response or 404 if not found
     */
    public function destroy(int $id): JsonResponse
    {
        $venue = $this->venueService->getVenue($id);

        if (! $venue) {
            return response()->json(['message' => 'Venue not found.'], 404);
        }

        $this->authorize('delete', $venue);

        $this->venueService->deleteVenue($venue);

        return response()->json(null, 204);
    }
}
