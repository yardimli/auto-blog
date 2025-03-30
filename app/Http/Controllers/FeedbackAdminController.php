<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException; // Import exception

class FeedbackAdminController extends Controller // Consider renaming to UserFeedbackController
{
	// Apply auth middleware to all methods in this controller
	public function __construct()
	{
		$this->middleware('auth');
		// No longer need admin-specific middleware here
	}

	/**
	 * Check if the authenticated user owns the given feedback item.
	 * Throws AuthorizationException if not.
	 */
	private function authorizeOwnership(Feedback $feedback)
	{
		if (Auth::id() !== $feedback->owner_user_id) {
			throw new AuthorizationException('You do not own this feedback item.');
			// or abort(403, 'You do not own this feedback item.');
		}
	}

	/**
	 * Display a listing of feedback items OWNED BY the authenticated user.
	 */
	public function index(Request $request)
	{
		$user = Auth::user(); // The logged-in user viewing their feedback

		// Get filters from request
		$status = $request->input('status');
		$sortBy = $request->input('sort_by', 'created_at'); // Default sort
		$sortDir = $request->input('sort_dir', 'desc'); // Default direction

		// Base query: Feedback owned by the current user
		$query = Feedback::where('owner_user_id', $user->id)
			->with('submitter') // Eager load submitter info
			->orderBy($sortBy, $sortDir);

		// Apply status filter
		if ($status && in_array($status, Feedback::$statuses)) {
			$query->where('status', $status);
		}

		// Add other filters if needed (e.g., search title/details)
		$search = $request->input('search');
		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('title', 'like', "%{$search}%")
					->orWhere('details', 'like', "%{$search}%");
			});
		}

		$feedbackItems = $query->paginate(20); // Paginate results
		$statuses = Feedback::$statuses; // Get statuses for dropdown

		// Append query params for pagination and filters
		$feedbackItems->appends($request->query());

		// Pass data to the view
		return view('feedback.index', compact('feedbackItems', 'statuses', 'status', 'sortBy', 'sortDir', 'search')); // Pass search too
	}

	/**
	 * Show the form for creating a new resource. (Disabled)
	 * Users don't create feedback for themselves in the admin area.
	 */
	public function create()
	{
		abort(404);
	}

	/**
	 * Store a newly created resource in storage. (Disabled)
	 */
	public function store(Request $request)
	{
		abort(404);
	}

	/**
	 * Display the specified feedback item owned by the user.
	 */
	public function show(Feedback $feedback)
	{
		$this->authorizeOwnership($feedback); // Check ownership

		// Eager load related data needed for the view
		$feedback->load('submitter', 'votes.user'); // Load submitter and voters

		return view('feedback.show', compact('feedback'));
	}

	/**
	 * Show the form for editing the specified feedback item owned by the user.
	 */
	public function edit(Feedback $feedback)
	{
		$this->authorizeOwnership($feedback); // Check ownership

		$statuses = Feedback::$statuses;
		// You could potentially load users if you wanted to re-assign ownership,
		// but that seems complex for this scenario. Stick to editing status/details.
		// $users = User::orderBy('name')->get();

		return view('feedback.edit', compact('feedback', 'statuses'));
	}

	/**
	 * Update the specified feedback item owned by the user.
	 */
	public function update(Request $request, Feedback $feedback)
	{
		$this->authorizeOwnership($feedback); // Check ownership

		$validated = $request->validate([
			// Allow owner to edit title/details? Maybe just status?
			// Let's allow title/details for now.
			'title' => 'required|string|max:255',
			'details' => 'required|string|max:5000',
			'status' => ['required', Rule::in(Feedback::$statuses)],
			// Add validation for other fields like assigned owner IF implemented
		]);

		$feedback->update($validated);

		return redirect()->route('feedback.index')->with('success', 'Feedback updated successfully.');
	}

	/**
	 * Remove the specified feedback item owned by the user.
	 */
	public function destroy(Feedback $feedback)
	{
		$this->authorizeOwnership($feedback); // Check ownership

		// Consider related votes? Deleting feedback should cascade delete votes via DB constraint.
		$feedback->delete();

		return redirect()->route('feedback.index')->with('success', 'Feedback deleted successfully.');
	}
}
