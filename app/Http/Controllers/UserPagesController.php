<?php

	namespace App\Http\Controllers;

	use App\Models\Article;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use App\Models\User;
	use App\Models\NewOrder;
	use App\Models\NewOrderItem;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;
	use Illuminate\Support\Facades\Validator;
	use App\Helpers\MyHelper;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Validation\Rule;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Validation\ValidationException;
	use Illuminate\Pagination\LengthAwarePaginator;

	class UserPagesController extends Controller
	{

		private function getUserOrFail($username)
		{
			$user = User::where('username', $username)->first();
			if (!$user) {
				abort(404);
			}
			return $user;
		}

		public function userHome($username)
		{
			$user = $this->getUserOrFail($username);
			return view('user.pages.home', compact('user'));
		}

		// UserPagesController.php

		public function userBlog($username)
		{
			$user = $this->getUserOrFail($username);

			$articles = Article::with(['featuredImage', 'categories', 'language'])
				->where('user_id', $user->id)
				->where('is_published', true)
				->orderBy('posted_at', 'desc')
				->paginate(10);

			return view('user.pages.blog', compact('user', 'articles'));
		}

		public function userBlogArticle($username, $slug)
		{
			$user = $this->getUserOrFail($username);

			$article = Article::with(['featuredImage', 'categories', 'language'])
				->where('user_id', $user->id)
				->where('slug', $slug)
				->where('is_published', true)
				->firstOrFail();

			// Get recent articles from the same categories
			$categoryIds = $article->categories->pluck('id');
			$recentArticles = Article::with(['featuredImage', 'categories'])
				->where('user_id', $user->id)
				->where('id', '!=', $article->id)
				->where('is_published', true)
				->whereHas('categories', function($query) use ($categoryIds) {
					$query->whereIn('categories.id', $categoryIds);
				})
				->orderBy('posted_at', 'desc')
				->limit(3)
				->get();

			return view('user.pages.blog-article', compact('user', 'article', 'recentArticles'));
		}

		public function userHelp($username)
		{
			$user = $this->getUserOrFail($username);
			return view('user.pages.help', compact('user'));
		}

		public function userRoadmap($username)
		{
			$user = $this->getUserOrFail($username);
			return view('user.pages.roadmap', compact('user'));
		}

		public function userFeedback($username)
		{
			$user = $this->getUserOrFail($username);
			return view('user.pages.feedback', compact('user'));
		}

		public function userChangelog($username)
		{
			$user = $this->getUserOrFail($username);
			return view('user.pages.changelog', compact('user'));
		}

		public function userTerms($username)
		{
			$user = $this->getUserOrFail($username);
			return view('user.pages.terms', compact('user'));
		}

		public function userPrivacy($username)
		{
			$user = $this->getUserOrFail($username);
			return view('user.pages.privacy', compact('user'));
		}

		public function userCookieConsent($username)
		{
			$user = $this->getUserOrFail($username);
			return view('user.pages.cookie-consent', compact('user'));
		}

		//------------------------------------------------------------------------------

	}
