<?php

	namespace App\Http\Controllers;

	use App\Models\Article;
	use App\Models\Category;
	use App\Models\ChangeLog;
  use App\Models\Help;
	use App\Models\UserPageSetting;
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
	use Illuminate\Support\Facades\View;
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

		private function getUserPageData($username)
		{
			$user = User::where('username', $username)->firstOrFail();
			$pageSettings = UserPageSetting::where('user_id', $user->id)->first(); // Assuming you have this model

			// Fetch categories and published articles for the sidebar/help pages
			$categories = Category::where('user_id', $user->id)
				->orderBy('category_name') // Or by a specific order column
				->get();

			$groupedArticles = Help::with('category')
				->where('user_id', $user->id)
				->where('is_published', true)
				->orderBy('order', 'asc') // Assuming an order column exists
				->orderBy('created_at', 'desc')
				->get()
				->groupBy('category.category_name'); // Group by name for easier access

			// Share data needed for the layout/sidebar partial across help views
			View::share([
				'user' => $user,
				'pageSettings' => $pageSettings, // Pass page settings if needed globally
				'helpCategoriesForSidebar' => $categories, // For sidebar navigation
				'groupedHelpArticlesForSidebar' => $groupedArticles, // For sidebar article lists
			]);


			return compact('user', 'pageSettings', 'categories', 'groupedArticles');
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

		//---------------- HELP

		public function userHelpIndex(Request $request, $username)
		{
			$data = $this->getUserPageData($username);
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'help');

			// Get first 6 categories with articles for the featured section
			$featuredCategories = $data['groupedArticles']->take(6);

			return view('user.pages.help', [
				'user' => $data['user'],
				'pageSettings' => $pageSettings,
				'featuredCategories' => $featuredCategories,
				// Sidebar data is shared via View::share()
			]);
		}

		/**
		 * Display the articles within a specific help category.
		 */
		public function userHelpCategory(Request $request, $username, $category_slug)
		{
			$data = $this->getUserPageData($username);
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'help');

			$currentCategory = Category::where('user_id', $data['user']->id)
				->where('category_slug', $category_slug)
				->firstOrFail();

			$articlesInCategory = Help::where('user_id', $data['user']->id)
				->where('category_id', $currentCategory->id)
				->where('is_published', true)
				->orderBy('order', 'asc')
				->orderBy('created_at', 'desc')
				->get();

			return view('user.pages.help-category', [
				'user' => $data['user'],
				'pageSettings' => $pageSettings,
				'currentCategory' => $currentCategory,
				'articlesInCategory' => $articlesInCategory,
				// Sidebar data is shared via View::share()
			]);
		}

		/**
		 * Display a single help article.
		 * Uses Route Model Binding for $help
		 */
		public function userHelpArticle(Request $request, $username, Help $help)
		{
			// Validate the article belongs to the user and is published
			$user = User::where('username', $username)->firstOrFail();
			if ($help->user_id !== $user->id || !$help->is_published) {
				abort(404);
			}

			$data = $this->getUserPageData($username); // To get sidebar data
			$pageSettings = $this->getPageSettings($user, 'help');

			// Eager load category if not already loaded by route model binding or needed
			$help->loadMissing('category');

			return view('user.pages.help-details', [
				'user' => $data['user'],
				'pageSettings' => $pageSettings,
				'helpArticle' => $help,
				// Sidebar data is shared via View::share()
			]);
		}

		//------------- HELP END


		public function userHelp($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'help');
      $helpArticles = $this->articlesByCategory();
			return view('user.pages.help', compact('user', 'pageSettings', 'helpArticles') );
		}

    public function userHelpDetails(Request $request, $username, $topic)
    {
      $user = $this->getUserOrFail($username);
      $pageSettings = $this->getPageSettings($user, 'help');
      $helpArticles = $this->articlesByCategory();
      return view('user.pages.help-details', ['topic' => $topic, 'user' => $user, 'pageSettings' => $pageSettings, 'helpArticles' => $helpArticles]);
    }

    public function articlesByCategory()
    {
      $helps = Help::with(['user', 'category'])
        ->where('is_published', 1)
        ->orderBy('created_at', 'desc')
        ->get();

      $helpArticles = [];

      foreach ($helps as $key => $help) {
        if(!isset($helpArticles[$help->category->category_name])) $helpArticles[$help->category->category_name] = [];
        $helpArticles[$help->category->category_name][] = [
          'id' => $help->id,
          'title' => $help->title,
          'body' => $help->body
        ];
      }

      return $helpArticles;
    }

		public function userRoadmap($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'roadmap');
			return view('user.pages.roadmap', compact('user', 'pageSettings') );
		}


		public function userChangelog($username)
		{
			$user = $this->getUserOrFail($username);
			$pageSettings = $this->getPageSettings($user, 'changelog');
      $logs = ChangeLog::with(['user'])
        ->where('is_released', 1)
        ->orderBy('released_at', 'desc')
        ->get();
      $logsGroupByDate = [];
      foreach ($logs as $key => $log) {
        $date = $log->released_at->format('F jS, Y');
        if(!array_key_exists($date, $logsGroupByDate)) $logsGroupByDate[$date] = [];
        $logsGroupByDate[$date][] = $log;
      }

			return view('user.pages.changelog', compact('user', 'pageSettings', 'logs', 'logsGroupByDate'));
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
