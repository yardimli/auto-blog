<?php

	namespace App\Models;

	use Illuminate\Contracts\Auth\MustVerifyEmail;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	use Illuminate\Notifications\Notifiable;
	use Illuminate\Contracts\Auth\CanResetPassword;


//use Laravel\Fortify\TwoFactorAuthenticatable;
//use Laravel\Jetstream\HasProfilePhoto;
//use Laravel\Jetstream\HasTeams;
	use Laravel\Sanctum\HasApiTokens;

	use App\Notifications\CustomVerifyEmail;


	class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
	{
		use HasApiTokens;
		use HasFactory;

//	use HasProfilePhoto;
//	use HasTeams;
		use Notifiable;

//	use TwoFactorAuthenticatable;


		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array<int, string>
		 */
		protected $fillable = [
			'name',
			'email',
			'password',
			'google_id',
			'line_id',
			'facebook_id',
			'avatar',
			'picture',
			'username',
			'about_me',
			'member_status',
			'member_type',
			'last_login',
			'last_ip',
			'background_image',
			'email_verified_at',
			'openrouter_key',
			'company_name',
			'company_description',


		];

		/**
		 * The attributes that should be hidden for serialization.
		 *
		 * @var array<int, string>
		 */
		protected $hidden = [
			'password',
			'remember_token',
			'two_factor_recovery_codes',
			'two_factor_secret',
		];

		/**
		 * The attributes that should be cast.
		 *
		 * @var array<string, string>
		 */
		protected $casts = [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
		];

		protected $appends = [
			'profile_photo_url',
		];

		public function sendEmailVerificationNotification()
		{
			$this->notify(new CustomVerifyEmail());
		}


		public function isAdmin()
		{
			return $this->member_type === 1;
		}


		public function pageSettings()
		{
			return $this->hasMany(UserPageSetting::class);
		}
		/**
		 * Get the feedback items SUBMITTED BY this user.
		 */
		public function submittedFeedback()
		{
			return $this->hasMany(Feedback::class, 'user_id');
		}

		/**
		 * Get the feedback items OWNED BY (submitted TO) this user.
		 */
		public function ownedFeedback()
		{
			return $this->hasMany(Feedback::class, 'owner_user_id');
		}

		/**
		 * Get the feedback votes cast by the user.
		 */
		public function feedbackVotes()
		{
			return $this->hasMany(FeedbackVote::class);
		}

		/**
		 * Get the URL for the user's profile photo.
		 * (Included from original User model)
		 */
		public function getProfilePhotoUrlAttribute()
		{
			if ($this->avatar) {
				if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
					return $this->avatar; // Already a full URL
				}
				// Assume stored relative path if not a URL
				return \Illuminate\Support\Facades\Storage::url($this->avatar);
			}

			// Return default image if no avatar
			return '/assets/images/avatar/placeholder.jpg'; // Or use a different default
		}

	}
