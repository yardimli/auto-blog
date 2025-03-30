<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB; // Import DB facade

	class FeedbackVote extends Model
	{
		use HasFactory;

		protected $table = 'feedback_votes';

		protected $fillable = [
			'feedback_id',
			'user_id',
		];

		// Automatically update the votes_count on the feedback table
		// when a vote is created or deleted.
		protected static function booted()
		{
			static::created(function ($vote) {
				// Use DB raw update for atomic increment
				DB::table('feedback')
					->where('id', $vote->feedback_id)
					->increment('votes_count');
			});

			static::deleted(function ($vote) {
				// Use DB raw update for atomic decrement
				// Ensure count doesn't go below zero, though it shouldn't with unique constraint
				DB::table('feedback')
					->where('id', $vote->feedback_id)
					->where('votes_count', '>', 0) // Prevent going negative
					->decrement('votes_count');
			});
		}


		/**
		 * Get the feedback this vote belongs to.
		 */
		public function feedback()
		{
			return $this->belongsTo(Feedback::class);
		}

		/**
		 * Get the user who cast the vote.
		 */
		public function user()
		{
			return $this->belongsTo(User::class);
		}
	}
