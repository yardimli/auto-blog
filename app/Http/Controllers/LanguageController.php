<?php

	namespace App\Http\Controllers;

	use App\Models\Language;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\App;

	class LanguageController extends Controller
	{
		public function change(Request $request)
		{
			App::setLocale($request->lang);
			session()->put('locale', $request->lang);
			//go to home page
			return redirect()->route('landing-page');
		}

		public function index()
		{
			$languages = Language::all();
			return response()->json($languages);
		}

		public function store(Request $request)
		{
			$validated = $request->validate([
				'language_name' => 'required|string|max:255',
				'locale' => 'required|string|max:10',
				'active' => 'sometimes|boolean'
			]);

			// Convert checkbox input to boolean
			$validated['active'] = $request->has('active');

			Language::create($validated);

			return redirect()->route('settings.languages')
				->with('success', __('default.Language added successfully'));
		}

		public function edit(Language $language)
		{
			return response()->json($language);
		}

		public function update(Request $request, Language $language)
		{
			$validated = $request->validate([
				'language_name' => 'required|string|max:255',
				'locale' => 'required|string|max:10',
				'active' => 'sometimes|boolean'
			]);

			// Convert checkbox input to boolean
			$validated['active'] = $request->has('active');

			$language->update($validated);

			return redirect()->route('settings.languages')
				->with('success', __('default.Language updated successfully'));
		}

		public function destroy(Language $language)
		{
			// Check if language has associated content
			if ($language->articles()->count() > 0 || $language->categories()->count() > 0) {
				return response()->json([
					'message' => __('default.Cannot delete language with associated content')
				], 422);
			}

			$language->delete();
			return response()->json(['message' => __('default.Language deleted successfully')], 200);
		}
	}
