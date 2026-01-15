<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Add this
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, HasUuids; // Use it here

    protected $fillable = [
        'user_id',
        'total_cents',
        'status',
    ];

    /**
     * The Many-to-Many relationship with Products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('quantity', 'unit_price_cents')
                    ->withTimestamps();
    }
}
