<?php namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Added for avatar path

class Feedback extends Model
{
	use HasFactory;

	protected $table = 'feedback';

	protected $fillable = [
		'user_id',      // Submitter (can be null for guests)
		'owner_user_id', // The user this feedback is for
		'guest_email',
		'title',
		'details',
		'status',
		// votes_count is handled automatically by FeedbackVote model observers
	];

	// Add default status if not set during creation
	protected $attributes = [
		'status' => self::STATUS_OPEN,
		'votes_count' => 0,
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
	 * Renamed to submitter() for clarity.
	 */
	public function submitter()
	{
		// Corresponds to the 'user_id' column
		return $this->belongsTo(User::class, 'user_id');
	}

	/**
	 * Get the user who owns this feedback item (the recipient).
	 */
	public function owner()
	{
		// Corresponds to the 'owner_user_id' column
		return $this->belongsTo(User::class, 'owner_user_id');
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
	 * Accessor: $feedback->has_voted
	 */
	public function getHasVotedAttribute()
	{
		if (!Auth::check()) {
			return false;
		}
		// Check if a vote exists for this feedback and the current user
		// Eager load this relation when fetching multiple feedback items for performance
		// Ensure votes relation is loaded before accessing this
		// return $this->votes()->where('user_id', Auth::id())->exists(); // Less efficient if called in loop without eager loading

		// Optimized check using loaded relationship (if available) or query
		if ($this->relationLoaded('votes')) {
			return $this->votes->contains('user_id', Auth::id());
		}
		// Fallback query if votes relationship is not loaded
		return $this->votes()->where('user_id', Auth::id())->exists();
	}

	/**
	 * Get the display name for the submitter.
	 * Accessor: $feedback->submitter_name
	 */
	public function getSubmitterNameAttribute()
	{
		// Use the submitter relationship
		return $this->submitter ? $this->submitter->name : 'Guest';
	}

	/**
	 * Get the submitter avatar url.
	 * Accessor: $feedback->submitter_avatar
	 */
	public function getSubmitterAvatarAttribute()
	{
		if ($this->submitter && $this->submitter->avatar) {
			// Check if avatar is a full URL (e.g., from Google) or relative path
			if (filter_var($this->submitter->avatar, FILTER_VALIDATE_URL)) {
				return $this->submitter->avatar;
			}
			return Storage::url($this->submitter->avatar);
		}
		// Return a default guest avatar or placeholder
		return '/assets/images/avatar/placeholder.jpg'; // Make sure this path is correct
	}
}
