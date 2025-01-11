<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class ReleaseNote extends Model
{
  protected $table = "release_notes";
  protected $fillable = [
    'user_id',
    'title',
    'body',
    'is_released',
    'released_at'
  ];

  protected $casts = [
    'is_released' => 'boolean',
    'released_at' => 'datetime'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  // Scope for published articles
  public function scopePublished($query)
  {
    return $query->where('is_released', true)
      ->whereNotNull('released_at')
      ->where('released_at', '<=', now());
  }

  // Scope for draft articles
  public function scopeDraft($query)
  {
    return $query->where('is_released', false);
  }

  public function getFormattedPostedAtAttribute()
  {
    return $this->released_at ? $this->released_at->format('F d, Y') : null;
  }

}