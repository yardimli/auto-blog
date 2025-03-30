<!DOCTYPE html>
<html lang="tr">
<head>
	<title>{{__('default.Books By AI')}} - @yield('title', 'Home')</title>
	
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="fictionfusion.io">
	<meta name="description"
	      content="{{__('default.Books By AI')}} - {{__('default.Boilerplate Site Tagline')}}">
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
		if (el != 'undefined' && el != null) {
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
		
		
		const modeSwitcher = document.getElementById('modeSwitcher');
		const lightModeIcon = document.getElementById('lightModeIcon');
		const darkModeIcon = document.getElementById('darkModeIcon');
		
		// Set initial icon state
		if (getPreferredTheme() === 'dark') {
			lightModeIcon.classList.remove('d-none');
			darkModeIcon.classList.add('d-none');
		} else {
			lightModeIcon.classList.add('d-none');
			darkModeIcon.classList.remove('d-none');
		}
		
		modeSwitcher.addEventListener('click', () => {
			const currentTheme = document.documentElement.getAttribute('data-bs-theme');
			const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
			
			// Update theme
			localStorage.setItem('theme', newTheme);
			setTheme(newTheme);
			
			// Toggle icons
			lightModeIcon.classList.toggle('d-none');
			darkModeIcon.classList.toggle('d-none');
		});
	});
</script>


@if (Auth::check())
	@php
		$found_package = false;
	@endphp
@endif

<body>

<!-- =======================
Header START -->
<header class="navbar-light fixed-top header-static bg-mode">
	
	<!-- Logo Nav START -->
	<nav class="navbar navbar-expand-lg">
		<div class="container">
			<!-- Logo START -->
			<a class="navbar-brand" href="{{route('landing-page')}}">
				<img class="light-mode-item navbar-brand-item" src="{{ !empty(Auth::user()->avatar) ? Storage::url(Auth::user()->avatar) : '/assets/images/avatar/01.jpg' }}" alt="logo">
				<img class="dark-mode-item navbar-brand-item" src="{{ !empty(Auth::user()->avatar) ? Storage::url(Auth::user()->avatar) : '/assets/images/avatar/01.jpg' }}" alt="logo">
			</a>
			<!-- Logo END -->
			
			<!-- Responsive navbar toggler -->
			<button class="navbar-toggler ms-auto icon-md btn btn-light p-0" type="button" data-bs-toggle="collapse"
			        data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
			        aria-label="Toggle navigation">
        <span class="navbar-toggler-animation">
          <span></span>
          <span></span>
          <span></span>
        </span>
			</button>
			
			<!-- Main navbar START -->
			<div class="collapse navbar-collapse" id="navbarCollapse">
				
				<ul class="navbar-nav navbar-nav-scroll ms-auto">
					<li class="nav-item">
					<a class="nav-link {{ Request::is($user->username) ? 'active' : '' }}"
					   href="{{ route('user.home', $user->username) }}">Home</a>
					</li>
					<li class="nav-item">
					<a class="nav-link {{ Request::is($user->username . '/blog') ? 'active' : '' }}"
					   href="{{ route('user.blog', $user->username) }}">Blog</a>
					</li>
					<li class="nav-item">
					<a class="nav-link {{ Request::is($user->username . '/help') ? 'active' : '' }}"
					   href="{{ route('user.help', $user->username) }}">Help</a>
					</li>
					<li class="nav-item">
					<a class="nav-link {{ Request::is($user->username . '/roadmap') ? 'active' : '' }}"
					   href="{{ route('user.roadmap', $user->username) }}">Roadmap</a>
					</li>
					<li class="nav-item">
					<a class="nav-link {{ Request::is($user->username . '/feedback') ? 'active' : '' }}"
					   href="{{ route('user.feedback.index', $user->username) }}">Feedback</a>
					</li>
					<li class="nav-item">
					<a class="nav-link {{ Request::is($user->username . '/changelog') ? 'active' : '' }}"
					   href="{{ route('user.changelog', $user->username) }}">Change Log</a>
					</li>
				
				
				
				
				</ul>
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
			</ul>
			</li>
			<!-- Profile START -->
			
			</ul>
			<!-- Nav right END -->
		</div>
	</nav>
	<!-- Logo Nav END -->
</header>
<!-- =======================
Header END -->


@yield('content')

<!-- =======================
JS libraries, plugins and custom scripts -->

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
