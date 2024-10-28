<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\HasMany;

	class Image extends Model
	{
		protected $fillable = [
			'image_guid',
			'image_original_filename',
			'image_large_filename',
			'image_medium_filename',
			'image_small_filename'
		];

		public function articles(): HasMany
		{
			return $this->hasMany(Article::class, 'featured_image_id');
		}

		// You might want to add methods for handling image URLs
		public function getOriginalUrl(): string
		{
			return asset('storage/images/original/' . $this->image_original_filename);
		}

		public function getLargeUrl(): string
		{
			return asset('storage/images/large/' . $this->image_large_filename);
		}

		public function getMediumUrl(): string
		{
			return asset('storage/images/medium/' . $this->image_medium_filename);
		}

		public function getSmallUrl(): string
		{
			return asset('storage/images/small/' . $this->image_small_filename);
		}
	}
