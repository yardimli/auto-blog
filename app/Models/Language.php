<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\HasMany;

	class Language extends Model
	{
		protected $fillable = [
			'language_name',
			'locale',
			'active'
		];

		protected $casts = [
			'active' => 'boolean'
		];

		public function articles(): HasMany
		{
			return $this->hasMany(Article::class);
		}

		public function categories(): HasMany
		{
			return $this->hasMany(Category::class);
		}
	}
