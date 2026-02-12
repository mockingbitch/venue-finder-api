<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $identifier = $request->user()?->id ?? $request->header('X-Session-Id');
        if (!$identifier) {
            return response()->json(['data' => []]);
        }

        $query = Favorite::with('venue');
        if ($request->user()) {
            $query->where('user_id', $request->user()->id);
        } else {
            $query->where('session_id', $identifier);
        }
        $favorites = $query->get()->pluck('venue_id')->toArray();

        return response()->json(['data' => $favorites]);
    }

    public function toggle(Request $request, Venue $venue): JsonResponse
    {
        $user = $request->user();
        $sessionId = $request->header('X-Session-Id');

        $favorite = Favorite::when($user, fn ($q) => $q->where('user_id', $user->id), fn ($q) => $q->where('session_id', $sessionId))
            ->where('venue_id', $venue->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
        } else {
            Favorite::create([
                'user_id' => $user?->id,
                'venue_id' => $venue->id,
                'session_id' => $user ? null : $sessionId,
            ]);
            $isFavorited = true;
        }

        return response()->json(['data' => ['is_favorited' => $isFavorited]]);
    }
}
