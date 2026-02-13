<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Venue model for event venue listings.
 * Attributes: name, description, address, lat, lng, price, capacity, rating, images.
 */
class Venue extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'description',
        'address',
        'lat',
        'lng',
        'price',
        'capacity',
        'rating',
        'images',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lat' => 'decimal:8',
            'lng' => 'decimal:8',
            'price' => 'decimal:2',
            'rating' => 'decimal:2',
            'capacity' => 'integer',
            'images' => 'array',
        ];
    }
}
