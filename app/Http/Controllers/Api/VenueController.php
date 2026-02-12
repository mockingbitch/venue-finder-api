<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Venue::query()->where('is_active', true)->withCount('spaces');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('suburb')) {
            $query->where('suburb', 'like', '%' . $request->suburb . '%');
        }
        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }
        if ($request->filled('max_price_level')) {
            $query->where('price_level', '<=', $request->max_price_level);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('suburb', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) $request->get('per_page', 12), 50);
        $venues = $query->orderBy('rating', 'desc')->paginate($perPage);

        $venuesCount = Venue::where('is_active', true)->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->when($request->filled('suburb'), fn ($q) => $q->where('suburb', 'like', '%' . $request->suburb . '%'))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhere('suburb', 'like', "%{$request->search}%");
            })
            ->count();
        $spacesCount = \App\Models\Space::whereIn('venue_id', Venue::where('is_active', true)->pluck('id'))->count();

        return response()->json([
            'data' => $venues->items(),
            'meta' => [
                'current_page' => $venues->currentPage(),
                'last_page' => $venues->lastPage(),
                'per_page' => $venues->perPage(),
                'total' => $venues->total(),
                'venues_count' => $venuesCount,
                'spaces_count' => $spacesCount,
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $venue = Venue::where('slug', $slug)->where('is_active', true)->with('spaces')->firstOrFail();
        return response()->json(['data' => $venue]);
    }

    public function map(Request $request): JsonResponse
    {
        $query = Venue::query()->where('is_active', true)->whereNotNull('lat')->whereNotNull('lng');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('suburb')) {
            $query->where('suburb', 'like', '%' . $request->suburb . '%');
        }
        if ($request->filled('bounds')) {
            $bounds = explode(',', $request->bounds);
            if (count($bounds) === 4) {
                $query->whereBetween('lat', [(float) $bounds[0], (float) $bounds[2]])
                    ->whereBetween('lng', [(float) $bounds[1], (float) $bounds[3]]);
            }
        }

        $venues = $query->select('id', 'name', 'slug', 'lat', 'lng', 'category', 'suburb', 'rating', 'price_level')->get();

        $venuesCount = Venue::where('is_active', true)->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->when($request->filled('suburb'), fn ($q) => $q->where('suburb', 'like', '%' . $request->suburb . '%'))->count();
        $spacesCount = \App\Models\Space::whereIn('venue_id', Venue::where('is_active', true)->pluck('id'))->count();

        return response()->json([
            'data' => $venues,
            'meta' => [
                'venues_count' => $venuesCount,
                'spaces_count' => $spacesCount,
            ],
        ]);
    }
}
