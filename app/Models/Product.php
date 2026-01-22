<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'image',
        'type',
        'price_cents',
        'quantity',
        'keywords',
    ];

    protected $casts = ['keywords' => 'array'];

    public function getStarsAverageAttribute(): string
    {
        $stars = round(($this->rating_avg ?? 0) * 2) / 2;
        return number_format($stars, 1);
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

