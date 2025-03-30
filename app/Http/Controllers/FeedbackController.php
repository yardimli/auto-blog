<?php namespace App\Http\Controllers;

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
	 */
	private function getPageSettings($user, $pageType)
	{
		return $user->pageSettings()
			->where('page_type', $pageType)
			->first();
	}

	/**
	 * Display the public feedback listing page for a specific user context.
	 * Shows feedback SUBMITTED TO the specified user.
	 */
	public function index(Request $request, $username)
	{
		$user = $this->getUserOrFail($username); // This is the OWNER of the feedback board
		$pageSettings = $this->getPageSettings($user, 'feedback');

		$sort = $request->input('sort', 'Trending'); // Default sort
		$search = $request->input('search');

		// --- Start Query ---
		// Base query: Get feedback owned by this user
		$query = Feedback::where('owner_user_id', $user->id)
			->with(['submitter', // Eager load the submitter (user_id)
				'votes' => function ($query) {
					// Eager load votes only for the current user if logged in
					if (Auth::check()) {
						$query->where('user_id', Auth::id());
					} else {
						// If not logged in, no need to load specific votes
						$query->whereRaw('1 = 0'); // Optimization
					}
				}]);

		// Apply search filter (searches title/details of feedback submitted TO $user)
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
			$query->withCount('votes') // Ensure votes_count is accurate or use the pre-calculated one
			->orderBy('votes_count', 'desc')
				->orderBy('created_at', 'desc');
		}
		// --- End Query ---

		$feedbackItems = $query->paginate(10); // Paginate results

		// Append query parameters to pagination links
		$feedbackItems->appends($request->only(['sort', 'search']));

		// Pass the $user (owner) to the view
		return view('user.pages.feedback', compact('user', 'pageSettings', 'feedbackItems', 'sort', 'search'));
	}

	/**
	 * Store a new feedback item submitted TO the user specified by $username.
	 */
	public function store(Request $request, $username)
	{
		// $userContext is the OWNER of the feedback board
		$userContext = $this->getUserOrFail($username);

		$validator = Validator::make($request->all(), [
			'title' => 'required|string|max:255',
			'details' => 'required|string|max:5000',
			'guest_email' => Rule::requiredIf(!Auth::check()) . '|nullable|email|max:255',
		]);

		if ($validator->fails()) {
			return redirect()->route('user.feedback.index', ['username' => $username])
				->withErrors($validator)
				->withInput();
		}

		$data = $validator->validated(); // Get validated data

		// Set the OWNER of the feedback
		$data['owner_user_id'] = $userContext->id;

		// Set the SUBMITTER (user_id) if logged in
		if (Auth::check()) {
			$data['user_id'] = Auth::id();
		} else {
			// Guest submission - guest_email is already in $data if provided and valid
			$data['user_id'] = null;
		}

		Feedback::create($data); // Create the feedback item

		return redirect()->route('user.feedback.index', ['username' => $username])
			->with('success', __('Feedback submitted successfully. Thank you!'));
	}

	/**
	 * Handle voting on a feedback item.
	 */
	public function vote(Request $request, $username, Feedback $feedback)
	{
		// $userContext = $this->getUserOrFail($username); // Username needed for redirect route

		if (!Auth::check()) {
			return redirect()->route('user.feedback.index', ['username' => $username])
				->with('error', 'You must be logged in to vote.');
		}

		$user = Auth::user(); // The voter

		// Prevent owner from voting on their own feedback items? Optional.
		// if ($feedback->owner_user_id === $user->id) {
		//     return redirect()->back()->with('error', 'You cannot vote on your own feedback items.');
		// }

		$existingVote = FeedbackVote::where('feedback_id', $feedback->id)
			->where('user_id', $user->id)
			->first();

		if ($existingVote) {
			// User already voted, so remove the vote (toggle)
			$existingVote->delete();
		} else {
			// User hasn't voted, add a new vote
			FeedbackVote::create([
				'feedback_id' => $feedback->id,
				'user_id'     => $user->id,
			]);
		}

		// Redirect back to the feedback page for that user
		// return redirect()->back(); // Might lose context sometimes
		return redirect()->route('user.feedback.index', ['username' => $username, '#' => 'feedback-'.$feedback->id]) // Optional: Add fragment
		->with('status', 'Vote recorded.'); // Use a neutral status message
	}
}
