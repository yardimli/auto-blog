<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\Models\User;
	use App\Models\Language;
	use App\Models\Category;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;
	use Illuminate\Support\Facades\Validator;
	use App\Helpers\MyHelper;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Validation\Rule;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Validation\ValidationException;


	class UserSettingsController extends Controller
	{
		//-------------------------------------------------------------------------
		// Index
		public function index()
		{
		}

		public function account()
		{
			$user = auth()->user();
			return view('user.account', compact('user'));
		}

		public function languages()
		{
			$languages = Language::all();
			return view('user.languages', compact('languages'));
		}

		public function categories()
		{
			$languages = Language::all();
			$categories = Category::with(['language', 'parent'])->get();
			return view('user.categories', compact('languages', 'categories'));
		}

		public function images()
		{
			return view('user.images');
		}

		public function closeAccount()
		{
			return view('user.close_account');
		}


//-------------------------------------------------------------------------
		// Update user password
		public function updatePassword(Request $request)
		{
			// Get the authenticated user
			$user = $request->user();

			// Validate input
			$validator = Validator::make($request->all(), [
				'current_password' => ['nullable', 'string'],
				'new_password' => ['required', 'string', 'min:6', 'confirmed'],
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$current_password = $request->input('current_password') ?? '123456dummy_password';
//dd($user->password, $current_password, Hash::check($current_password, $user->password), Hash::make($current_password));
			// Check if the current password is correct
			if (!Hash::check($current_password, $user->password)) {
				throw ValidationException::withMessages([
					'current_password' => ['Current password is incorrect.'],
				]);
			}

			// Update user password
			$user->password = Hash::make($request->input('new_password'));
			$user->save();

			// Redirect back with success message
			Session::flash('success', 'Your password has been updated successfully.');
			return redirect()->back();
		}

		//-------------------------------------------------------------------------
		// privacy
		public function buyPackages(Request $request)
		{
			if (Auth::check()) {
				$checkout_starter = 'starter';
				$checkout_novella = 'novella';
				$checkout_novel = 'novel';
			} else {
				$checkout_starter = null;
				$checkout_novella = null;
				$checkout_novel = null;
			}

			return view('user.buy-packages', compact('checkout_starter', 'checkout_novella', 'checkout_novel'));
		}


		//-------------------------------------------------------------------------
		// settings


		public function editSettings(Request $request)
		{
			$user = auth()->user();
			$languages = Language::all();
			$categories = Category::with(['language', 'parent'])->get();

			return view('user.settings', compact('user', 'languages', 'categories'));
		}

// Update user settings
		public function updateSettings(Request $request)
		{
			// Get the authenticated user
			$user = $request->user();

			// Validate input
			$validator = Validator::make($request->all(), [
				'name' => ['required', 'string', 'max:255'],
				'username' => [
					'required',
					'string',
					'max:255',
					'alpha_dash',
					Rule::unique('users')->ignore($user->id),
				],
				'email' => [
					'required',
					'string',
					'email',
					'max:255',
					Rule::unique('users')->ignore($user->id),
				],
				'company_name' => ['required', 'string', 'max:255'],
				'company_description' => ['required', 'string', 'max:1000'],
				'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			if ($request->hasFile('avatar')) {
				$avatar = $request->file('avatar');
				$avatarContents = file_get_contents($avatar->getRealPath());
				$avatarName = $user->id . '.jpg';
				$avatarPath = 'public/user_avatars/' . $avatarName;
				Storage::put($avatarPath, $avatarContents);
				$user->avatar = $avatarPath;
			}

			// Update user
			$user->name = $request->input('name');
			$user->username = $request->input('username');
			$user->email = $request->input('email');
			$user->company_name = $request->input('company_name');
			$user->company_description = $request->input('company_description');
			$user->save();

			// Redirect back with success message
			Session::flash('success', 'Your settings have been updated successfully.');
			return redirect()->back();
		}

		public function updateApiKeys(Request $request)
		{
			$user = Auth::user();

			$validator = Validator::make($request->all(), [
				'openrouter_key' => 'nullable|string',
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$user->update([
				'openrouter_key' => $request->input('openrouter_key'),
			]);

			Session::flash('success', 'Your API keys have been updated successfully.');
			return redirect()->back();
		}

	}
