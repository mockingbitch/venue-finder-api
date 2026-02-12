<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'name',
        'capacity',
        'area_sqm',
        'description',
        'image_url',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
