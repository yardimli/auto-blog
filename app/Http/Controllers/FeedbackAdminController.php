<?php

	namespace App\Http\Controllers; // Correct namespace

	use App\Http\Controllers\Controller; // Import base Controller
	use App\Models\Feedback;
	use App\Models\User; // Import User model if needed for filtering owners etc.
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Validation\Rule; // Import Rule

	class FeedbackAdminController extends Controller
	{
		// Middleware can be applied in routes/web.php or here in the constructor
		public function __construct()
		{
			// Example: Apply auth middleware to all methods
			// $this->middleware('auth');
			// Add admin check middleware if you create one
			// $this->middleware('admin'); // Assuming you create an 'admin' middleware
		}

		private function checkAdmin() {
			// Simple check for user ID 1 - Replace with a proper role/permission check later
			if (Auth::guest() || Auth::user()->id !== 1) {
				abort(403, 'Unauthorized action.');
			}
		}

		/**
		 * Display a listing of the resource.
		 */
		public function index(Request $request)
		{
			$this->checkAdmin();

			// Get filters from request
			$status = $request->input('status');
			$sortBy = $request->input('sort_by', 'created_at'); // Default sort
			$sortDir = $request->input('sort_dir', 'desc'); // Default direction

			$query = Feedback::with('user')->orderBy($sortBy, $sortDir); // Eager load user

			// Apply status filter
			if ($status && in_array($status, Feedback::$statuses)) {
				$query->where('status', $status);
			}

			// Add other filters like search, user, etc. if needed

			$feedbackItems = $query->paginate(20); // Paginate results

			$statuses = Feedback::$statuses; // Get statuses for dropdown

			return view('feedback.index', compact('feedbackItems', 'statuses', 'status', 'sortBy', 'sortDir'));
		}

		/**
		 * Show the form for creating a new resource.
		 * (Usually not needed for admin feedback management)
		 */
		public function create()
		{
			// $this->checkAdmin();
			// return view('feedback.create');
			abort(404); // Or redirect, typically admins don't *create* feedback this way
		}

		/**
		 * Store a newly created resource in storage.
		 * (Usually not needed for admin feedback management)
		 */
		public function store(Request $request)
		{
			$this->checkAdmin();
			abort(404);
		}

		/**
		 * Display the specified resource.
		 * (Could be used for a detailed view if needed)
		 */
		public function show(Feedback $feedback)
		{
			$this->checkAdmin();
			// Eager load related data if necessary
			$feedback->load('user', 'votes.user');
			return view('feedback.show', compact('feedback'));
		}

		/**
		 * Show the form for editing the specified resource.
		 */
		public function edit(Feedback $feedback)
		{
			$this->checkAdmin();
			$statuses = Feedback::$statuses;
			// Add users list if you want to assign an owner (optional)
			// $users = User::orderBy('name')->get();
			return view('feedback.edit', compact('feedback', 'statuses'));
		}

		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, Feedback $feedback)
		{
			$this->checkAdmin();

			$validated = $request->validate([
				'title' => 'required|string|max:255',
				'details' => 'required|string|max:5000',
				'status' => ['required', Rule::in(Feedback::$statuses)],
				// Add validation for other fields like assigned owner if implemented
			]);

			$feedback->update($validated);

			return redirect()->route('feedback.index')->with('success', 'Feedback updated successfully.');
		}

		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(Feedback $feedback)
		{
			$this->checkAdmin();
			$feedback->delete();
			return redirect()->route('feedback.index')->with('success', 'Feedback deleted successfully.');
		}
	}
