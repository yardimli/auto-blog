<?php

	namespace App\Http\Controllers;

	use App\Models\Feedback; // Import Feedback model
	use App\Models\FeedbackVote;
	use App\Models\User; // Import User model
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Validator; // Import Validator
	use Illuminate\Validation\Rule; // Import Rule class

	class FeedbackController extends Controller
	{
		/**
		 * Get user by username or fail.
		 * (Copied from UserPagesController for consistency, could be moved to a Trait)
		 */
		private function getUserOrFail($username)
		{
			$user = User::where('username', $username)->first();
			if (!$user) {
				abort(404, 'User not found.');
			}
			return $user;
		}

		/**
		 * Get page settings for the user and page type.
		 * (Copied from UserPagesController for consistency)
		 */
		private function getPageSettings($user, $pageType) {
			// Attempt to find settings, return default if not found or provide null/defaults
			return $user->pageSettings()
				->where('page_type', $pageType)
				->first(); // Or use firstOrNew/firstOrCreate with default values if needed
		}


		/**
		 * Display the public feedback listing page for a specific user context.
		 * Note: This still shows GLOBAL feedback by default, just accessed via a user's URL.
		 * Modify the query if you want to filter feedback *by* this user.
		 */
		public function index(Request $request, $username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'feedback'); // Get settings like other user pages

			// Get sorting preference, default to 'Trending' (most votes)
			$sort = $request->input('sort', 'Trending');
			$search = $request->input('search');

			// --- Start Query ---
			$query = Feedback::with(['user', 'votes' => function ($query) {
				// Eager load votes only for the current user if logged in
				if (Auth::check()) {
					$query->where('user_id', Auth::id());
				} else {
					// If not logged in, no need to load specific votes
					$query->whereRaw('1 = 0'); // Optimization: Don't load any votes
				}
			}]);

			// Apply search filter
			if ($search) {
				$query->where(function ($q) use ($search) {
					$q->where('title', 'like', "%{$search}%")
						->orWhere('details', 'like', "%{$search}%");
				});
			}

			// Apply sorting
			if ($sort === 'Newest') {
				$query->orderBy('created_at', 'desc');
			} else { // Default to 'Trending'
				$query->orderBy('votes_count', 'desc')->orderBy('created_at', 'desc');
			}
			// --- End Query ---

			// ** Filtering Decision Point: **
			// If you want /@username/feedback to show ONLY feedback submitted by $user:
			// Uncomment the following line:
			// $query->where('user_id', $user->id);
			// Be aware this changes the nature of the feedback page significantly.
			// Currently, it shows *all* feedback, just under the user's URL namespace.

			$feedbackItems = $query->paginate(10); // Paginate results

			// Append query parameters to pagination links
			$feedbackItems->appends($request->only(['sort', 'search']));

			// Pass the specific $user to the view for context (e.g., header, links)
			return view('user.pages.feedback', compact('user', 'pageSettings', 'feedbackItems', 'sort', 'search'));
		}

		/**
		 * Store a new feedback item submitted via the user-specific page.
		 */
		public function store(Request $request, $username)
		{
			$userContext = $this->getUserOrFail($username); // Get the user context for redirection

			$validator = Validator::make($request->all(), [
				'title' => 'required|string|max:255',
				'details' => 'required|string|max:5000',
				'guest_email' => Rule::requiredIf(!Auth::check()) . '|nullable|email|max:255', // Require email if guest
			]);

			if ($validator->fails()) {
				// Redirect back to the correct user's feedback page
				return redirect()->route('user.feedback.index', ['username' => $username])
					->withErrors($validator)
					->withInput();
			}

			$data = $validator->validated(); // Get validated data

			if (Auth::check()) {
				$data['user_id'] = Auth::id();
			} else {
				// Guest submission - guest_email is already in $data if provided and valid
				$data['user_id'] = null;
			}

			Feedback::create($data);

			// Redirect back to the correct user's feedback page
			return redirect()->route('user.feedback.index', ['username' => $username])
				->with('success', __('Feedback submitted successfully. Thank you!'));
		}

		/**
		 * Handle voting on a feedback item from the user-specific page.
		 */
		public function vote(Request $request, $username, Feedback $feedback)
		{
			// $userContext = $this->getUserOrFail($username); // We need the username for the route, but not necessarily the user object for logic here

			// Ensure user is logged in to vote
			if (!Auth::check()) {
				// Or return an error response if using AJAX
				// Redirect back might be tricky if the referrer policy is strict,
				// but let's keep it simple for now. Consider redirecting to the specific feedback page.
				return redirect()->route('user.feedback.index', ['username' => $username])
					->with('error', 'You must be logged in to vote.');
				// return redirect()->back()->with('error', 'You must be logged in to vote.');
			}

			$user = Auth::user();

			// Check if the user has already voted
			$existingVote = FeedbackVote::where('feedback_id', $feedback->id)
				->where('user_id', $user->id)
				->first();

			if ($existingVote) {
				// User already voted, so remove the vote (toggle)
				$existingVote->delete();
				// No message needed, UI will update
			} else {
				// User hasn't voted, add a new vote
				FeedbackVote::create([
					'feedback_id' => $feedback->id,
					'user_id' => $user->id,
				]);
				// No message needed, UI will update
			}

			// Redirect back to the previous page (or return JSON if using AJAX)
			// redirect()->back() should work correctly as the referrer URL will include /@{username}/feedback
			return redirect()->back();
		}
	}
