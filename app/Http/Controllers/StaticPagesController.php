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

	class StaticPagesController extends Controller
	{

		//-------------------------------------------------------------------------
		// Index
		public function index(Request $request)
		{

			return view("user.index");

		}

		public function landing(Request $request)
		{
			return view('landing.landing');
		}

		public function about(Request $request)
		{
			return view('user.about');
		}

		public function faq(Request $request)
		{
			return view("user.faq");
		}

		public function onboarding(Request $request)
		{
			return view('user.onboarding');
		}

		public function help(Request $request)
		{
			return view('helps.site-help');
		}

		public function helpDetails(Request $request, $topic)
		{
			return view('helps.site-help-details', ['topic' => $topic]);
		}

		public function contact_us(Request $request)
		{
			return view("user.contact-us");

		}

		public function privacy(Request $request)
		{
			return view('user.privacy');
		}

		public function terms(Request $request)
		{
			return view('user.terms');
		}

		public function changeLog(Request $request)
		{
			return view('user.change-log');
		}

		//------------------------------------------------------------------------------

	}
