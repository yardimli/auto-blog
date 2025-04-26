<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Help extends Model
{
  protected $table = "helps";
  protected $fillable = [
    'user_id',
    'category_id',
    'title',
    'body',
    'order',
    'is_published',
    'published_at'
  ];

  protected $dates = ['published_at'];

  protected $casts = [
    'is_published' => 'boolean',
    'published_at' => 'datetime'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }

}