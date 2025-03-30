<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Auth;

	class Feedback extends Model
	{
		use HasFactory;

		protected $table = 'feedback';

		protected $fillable = [
			'user_id',
			'guest_email',
			'title',
			'details',
			'status',
			'votes_count', // Add votes_count here if you want to mass assign it directly (less common)
		];

		// Possible statuses - good for validation or dropdowns
		public const STATUS_OPEN = 'Open';
		public const STATUS_UNDER_REVIEW = 'Under Review';
		public const STATUS_PLANNED = 'Planned';
		public const STATUS_IN_PROGRESS = 'In Progress';
		public const STATUS_COMPLETE = 'Complete';
		public const STATUS_CLOSED = 'Closed'; // Could be for duplicates, won't fix, etc.

		public static $statuses = [
			self::STATUS_OPEN,
			self::STATUS_UNDER_REVIEW,
			self::STATUS_PLANNED,
			self::STATUS_IN_PROGRESS,
			self::STATUS_COMPLETE,
			self::STATUS_CLOSED,
		];


		/**
		 * Get the user who submitted the feedback (if any).
		 */
		public function user()
		{
			return $this->belongsTo(User::class);
		}

		/**
		 * Get the votes for this feedback.
		 */
		public function votes()
		{
			return $this->hasMany(FeedbackVote::class);
		}

		/**
		 * Check if the currently authenticated user has voted for this feedback.
		 */
		public function getHasVotedAttribute()
		{
			if (!Auth::check()) {
				return false;
			}
			// Check if a vote exists for this feedback and the current user
			// Eager load this relation when fetching multiple feedback items for performance
			return $this->votes()->where('user_id', Auth::id())->exists();
		}

		/**
		 * Get the display name for the submitter.
		 */
		public function getSubmitterNameAttribute()
		{
			return $this->user ? $this->user->name : 'Guest';
		}

		/**
		 * Get the submitter avatar url.
		 */
		public function getSubmitterAvatarAttribute()
		{
			if ($this->user && $this->user->avatar) {
				return \Illuminate\Support\Facades\Storage::url($this->user->avatar);
			}
			// Return a default guest avatar or placeholder
			return '/assets/images/avatar/placeholder.jpg';
		}

	}
