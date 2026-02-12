<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'suburb',
        'lat',
        'lng',
        'capacity',
        'area_sqm',
        'rating',
        'reviews_count',
        'price_level',
        'description',
        'image_url',
        'video_thumbnail_url',
        'has_offer',
        'is_active',
    ];

    protected $casts = [
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'rating' => 'decimal:1',
        'has_offer' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function spaces(): HasMany
    {
        return $this->hasMany(Space::class);
    }

    public function quoteRequests(): HasMany
    {
        return $this->hasMany(QuoteRequest::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function getPriceLevelStarsAttribute(): string
    {
        return str_repeat('$', (int) $this->price_level);
    }
}
