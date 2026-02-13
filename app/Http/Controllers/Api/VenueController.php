<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VenueResource;
use App\Services\VenueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Public API controller for listing and viewing venues.
 * No authentication required.
 */
class VenueController extends Controller
{
    public function __construct(
        private readonly VenueService $venueService
    ) {}

    /**
     * List venues with optional bounding box and filters.
     *
     * GET /api/venues
     *
     * @param  Request  $request  Query params: min_lat, max_lat, min_lng, max_lng (bounding box), search, min_capacity, max_price, per_page
     * @return AnonymousResourceCollection|JsonResponse  Paginated venue collection
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $filters = $request->only([
            'min_lat', 'max_lat', 'min_lng', 'max_lng',
            'search', 'min_capacity', 'max_price', 'per_page',
        ]);

        $minLat = $request->filled('min_lat') ? (float) $request->min_lat : null;
        $maxLat = $request->filled('max_lat') ? (float) $request->max_lat : null;
        $minLng = $request->filled('min_lng') ? (float) $request->min_lng : null;
        $maxLng = $request->filled('max_lng') ? (float) $request->max_lng : null;

        $hasBoundingBox = $minLat !== null || $maxLat !== null || $minLng !== null || $maxLng !== null;

        $venues = $hasBoundingBox
            ? $this->venueService->listVenuesInBoundingBox($minLat, $maxLat, $minLng, $maxLng, $filters)
            : $this->venueService->listVenues($filters);

        return VenueResource::collection($venues);
    }

    /**
     * Show a single venue by ID.
     *
     * GET /api/venues/{id}
     *
     * @param  int  $id  Venue ID
     * @return VenueResource|JsonResponse  Venue resource or 404 if not found
     */
    public function show(int $id): VenueResource|JsonResponse
    {
        $venue = $this->venueService->getVenue($id);

        if (! $venue) {
            return response()->json(['message' => 'Venue not found.'], 404);
        }

        return new VenueResource($venue);
    }
}
