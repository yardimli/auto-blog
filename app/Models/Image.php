<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsTo;
	use Illuminate\Database\Eloquent\Relations\HasMany;

	class Image extends Model
	{
		protected $fillable = [
			'user_id',
			'image_guid',
			'image_alt',
			'image_original_filename',
			'image_large_filename',
			'image_medium_filename',
			'image_small_filename'
		];

		public function user(): BelongsTo
		{
			return $this->belongsTo(User::class);
		}

		public function articles(): HasMany
		{
			return $this->hasMany(Article::class, 'featured_image_id');
		}

		// You might want to add methods for handling image URLs
		public function getOriginalUrl(): string
		{
			return asset('storage/upload-images/original/' . $this->image_original_filename);
		}

		public function getLargeUrl(): string
		{
			return asset('storage/upload-images/large/' . $this->image_large_filename);
		}

		public function getMediumUrl(): string
		{
			return asset('storage/upload-images/medium/' . $this->image_medium_filename);
		}

		public function getSmallUrl(): string
		{
			return asset('storage/upload-images/small/' . $this->image_small_filename);
		}
	}
