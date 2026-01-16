<?php
  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

  class Rating extends Model
  {
    use HasFactory;      
    use HasUuids;

      protected $keyType = 'string';
      public $incrementing = false;

      public function ratings(): HasMany
      {
          return $this->hasMany(Rating::class);
      }
      public function user(): BelongsTo
      {
          // This links the 'user_id' column in your ratings table to the User model
          return $this->belongsTo(User::class);
      }
  }