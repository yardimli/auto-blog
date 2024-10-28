<?php

	namespace App\Http\Controllers;

	use App\Models\Article;
	use App\Models\Category;
	use App\Models\Language;
	use Illuminate\Http\Request;
	use Illuminate\Support\Str;

	class ArticleController extends Controller
	{
		public function index()
		{
			$articles = Article::with(['language', 'categories', 'featuredImage'])
				->orderBy('created_at', 'desc')
				->paginate(10);

			return view('articles.index', compact('articles'));
		}

		public function create()
		{
			$languages = Language::where('active', true)->get();
			$categories = Category::all();
			return view('articles.create', compact('languages', 'categories'));
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

			$article = Article::create($validated);

			if (isset($validated['categories'])) {
				$article->categories()->sync($validated['categories']);
			}

			return redirect()->route('articles.index')
				->with('success', 'Article created successfully.');
		}

		public function edit(Article $article)
		{
			$languages = Language::where('active', true)->get();
			$categories = Category::all();
			return view('articles.edit', compact('article', 'languages', 'categories'));
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

			$article->update($validated);

			if (isset($validated['categories'])) {
				$article->categories()->sync($validated['categories']);
			}

			return redirect()->route('articles.index')
				->with('success', 'Article updated successfully.');
		}

		public function destroy(Article $article)
		{
			$article->categories()->detach();
			$article->delete();

			return redirect()->route('articles.index')
				->with('success', 'Article deleted successfully.');
		}
	}
