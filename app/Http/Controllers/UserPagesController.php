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

		// UserPagesController.php

		public function userBlog($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'blog');

			$articles = Article::with(['featuredImage', 'categories', 'language'])
				->where('user_id', $user->id)
				->where('is_published', true)
				->orderBy('posted_at', 'desc')
				->paginate(10);

			return view('user.pages.blog', compact('user', 'pageSettings',  'articles'));
		}

		public function userBlogArticle($username, $slug)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'blog');

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

			return view('user.pages.blog-article', compact('user', 'pageSettings',  'article', 'recentArticles'));
		}

		public function userHome($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'home');
			return view('user.pages.home', compact('user',  'pageSettings'));
		}


		public function userHelp($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'help');
			return view('user.pages.help', compact('user', 'pageSettings') );
		}

		public function userRoadmap($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'roadmap');
			return view('user.pages.roadmap', compact('user', 'pageSettings') );
		}

		public function userFeedback($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'feedback');
			return view('user.pages.feedback', compact('user', 'pageSettings') );
		}

		public function userChangelog($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'changelog');
			return view('user.pages.changelog', compact('user', 'pageSettings') );
		}

		public function userTerms($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'terms');
			return view('user.pages.terms', compact('user', 'pageSettings') );
		}

		public function userPrivacy($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'privacy');
			return view('user.pages.privacy', compact('user', 'pageSettings') );
		}

		public function userCookieConsent($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'cookie');
			return view('user.pages.cookie-consent', compact('user', 'pageSettings') );
		}

		private function getPageSettings($user, $pageType)
		{
			return $user->pageSettings()
				->where('page_type', $pageType)
				->first();
		}



		//------------------------------------------------------------------------------

	}
