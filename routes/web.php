<?php

	use App\Http\Controllers\ChangeLogController;
	use App\Http\Controllers\ArticleController;
	use App\Http\Controllers\CategoryController;
	use App\Http\Controllers\ChatController;
	use App\Http\Controllers\FeedbackAdminController;
	use App\Http\Controllers\FeedbackController;
	use App\Http\Controllers\HelpController;
	use App\Http\Controllers\ImageController;
	use App\Http\Controllers\LanguageController;
	use App\Http\Controllers\LoginWithGoogleController;
	use App\Http\Controllers\RoadmapController;
	use App\Http\Controllers\StaticPagesController;
	use App\Http\Controllers\UserPagesController;
	use App\Http\Controllers\UserSettingsController;
	use App\Http\Controllers\VerifyThankYouController;
	use App\Mail\WelcomeMail;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Mail;
	use Illuminate\Support\Facades\Route;


	/*
	|--------------------------------------------------------------------------
	| Web Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register web routes for your application. These
	| routes are loaded by the RouteServiceProvider and all of them will
	| be assigned to the "web" middleware group. Make something great!
	|
	*/

	//-------------------------------------------------------------------------

	Route::get('/', [StaticPagesController::class, 'landing'])->name('landing-page');

	Route::get('/lang/home', [LanguageController::class, 'index']);
	Route::get('/lang/change', [LanguageController::class, 'change'])->name('changeLang');

	Route::get('login/google', [LoginWithGoogleController::class, 'redirectToGoogle']);
	Route::get('login/google/callback', [LoginWithGoogleController::class, 'handleGoogleCallback']);

	Route::get('/logout', [LoginWithGoogleController::class, 'logout']);

	Route::get('/verify-thank-you', [VerifyThankYouController::class, 'index'])->name('verify-thank-you')->middleware('verified');
	Route::get('/verify-thank-you-zh_TW', [VerifyThankYouController::class, 'index_zh_TW'])->name('verify-thank-you-zh_TW')->middleware('verified');

	Route::get('/privacy', [StaticPagesController::class, 'privacy'])->name('privacy-page');
	Route::get('/terms', [StaticPagesController::class, 'terms'])->name('terms-page');
	Route::get('/site-help', [StaticPagesController::class, 'help'])->name('site-help-page');
	Route::get('/site-help/{topic}', [StaticPagesController::class, 'helpDetails'])->name('site-help-details');
	Route::get('/about', [StaticPagesController::class, 'about'])->name('about-page');
	Route::get('/contact', [StaticPagesController::class, 'contact'])->name('contact-page');
	Route::get('/onboarding', [StaticPagesController::class, 'onboarding'])->name('onboarding-page');
	Route::get('/change-log', [StaticPagesController::class, 'changeLog'])->name('change-log-page');
	Route::get('/buy-packages', [UserSettingsController::class, 'buyPackages'])->name('buy-packages');

	//-------------------------------------------------------------------------

	Route::get('/buy-packages', [UserSettingsController::class, 'buyPackages'])->name('buy-packages');

	Route::get('/buy-credits-test/{id}', [PayPalController::class, 'beginTransaction'])->name('beginTransaction');
	Route::get('/buy-credits/{id}', [PayPalController::class, 'processTransaction'])->name('processTransaction');
	Route::get('/success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
	Route::get('/cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');

	Route::get('/user-profile/{username}', [StaticPagesController::class, 'userProfile'])->name('user-profile');


	//-------------------------------------------------------------------------
	Route::middleware(['auth'])->group(function () {

		Route::get('/check-llms-json', [ChatController::class, 'checkLLMsJson']);


		Route::get('/chat/sessions', [ChatController::class, 'getChatSessions']);
		Route::get('/chat/{session_id?}', [ChatController::class, 'index'])->name('chat');
		Route::post('/create-session', [ChatController::class, 'createSession'])->name('chat.create-session');
		Route::get('/chat/messages/{sessionId}', [ChatController::class, 'getChatMessages']);
		Route::post('/send-llm-prompt', [ChatController::class, 'sendLlmPrompt'])->name('send-llm-prompt');
		Route::delete('/chat/{sessionId}', [ChatController::class, 'destroy'])->name('chat.destroy');


		Route::post('/settings', [UserSettingsController::class, 'updateSettings'])->name('settings-update');

		Route::get('/settings/account', [UserSettingsController::class, 'account'])->name('settings.account');
		Route::get('/settings/languages', [UserSettingsController::class, 'languages'])->name('settings.languages');
		Route::get('/settings/categories', [UserSettingsController::class, 'categories'])->name('settings.categories');
		Route::get('/settings/images', [UserSettingsController::class, 'images'])->name('settings.images');
		Route::get('/settings/close-account', [UserSettingsController::class, 'closeAccount'])->name('settings.close-account');


		Route::post('/settings/password', [UserSettingsController::class, 'updatePassword'])->name('settings-password-update');
		Route::post('/settings/api-keys', [UserSettingsController::class, 'updateApiKeys'])->name('settings-update-api-keys');

		Route::get('/settings/pages', [UserSettingsController::class, 'pages'])->name('settings.pages');
		Route::put('/settings/pages', [UserSettingsController::class, 'updatePages'])->name('settings.pages.update');

		Route::get('/users', [UserSettingsController::class, 'admin_index'])->name('admin-index');
		Route::post('/login-as', [UserSettingsController::class, 'loginAs'])->name('users-login-as');

		Route::post('/settings/password', [UserSettingsController::class, 'updatePassword'])->name('settings-password-update');


		Route::resource('articles', ArticleController::class);

		// Language Management Routes
		Route::get('/languages', [LanguageController::class, 'index'])->name('languages.index');
		Route::post('/languages', [LanguageController::class, 'store'])->name('languages.store');
		Route::get('/languages/{language}/edit', [LanguageController::class, 'edit'])->name('languages.edit');
		Route::put('/languages/{language}', [LanguageController::class, 'update'])->name('languages.update');
		Route::delete('/languages/{language}', [LanguageController::class, 'destroy'])->name('languages.destroy');

		// Category Management Routes
		Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
		Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
		Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
		Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
		Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

		Route::get('/upload-images', [ImageController::class, 'index'])->name('upload-images.index');
		Route::post('/upload-images', [ImageController::class, 'store'])->name('upload-images.store');
		Route::put('/upload-images/{id}', [ImageController::class, 'update'])->name('upload-images.update');
		Route::delete('/upload-images/{id}', [ImageController::class, 'destroy'])->name('upload-images.destroy');

		Route::get('/image-gen/sessions', [ImageController::class, 'getImageGenSessions'])->name('image-gen-sessions');
		Route::post('/image-gen', [ImageController::class, 'makeImage'])->name('send-image-gen-prompt');
		Route::delete('/image-gen/{session_id}', [ImageController::class, 'destroyGenImage'])->name('image-gen.destroy');


		Route::prefix('articles')->group(function () {
			Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
			Route::get('/create', [ArticleController::class, 'create'])->name('articles.create');
			Route::post('/', [ArticleController::class, 'store'])->name('articles.store');
			Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
			Route::put('/{article}', [ArticleController::class, 'update'])->name('articles.update');
			Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
			Route::get('/get-images', [ArticleController::class, 'getImages'])->name('articles.get-images');
		});

		Route::prefix('feedback')->group(function () {

			Route::resource('feedback', FeedbackAdminController::class)->except(['create', 'store']);
		});

		Route::prefix('roadmap')->group(function () {
			Route::get('/', [RoadmapController::class, 'index'])->name('roadmap.index');
		});

		Route::prefix('helps')->group(function () {
			Route::get('/', [HelpController::class, 'index'])->name('helps.index');
      Route::get('/create', [HelpController::class, 'create'])->name('helps.create');
      Route::post('/store', [HelpController::class, 'store'])->name('helps.store');
      Route::get('/{help}/edit', [HelpController::class, 'edit'])->name('helps.edit');
      Route::put('{help}/update', [HelpController::class, 'update'])->name('helps.update');
      Route::delete('/{help}', [HelpController::class, 'destroy'])->name('helps.destroy');
      Route::post('/togglePublished/{help}', [HelpController::class, 'togglePublished'])->name('helps.togglePublished');
		});

		Route::prefix('changelogs')->group(function () {
			Route::get('/', [ChangeLogController::class, 'index'])->name('changelogs.index');
			Route::get('/create', [ChangeLogController::class, 'create'])->name('changelogs.create');
			Route::post('/store', [ChangeLogController::class, 'store'])->name('changelogs.store');
			Route::get('/{changelog}/edit', [ChangeLogController::class, 'edit'])->name('changelogs.edit');
			Route::put('{changelog}/update', [ChangeLogController::class, 'update'])->name('changelogs.update');
			Route::delete('/{changelog}', [ChangeLogController::class, 'destroy'])->name('changelogs.destroy');
			Route::post('/toggleReleased/{changelog}', [ChangeLogController::class, 'toggleReleased'])->name('changelogs.toggleReleased');
		});

	});

	Route::middleware(['web'])->group(function () { // Ensure session, etc. is available if needed
		// User-specific public pages
		Route::get('/@{username}', [UserPagesController::class, 'userHome'])->name('user.home');
		Route::get('/@{username}/blog', [UserPagesController::class, 'userBlog'])->name('user.blog');
		Route::get('/@{username}/blog/{slug}', [UserPagesController::class, 'userBlogArticle'])->name('user.blog.article');

		Route::get('/@{username}/roadmap', [UserPagesController::class, 'userRoadmap'])->name('user.roadmap');
		Route::get('/@{username}/changelog', [UserPagesController::class, 'userChangelog'])->name('user.changelog');
		Route::get('/@{username}/terms', [UserPagesController::class, 'userTerms'])->name('user.terms');
		Route::get('/@{username}/privacy', [UserPagesController::class, 'userPrivacy'])->name('user.privacy');
		Route::get('/@{username}/cookie-consent', [UserPagesController::class, 'userCookieConsent'])->name('user.cookie-consent');

		// Feedback Routes
		Route::get('/@{username}/feedback', [FeedbackController::class, 'index'])->name('user.feedback.index');
		Route::post('/@{username}/feedback', [FeedbackController::class, 'store'])->name('user.feedback.store');
		Route::post('/@{username}/feedback/{feedback}/vote', [FeedbackController::class, 'vote'])->name('user.feedback.vote');

		// --- NEW HELP ROUTES ---
		Route::get('/@{username}/help', [UserPagesController::class, 'userHelpIndex'])->name('user.help.index'); // Main help page
		Route::get('/@{username}/help/{category_slug}', [UserPagesController::class, 'userHelpCategory'])->name('user.help.category'); // Category article list
		Route::get('/@{username}/help/article/{help}', [UserPagesController::class, 'userHelpArticle'])->name('user.help.article'); // Single article (using route model binding)
		// --- END NEW HELP ROUTES ---

	});

	//-------------------------------------------------------------------------

	Auth::routes();
	Auth::routes(['verify' => true]);

	Route::fallback(function() { abort(404); });
