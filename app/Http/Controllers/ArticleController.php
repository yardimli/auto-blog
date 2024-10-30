<?php

	namespace App\Http\Controllers;

	use App\Models\Article;
	use App\Models\Category;
	use App\Models\Language;
	use App\Models\Image;
	use Illuminate\Http\Request;
	use Illuminate\Support\Str;

	class ArticleController extends Controller
	{
		public function index()
		{
			$languages = Language::where('active', true)->get();
			$categories = Category::where('user_id', auth()->id())->get();

			$articles = Article::with(['language', 'categories', 'featuredImage'])
				->orderBy('created_at', 'desc')
				->paginate(10);
			return view('user.articles', compact('articles', 'languages', 'categories'));
		}

		public function create()
		{
			$languages = Language::where('active', true)->get();
			$categories = Category::all();
			return view('user.article', compact('languages', 'categories'));
		}

		public function store(Request $request)
		{
			$validated = $request->validate([
				'language_id' => 'required|exists:languages,id',
				'title' => 'required|max:255',
				'subtitle' => 'nullable|max:255',
				'body' => 'required',
				'meta_description' => 'nullable|max:255',
				'short_description' => 'nullable|max:500',
				'featured_image_id' => 'nullable|exists:images,id',
				'categories' => 'nullable|array',
				'categories.*' => 'exists:categories,id',
				'is_published' => 'boolean',
				'posted_at' => 'nullable|date'
			]);

			// Add user_id to the validated data
			$validated['user_id'] = auth()->id();

			// Set default values if not provided
			$validated['is_published'] = $request->has('is_published');
			$validated['posted_at'] = $validated['posted_at'] ?? now();

			$article = Article::create($validated);

			if (isset($validated['categories'])) {
				$article->categories()->sync($validated['categories']);
			}

			return redirect()->route('articles.index')
				->with('success', __('default.Article created successfully.'));
		}

		public function edit(Article $article)
		{
			$languages = Language::where('active', true)->get();
			$categories = Category::all();
			return view('user.article', compact('article', 'languages', 'categories'));
		}

		public function update(Request $request, Article $article)
		{
			$validated = $request->validate([
				'language_id' => 'required|exists:languages,id',
				'title' => 'required|max:255',
				'subtitle' => 'nullable|max:255',
				'body' => 'required',
				'meta_description' => 'nullable|max:255',
				'short_description' => 'nullable|max:500',
				'featured_image_id' => 'nullable|exists:images,id',
				'categories' => 'nullable|array',
				'categories.*' => 'exists:categories,id',
				'is_published' => 'boolean',
				'posted_at' => 'nullable|date'
			]);

			// Set is_published based on checkbox
			$validated['is_published'] = $request->has('is_published');

			$article->update($validated);

			if (isset($validated['categories'])) {
				$article->categories()->sync($validated['categories']);
			}

			return redirect()->route('articles.index')
				->with('success', __('default.Article updated successfully.'));
		}

		public function destroy(Article $article)
		{
			$article->categories()->detach();
			$article->delete();


			return redirect()->route('articles.index')
				->with('success', __('default.Article deleted successfully.'));
		}

		// Add method to handle image loading for the modal
		public function getImages()
		{
			$images = Image::where('user_id', auth()->id())
				->orderBy('created_at', 'desc')
				->paginate(9);  // 9 images per page

			return response()->json([
				'images' => $images,
				'pagination' => [
					'current_page' => $images->currentPage(),
					'last_page' => $images->lastPage(),
					'per_page' => $images->perPage(),
					'total' => $images->total()
				]
			]);
		}
	}
