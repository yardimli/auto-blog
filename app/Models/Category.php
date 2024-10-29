<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;
	use Illuminate\Support\Str;

	class Category extends Model
	{
		protected $fillable = [
			'user_id',
			'language_id',
			'category_name',
			'category_slug',
			'parent_id',
			'category_description'
		];

		protected static function boot()
		{
			parent::boot();

			static::creating(function ($category) {
				if (! $category->category_slug) {
					$category->category_slug = Str::slug($category->category_name);
				}
			});
		}

		public function language(): BelongsTo
		{
			return $this->belongsTo(Language::class);
		}

		public function user(): BelongsTo
		{
			return $this->belongsTo(User::class);
		}

		public function parent(): BelongsTo
		{
			return $this->belongsTo(Category::class, 'parent_id');
		}

		public function children(): HasMany
		{
			return $this->hasMany(Category::class, 'parent_id');
		}

		public function articles(): BelongsToMany
		{
			return $this->belongsToMany(Article::class);
		}
	}
