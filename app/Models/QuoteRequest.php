<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'user_id',
        'name',
        'email',
        'phone',
        'event_date',
        'guests',
        'message',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
