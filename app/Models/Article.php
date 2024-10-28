<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;

	class Article extends Model
	{
		protected $fillable = [
			'language_id',
			'title',
			'subtitle',
			'slug',
			'is_published',
			'posted_at',
			'body',
			'meta_description',
			'short_description',
			'featured_image_id'
		];

		protected $casts = [
			'is_published' => 'boolean',
			'posted_at' => 'datetime'
		];

		protected static function boot()
		{
			parent::boot();

			static::creating(function ($article) {
				if (! $article->slug) {
					$article->slug = Str::slug($article->title);
				}
			});
		}

		public function language(): BelongsTo
		{
			return $this->belongsTo(Language::class);
		}

		public function featuredImage(): BelongsTo
		{
			return $this->belongsTo(Image::class, 'featured_image_id');
		}

		public function categories(): BelongsToMany
		{
			return $this->belongsToMany(Category::class);
		}

		// Scope for published articles
		public function scopePublished($query)
		{
			return $query->where('is_published', true)
				->whereNotNull('posted_at')
				->where('posted_at', '<=', now());
		}

		// Scope for draft articles
		public function scopeDraft($query)
		{
			return $query->where('is_published', false);
		}

		public function getFormattedPostedAtAttribute()
		{
			return $this->posted_at ? $this->posted_at->format('F d, Y') : null;
		}

	}
