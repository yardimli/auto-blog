<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class UserPageSetting extends Model
	{
		protected $fillable = [
			'user_id',
			'page_type',
			'title',
			'description'
		];

		public function user()
		{
			return $this->belongsTo(User::class);
		}
	}
