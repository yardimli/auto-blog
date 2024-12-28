<?php

	namespace App\Http\Controllers;

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

		public function userBlog($username)
		{
			$user = $this->getUserOrFail($username);
			return view('user.pages.blog', compact('user'));
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
