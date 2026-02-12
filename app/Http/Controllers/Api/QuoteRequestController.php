<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Models\Venue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuoteRequestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'event_date' => 'nullable|date',
            'guests' => 'nullable|integer|min:1',
            'message' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quote = QuoteRequest::create([
            'venue_id' => $request->venue_id,
            'user_id' => $request->user()?->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'event_date' => $request->event_date,
            'guests' => $request->guests,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return response()->json(['data' => $quote, 'message' => 'Quote request submitted.'], 201);
    }
}
