<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];

    protected $casts = [
        'keywords' => 'array',
        'rating_stars' => 'float',
        'price_cents' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}

