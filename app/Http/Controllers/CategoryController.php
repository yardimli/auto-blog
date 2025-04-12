<?php

	namespace App\Http\Controllers;

	use App\Models\Category;
	use Illuminate\Http\Request;
	use Illuminate\Support\Str;

	class CategoryController extends Controller
	{
		public function index()
		{
			$categories = Category::with(['language', 'parent'])->get();
			return response()->json($categories);
		}

		public function store(Request $request)
		{
			$validated = $request->validate([
				'language_id' => 'required|exists:languages,id',
				'category_name' => 'required|string|max:255',
				'parent_id' => 'nullable|exists:categories,id',
				'category_description' => 'nullable|string'
			]);

      $validated['user_id'] = auth()->id();
			$validated['category_slug'] = Str::slug($validated['category_name']);
			$category = Category::create($validated);

			if ($request->wantsJson()) {
				return response()->json($category, 201);
			}

			return redirect()->route('settings.categories')
				->with('success', __('default.Category added successfully'));
		}

		public function edit(Category $category)
		{
			return response()->json($category);
		}

		public function update(Request $request, Category $category)
		{
			$validated = $request->validate([
				'language_id' => 'required|exists:languages,id',
				'category_name' => 'required|string|max:255',
				'parent_id' => 'nullable|exists:categories,id',
				'category_description' => 'nullable|string'
			]);

			// Prevent category from being its own parent
			if ($validated['parent_id'] == $category->id) {
				return response()->json([
					'message' => 'A category cannot be its own parent'
				], 422);
			}

			$validated['category_slug'] = Str::slug($validated['category_name']);

			$category->update($validated);

			return redirect()->route('settings.categories')
				->with('success', __('default.Category updated successfully'));
		}

		public function destroy(Category $category)
		{
			// Check if category has children
			if ($category->children()->count() > 0) {
				return response()->json([
					'message' =>  __('default.Cannot delete category with child categories')
				], 422);
			}

			// Check if category has associated articles
			if ($category->articles()->count() > 0) {
				return response()->json([
					'message' =>  __('default.Cannot delete category with associated articles')
				], 422);
			}

			$category->delete();
			return response()->json(null, 204);
		}
	}
