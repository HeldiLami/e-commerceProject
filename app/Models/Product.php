<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'keywords' => 'array',
        'rating_stars' => 'float',
        'price_cents' => 'integer',
    ];

    public function getStarsAverageAttribute(): string
    {
        $avg = $this->rating_avg ?? 0;

        $rounded = round($avg * 2) / 2;

        return number_format($rounded, 1);
    }

    public function getStarsImageAttribute(): string
    {
        $stars = round(($this->rating_avg ?? 0) * 2) / 2;
        return asset('images/ratings/rating-' . ((int) ($stars * 10)) . '.png');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}

