<?php
  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Concerns\HasUuids;
  use Illuminate\Database\Eloquent\Factories\HasFactory;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;

  class Rating extends Model
  {
    use HasFactory, HasUuids;

      protected $keyType = 'string';
      public $incrementing = false;

      protected $fillable = [
        'user_id',
        'product_id',
        'stars',
        'comment'
      ];

      public function getStarsImageAttribute(): string
      {
          return asset('images/ratings/rating-' . ((int) round($this->stars * 10)) . '.png');
      }

      public function user(): BelongsTo
      {
          return $this->belongsTo(User::class);
      }
  }