<!DOCTYPE html>
{{-- Ensure $user representing the profile owner is passed to this layout --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> {{-- Use app()->getLocale() --}}
<head>
	<title>@yield('title', 'Home')</title> {{-- Use profile owner's name or app name --}}
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	{{-- Consider dynamic author/description based on $user or pageSettings --}}
	<meta name="author" content="{{ $user->company_name ?? $user->name ?? 'App Author' }}">
	<meta name="description" content="@yield('description', $user->about_me ?? 'User Profile Page')">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<script src="/assets/js/core/jquery.min.js"></script>
	
	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	
	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
	@stack('google-fonts')
	
	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/choices/css/choices.min.css">
	
	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/custom.css">

</head>

@php
	use Carbon\Carbon;
	// Ensure $user is available in this scope. It MUST be passed from the controller.
	// If $user might not be set (e.g., on general site pages not tied to a user profile),
	// you'll need default logic or ensure this layout is only used where $user is guaranteed.
	if (!isset($user)) {
			// Handle cases where $user might not be set - perhaps use Auth::user() or a default?
			// This depends on how/where this layout is used. For user profile pages (@username/...),
			// $user *should* always be set by the controller.
			// For now, we assume $user is always passed correctly for routes using this layout.
			// Log::warning('The $user variable was not set in company-app.blade.php layout.');
			 // $user = Auth::user(); // Example fallback - BE CAREFUL, might not be desired.
	}
@endphp

<script>
	// <!-- Dark mode -->
	const storedTheme = localStorage.getItem('theme')
	
	const getPreferredTheme = () => {
		if (storedTheme) {
			return storedTheme
		}
		return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
	}
	
	const setTheme = function (theme) {
		if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
			document.documentElement.setAttribute('data-bs-theme', 'dark')
		} else {
			document.documentElement.setAttribute('data-bs-theme', theme)
		}
	}
	
	setTheme(getPreferredTheme())
	
	window.addEventListener('DOMContentLoaded', () => {
		var el = document.querySelector('.theme-icon-active');
		if(el != 'undefined' && el != null) {
			const showActiveTheme = theme => {
				const activeThemeIcon = document.querySelector('.theme-icon-active use')
				const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
				const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')
				
				document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
					element.classList.remove('active')
				})
				
				btnToActive.classList.add('active')
				activeThemeIcon.setAttribute('href', svgOfActiveBtn)
			}
			
			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
				if (storedTheme !== 'light' || storedTheme !== 'dark') {
					setTheme(getPreferredTheme())
				}
			})
			
			showActiveTheme(getPreferredTheme())
			
			document.querySelectorAll('[data-bs-theme-value]')
				.forEach(toggle => {
					toggle.addEventListener('click', () => {
						const theme = toggle.getAttribute('data-bs-theme-value')
						localStorage.setItem('theme', theme)
						setTheme(theme)
						showActiveTheme(theme)
					})
				})
		}
		
		// --- Mode Switcher Button Logic ---
		const modeSwitcher = document.getElementById('modeSwitcher');
		if (modeSwitcher) { // Check if element exists
			const lightModeIcon = document.getElementById('lightModeIcon');
			const darkModeIcon = document.getElementById('darkModeIcon');
			
			// Set initial icon state
			if (getPreferredTheme() === 'dark') {
				lightModeIcon?.classList.remove('d-none'); // Add safety checks
				darkModeIcon?.classList.add('d-none');
			} else {
				lightModeIcon?.classList.add('d-none');
				darkModeIcon?.classList.remove('d-none');
			}
			
			modeSwitcher.addEventListener('click', () => {
				const currentTheme = document.documentElement.getAttribute('data-bs-theme');
				const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
				
				// Update theme
				localStorage.setItem('theme', newTheme);
				setTheme(newTheme);
				
				// Toggle icons (with safety checks)
				lightModeIcon?.classList.toggle('d-none');
				darkModeIcon?.classList.toggle('d-none');
			});
		}
		// --- End Mode Switcher Button Logic ---
	});
</script>

{{-- Removed check for Auth::check() as $user should be passed from controller --}}
{{-- @if (Auth::check())
    @php $found_package = false; @endphp
@endif --}}

<body>

<!-- ======================= Header START -->
<header class="navbar-light fixed-top header-static bg-mode">
	
	<!-- Logo Nav START -->
	<nav class="navbar navbar-expand-lg">
		<div class="container">
			<!-- Logo START -->
			{{-- Use the $user variable passed from the controller for the logo link and image --}}
			<a class="navbar-brand" href="{{ isset($user) ? route('user.home', $user->username) : route('landing-page') }}">
				{{-- Use the profile_photo_url accessor from the User model ($user represents the profile owner) --}}
				@if(isset($user))
					<img class="light-mode-item navbar-brand-item" src="{{ $user->profile_photo_url }}" alt="{{ $user->name ?? 'User' }} logo" style="max-height: 40px;"> {{-- Added style --}}
					<img class="dark-mode-item navbar-brand-item" src="{{ $user->profile_photo_url }}" alt="{{ $user->name ?? 'User' }} logo" style="max-height: 40px;"> {{-- Added style --}}
				@else
					{{-- Fallback if $user isn't set (e.g., general site logo) --}}
					<img class="light-mode-item navbar-brand-item" src="/assets/images/logo-light.svg" alt="Site Logo"> {{-- Example fallback --}}
					<img class="dark-mode-item navbar-brand-item" src="/assets/images/logo-dark.svg" alt="Site Logo"> {{-- Example fallback --}}
				@endif
			</a>
			<!-- Logo END -->
			
			<!-- Responsive navbar toggler -->
			<button class="navbar-toggler ms-auto icon-md btn btn-light p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-animation">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>
			
			<!-- Main navbar START -->
			<div class="collapse navbar-collapse" id="navbarCollapse">
				{{-- Ensure $user is available for these links. If not, hide/disable them or provide fallbacks --}}
				@if(isset($user))
					<ul class="navbar-nav navbar-nav-scroll ms-auto">
						<!-- Nav item -->
						<li class="nav-item">
							{{-- Links correctly use $user passed from controller --}}
							<a class="nav-link {{ Request::is('@' . $user->username) ? 'active' : '' }}" href="{{ route('user.home', $user->username) }}">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link {{ Request::is('@' . $user->username . '/blog*') ? 'active' : '' }}" href="{{ route('user.blog', $user->username) }}">Blog</a>
						</li>
						<li class="nav-item">
							<a class="nav-link {{ Request::is('@' . $user->username . '/help') ? 'active' : '' }}" href="{{ route('user.help', $user->username) }}">Help</a>
						</li>
						<li class="nav-item">
							<a class="nav-link {{ Request::is('@' . $user->username . '/roadmap') ? 'active' : '' }}" href="{{ route('user.roadmap', $user->username) }}">Roadmap</a>
						</li>
						<li class="nav-item">
							<a class="nav-link {{ Request::is('@' . $user->username . '/feedback*') ? 'active' : '' }}" href="{{ route('user.feedback.index', $user->username) }}">Feedback</a>
						</li>
						<li class="nav-item">
							<a class="nav-link {{ Request::is('@' . $user->username . '/changelog') ? 'active' : '' }}" href="{{ route('user.changelog', $user->username) }}">Change Log</a>
						</li>
					</ul>
				@endif
			</div>
			<!-- Main navbar END -->
			
			<!-- Nav right START -->
			<ul class="nav flex-nowrap align-items-center ms-sm-3 list-unstyled">
				
				<!-- Mode Switch Button START -->
				<li class="nav-item ms-2">
					<button type="button" class="nav-link icon-md btn btn-light p-0" id="modeSwitcher">
						<!-- Sun icon for dark mode -->
						<i class="bi bi-sun fs-6 d-none" id="lightModeIcon"></i>
						<!-- Moon icon for light mode -->
						<i class="bi bi-moon-stars fs-6" id="darkModeIcon"></i>
					</button>
				</li>
				<!-- Mode Switch Button END -->
				
				{{-- You might want a Login/Register or User Profile Dropdown here using Auth::user() --}}
				@auth
					<li class="nav-item ms-2 dropdown">
						<a class="nav-link btn btn-light p-0" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img class="avatar-sm rounded-circle" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
						</a>
						<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
							<li><a class="dropdown-item" href="{{ route('settings.account') }}">Settings</a></li>
							<li><hr class="dropdown-divider"></li>
							<li>
								<form method="POST" action="{{ route('logout') }}">
									@csrf
									<button type="submit" class="dropdown-item">Logout</button>
								</form>
							</li>
						</ul>
					</li>
				@else
					<li class="nav-item ms-2">
						<a href="{{ route('login') }}" class="btn btn-link">Login</a>
					</li>
					<li class="nav-item ms-2">
						<a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
					</li>
				@endauth
				{{-- End Auth Check --}}
			
			</ul>
			<!-- Nav right END -->
		</div>
	</nav>
	<!-- Logo Nav END -->
</header>
<!-- ======================= Header END -->

@yield('content')


<!-- ======================= Footer START -->
{{-- Add a footer partial or content here --}}
<footer class="bg-light pt-5 pb-4">
	<div class="container">
		<div class="row">
			<div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
				© {{ date('Y') }} {{ $user->company_name ?? $user->name ?? config('app.name') }}. All rights reserved.
			</div>
			<div class="col-md-6 text-center text-md-end">
				@if(isset($user))
					<ul class="list-inline mb-0">
						<li class="list-inline-item"><a href="{{ route('user.terms', $user->username) }}" class="text-body">Terms</a></li>
						<li class="list-inline-item"><a href="{{ route('user.privacy', $user->username) }}" class="text-body">Privacy</a></li>
						<li class="list-inline-item"><a href="{{ route('user.cookie-consent', $user->username) }}" class="text-body">Cookies</a></li>
					</ul>
				@else
					{{-- Fallback links if $user not set --}}
					<ul class="list-inline mb-0">
						<li class="list-inline-item"><a href="{{ route('terms-page') }}" class="text-body">Terms</a></li>
						<li class="list-inline-item"><a href="{{ route('privacy-page') }}" class="text-body">Privacy</a></li>
					</ul>
				@endif
			</div>
		</div>
	</div>
</footer>
<!-- ======================= Footer END -->

<!-- ======================= JS libraries, plugins and custom scripts -->
<!-- Bootstrap JS -->
<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Vendors -->
<script src="/assets/vendor/tiny-slider/tiny-slider.js"></script>
<script src="/assets/vendor/choices/js/choices.min.js"></script>

<!-- Theme Functions -->
<script src="/assets/js/functions.js"></script>

@php($title = View::getSection('title', 'Home'))

@stack('scripts')

</body>
</html>
