<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>
	
	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	
	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
	
	<!-- Styles -->
	<link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css"> {{-- Use your base theme CSS --}}
	{{-- Bootstrap Toggle CSS (used in changelog index) --}}
	<link href="/assets/vendor/bootstrap5-toggle-5.1.2/css/bootstrap5-toggle.min.css" rel="stylesheet">
	{{-- EditorMD CSS (used in changelog create/edit) --}}
	<link rel="stylesheet" href="/editormd/css/editormd.css" />
	
	{{-- Custom Admin Layout Styles --}}
	<style>
      body {
          display: flex;
          min-height: 100vh;
          overflow-x: hidden; /* Prevent horizontal scroll */
          font-family: 'Inter', sans-serif;
          background-color: var(--bs-body-bg); /* Ensure body background matches theme */
      }
      #sidebar {
          width: 260px;
          min-width: 260px;
          max-width: 260px;
          transition: all 0.3s;
          position: fixed;
          top: 0;
          left: 0;
          height: 100vh;
          z-index: 1030; /* Ensure sidebar is above content */
          overflow-y: auto;
          padding-bottom: 70px; /* Space for bottom user section */
          background-color: var(--bs-tertiary-bg); /* Explicit background */
          border-right: 1px solid var(--bs-border-color);
      }
      #main-content {
          flex-grow: 1;
          padding-left: 260px; /* Match sidebar width */
          transition: all 0.3s;
          min-width: 0; /* Important for flexbox wrapping */
          background-color: var(--bs-body-bg); /* Explicit background */
      }
      .sidebar-logo {
          padding: 1.25rem 1.5rem;
          font-size: 1.5rem;
          font-weight: 600;
          color: var(--bs-emphasis-color); /* Use theme color */
          border-bottom: 1px solid var(--bs-border-color);
      }
      html[data-bs-theme="dark"] .sidebar-logo {
          color: var(--bs-emphasis-color); /* Adjust if needed for dark */
      }
      .sidebar-nav .nav-link {
          display: flex;
          align-items: center;
          padding: 0.75rem 1.5rem;
          font-size: 0.95rem;
          color: var(--bs-secondary-color); /* Adjusted for better contrast */
          border-left: 3px solid transparent;
      }
      html[data-bs-theme="dark"] .sidebar-nav .nav-link {
          color: var(--bs-secondary-color);
      }
      .sidebar-nav .nav-link i {
          margin-right: 0.75rem;
          font-size: 1.1rem;
          width: 20px; /* Ensure icons align */
          text-align: center;
          color: var(--bs-secondary-color); /* Icon color */
      }
      .sidebar-nav .nav-link:hover {
          color: var(--bs-primary);
          background-color: var(--bs-tertiary-bg); /* Use tertiary for hover */
          border-left-color: var(--bs-primary);
      }
      .sidebar-nav .nav-link:hover i {
          color: var(--bs-primary);
      }
      html[data-bs-theme="dark"] .sidebar-nav .nav-link:hover {
          color: var(--bs-primary);
          background-color: var(--bs-gray-800); /* Darker hover */
      }
      .sidebar-nav .nav-link.active {
          color: var(--bs-primary);
          background-color: var(--bs-gray-300);
          border-left-color: var(--bs-primary);
          font-weight: 500;
      }
      .sidebar-nav .nav-link.active i {
          color: var(--bs-primary);
      }

      html[data-bs-theme="dark"] .sidebar-nav .nav-link i {
          color: var(--bs-gray-800);
      }

      html[data-bs-theme="dark"] .sidebar-nav .nav-link.active i {
          color: var(--bs-gray-800);
      }

      html[data-bs-theme="dark"] .sidebar-nav .nav-link.active {
          background-color: var(--bs-gray-300); /* Darker active */
		      color: var(--bs-gray-800);
      }

      html[data-bs-theme="dark"] .sidebar-nav .nav-link:hover {
					background-color: var(--bs-gray-400) !important;
		      color: var(--bs-gray-700);
			}

      .sidebar-heading {
          padding: 0.5rem 1.5rem;
          font-size: 0.75rem;
          font-weight: 600;
          color: var(--bs-secondary-color); /* Adjusted */
          text-transform: uppercase;
          letter-spacing: 0.05em;
          margin-top: 1rem;
      }
      html[data-bs-theme="dark"] .sidebar-heading {
          color: var(--bs-secondary-color);
      }
      .sidebar-bottom {
          position: fixed;
          bottom: 0;
          left: 0;
          width: 260px; /* Match sidebar width */
          border-top: 1px solid var(--bs-border-color);
          padding: 0.75rem 1.5rem;
          background-color: inherit; /* Inherit sidebar background */
          z-index: 1031;
      }
      .sidebar-bottom .nav-link {
          padding: 0.5rem 0;
          color: var(--bs-secondary-color);
      }
      html[data-bs-theme="dark"] .sidebar-bottom .nav-link {
          color: var(--bs-secondary-color);
      }
      .sidebar-bottom .nav-link:hover {
          color: var(--bs-primary);
      }
      .sidebar-bottom .avatar-img {
          width: 32px;
          height: 32px;
      }
      .top-navbar {
          border-bottom: 1px solid var(--bs-border-color);
          position: sticky;
          top: 0;
          z-index: 1020; /* Below sidebar */
          background-color: var(--bs-body-bg); /* Ensure it has background */
          min-height: 61px; /* Match sidebar logo height */
      }
      .breadcrumb {
          margin-bottom: 0;
          align-items: center;
      }
      .breadcrumb-item {
          font-size: 1.25rem;
          font-weight: 600;
          color: var(--bs-emphasis-color); /* Use emphasis color */
      }
      .breadcrumb-item a {
          color: inherit;
          text-decoration: none;
      }
      .breadcrumb-item.active {
          color: var(--bs-secondary-color); /* Use secondary color */
      }
      /* Ensure content area has some padding */
      #main-content > main {
          padding: 1.5rem;
      }
      /* Adjustments for SimpleMDE/EditorMD in dark mode */
      html[data-bs-theme="dark"] .editor-toolbar a {
          color: #adb5bd !important; /* Lighter gray for icons */
      }
      html[data-bs-theme="dark"] .editor-toolbar a:hover {
          background: #495057;
          border-color: #495057;
      }
      html[data-bs-theme="dark"] .cm-s-easymde .CodeMirror-cursor,
      html[data-bs-theme="dark"] .cm-s-default .CodeMirror-cursor { /* Added default */
          border-left: 1px solid #ced4da;
      }
      html[data-bs-theme="dark"] .CodeMirror {
          background-color: #212529; /* Darker background for editor */
          color: #f8f9fa; /* Light text */
          border-color: #495057;
      }
      html[data-bs-theme="dark"] .editor-preview,
      html[data-bs-theme="dark"] .editor-preview-side {
          background-color: #2b3035; /* Slightly lighter preview */
          color: #f8f9fa;
          border-color: #495057;
      }
      html[data-bs-theme="dark"] .editormd-preview-theme-dark {
          background-color: #2b3035 !important;
          color: #f8f9fa !important;
      }
      html[data-bs-theme="dark"] .editormd-preview-theme-dark h1,
      html[data-bs-theme="dark"] .editormd-preview-theme-dark h2,
      html[data-bs-theme="dark"] .editormd-preview-theme-dark h3,
      html[data-bs-theme="dark"] .editormd-preview-theme-dark h4,
      html[data-bs-theme="dark"] .editormd-preview-theme-dark h5,
      html[data-bs-theme="dark"] .editormd-preview-theme-dark h6 {
          color: #f8f9fa !important;
      }
      html[data-bs-theme="dark"] .editormd-preview-theme-dark a {
          color: var(--bs-link-color) !important;
      }
      html[data-bs-theme="dark"] .editormd-preview-theme-dark code {
          background-color: #495057 !important;
          color: #f8f9fa !important;
      }
      html[data-bs-theme="dark"] .editormd-preview-theme-dark pre {
          background-color: #212529 !important;
      }
      html[data-bs-theme="dark"] .editormd-preview-theme-dark blockquote {
          border-left: 4px solid #495057 !important;
          color: #adb5bd !important;
      }
      html[data-bs-theme="dark"] .editormd-preview-theme-dark table th,
      html[data-bs-theme="dark"] .editormd-preview-theme-dark table td {
          border: 1px solid #495057 !important;
      }
      html[data-bs-theme="dark"] .editormd-preview-theme-dark table tr:nth-child(2n) {
          background-color: #2b3035 !important;
      }

      /* Style for sub-items under Settings */
      .sidebar-submenu .nav-link {
          padding-left: 3.5rem; /* Indent sub-items */
          font-size: 0.9rem;
          color: var(--bs-secondary-color); /* Match regular links */
      }
      .sidebar-submenu .nav-link:hover {
          color: var(--bs-primary);
          background-color: var(--bs-tertiary-bg); /* Match regular hover */
          border-left-color: transparent; /* No border for subitems */
      }
      html[data-bs-theme="dark"] .sidebar-submenu .nav-link:hover {
          background-color: var(--bs-gray-800);
      }
      .sidebar-submenu .nav-link.active {
          font-weight: 500;
          color: var(--bs-primary); /* Ensure active sub-item is highlighted */
          background-color: transparent; /* No background for active subitem */
          border-left-color: transparent; /* No border */
      }
      html[data-bs-theme="dark"] .sidebar-submenu .nav-link.active {
          color: var(--bs-primary);
          background-color: transparent;
      }
      /* Ensure parent Settings link stays visually active when submenu is open/active */
      .sidebar-nav > .nav-item > .nav-link[aria-expanded="true"] {
          color: var(--bs-primary);
          background-color: var(--bs-tertiary-bg);
      }
      html[data-bs-theme="dark"] .sidebar-nav > .nav-item > .nav-link[aria-expanded="true"] {
          background-color: var(--bs-gray-800);
      }
      .sidebar-nav > .nav-item > .nav-link[aria-expanded="true"] i {
          color: var(--bs-primary);
      }
      /* Fix label color in dark mode for specific editors */
      html[data-bs-theme="dark"] label[for="title"],
      html[data-bs-theme="dark"] label[for="body"] {
          color: var(--bs-body-color) !important;
      }
	</style>
	
	<!-- Scripts -->
	<script src="/assets/js/core/jquery.min.js"></script> {{-- Ensure jQuery is loaded first --}}
	<script>
		// Dark mode switcher logic
		const storedTheme = localStorage.getItem('theme')
		const getPreferredTheme = () => {
			if (storedTheme) {
				return storedTheme
			}
			// Default to light if no preference or system preference
			return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
		}
		const setTheme = function (theme) {
			if (theme === 'auto') {
				theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
			}
			document.documentElement.setAttribute('data-bs-theme', theme)
		}
		
		setTheme(getPreferredTheme())
		
		window.addEventListener('DOMContentLoaded', () => {
			const modeSwitcher = document.getElementById('modeSwitcher');
			if (modeSwitcher) {
				const lightIcon = modeSwitcher.querySelector('.bi-sun');
				const darkIcon = modeSwitcher.querySelector('.bi-moon-stars');
				
				const updateIcon = (theme) => {
					if (theme === 'dark') {
						lightIcon?.classList.remove('d-none');
						darkIcon?.classList.add('d-none');
					} else {
						lightIcon?.classList.add('d-none');
						darkIcon?.classList.remove('d-none');
					}
				}
				
				updateIcon(getPreferredTheme()); // Set initial icon
				
				modeSwitcher.addEventListener('click', () => {
					const currentTheme = document.documentElement.getAttribute('data-bs-theme');
					const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
					localStorage.setItem('theme', newTheme);
					setTheme(newTheme);
					updateIcon(newTheme);
				});
			}
			
			// Optional: Update icon if system preference changes and theme is 'auto'
			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
				if (!storedTheme || storedTheme === 'auto') {
					const newSystemTheme = getPreferredTheme();
					setTheme(newSystemTheme);
					updateIcon(newSystemTheme);
				}
			})
		});
	</script>
</head>
<body>
<!-- Sidebar START -->
<nav id="sidebar">
	<a href="{{ route('landing-page') }}" class="sidebar-logo d-flex align-items-center text-decoration-none">
		<img style="width: 40px; height: 40px;" src="/images/logo.png" alt="logo">
		<span>{{ config('app.name', 'App') }}</span>
	</a>
	
	<ul class="nav flex-column sidebar-nav pt-3">
		{{-- Main Navigation --}}
		<li class="nav-item">
			<a class="nav-link ps-2 {{ Request::routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">
				<i class="bi bi-file-text"></i> Articles
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link ps-2 {{ Request::routeIs('changelogs.*') ? 'active' : '' }}" href="{{ route('changelogs.index') }}">
				<i class="bi bi-list-check"></i> Changelog
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link ps-2 {{ Request::routeIs('feedback.*') ? 'active' : '' }}" href="{{ route('feedback.index') }}">
				<i class="bi bi-chat-left-dots"></i> Feedback
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link ps-2 {{ Request::routeIs('roadmap.*') ? 'active' : '' }}" href="{{ route('roadmap.index') }}">
				<i class="bi bi-signpost-split"></i> Roadmap
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link ps-2 {{ Request::routeIs('knowledgebase.*') ? 'active' : '' }}" href="{{ route('knowledgebase.index') }}">
				<i class="bi bi-book"></i> Knowledge Base
			</a>
		</li>
		
		{{-- Resources/Settings Section --}}
		<li class="sidebar-heading">Resources</li>
		
		<li class="nav-item">
			<a class="nav-link ps-2 {{ Request::routeIs('site-help-page') ? 'active' : '' }}" href="{{ route('site-help-page') }}" target="_blank"> {{-- Open help in new tab? --}}
				<i class="bi bi-question-circle"></i> Help Center
			</a>
		</li>
	
	</ul>
	
	<!-- Sidebar Bottom: Settings, User Info & Mode Switch -->
	<div class="sidebar-bottom">
		<ul class="nav flex-column mb-2">
			<li class="nav-item">
				<a class="nav-link ps-2 {{ Request::routeIs('settings.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#settingsSubmenu" role="button" aria-expanded="{{ Request::routeIs('settings.*') ? 'true' : 'false' }}" aria-controls="settingsSubmenu">
					<i class="bi bi-gear"></i> Settings
				</a>
				<div class="collapse {{ Request::routeIs('settings.*') ? 'show' : '' }}" id="settingsSubmenu">
					<ul class="nav flex-column sidebar-submenu">
						<li class="nav-item">
							<a class="nav-link ps-2 {{ Request::routeIs('settings.account') ? 'active' : '' }}" href="{{ route('settings.account') }}">
								Account
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link ps-2 {{ Request::routeIs('settings.pages') ? 'active' : '' }}" href="{{ route('settings.pages') }}">
								Page Settings
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link ps-2 {{ Request::routeIs('settings.languages') ? 'active' : '' }}" href="{{ route('settings.languages') }}">
								Languages
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link ps-2 {{ Request::routeIs('settings.categories') ? 'active' : '' }}" href="{{ route('settings.categories') }}">
								Categories
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link ps-2 {{ Request::routeIs('settings.images') ? 'active' : '' }}" href="{{ route('settings.images') }}">
								Images
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link ps-2 {{ Request::routeIs('settings.close-account') ? 'active' : '' }}" href="{{ route('settings.close-account') }}">
								Close Account
							</a>
						</li>
					</ul>
				</div>
			</li>
		</ul>
		<div class="d-flex align-items-center justify-content-between pt-2 border-top">
			@auth
				<a href="{{ route('settings.account') }}" class="d-flex align-items-center text-decoration-none text-body" title="{{ Auth::user()->name }}">
					<img class="avatar-img rounded-circle me-2" src="{{ !empty(Auth::user()->avatar) ? Storage::url(Auth::user()->avatar) : '/assets/images/avatar/placeholder.jpg' }}" alt="avatar">
					<span class="text-truncate" style="max-width: 150px;">{{ Auth::user()->name }}</span>
				</a>
			@endauth
			
			<button type="button" class="btn btn-outline-secondary btn-sm border-0 p-1" id="modeSwitcher" title="Toggle theme">
				<i class="bi bi-sun d-none fs-6" style="color:#ccc;"></i> <!-- Sun icon for dark mode -->
				<i class="bi bi-moon-stars fs-6"></i> <!-- Moon icon for light mode -->
			</button>
		</div>
	</div>
</nav>
<!-- Sidebar END -->

<!-- Main Content START -->
<div id="main-content">
	<!-- Top Navbar START -->
	<nav class="navbar navbar-expand-lg top-navbar px-3 py-2">
		<div class="container-fluid">
			<!-- Breadcrumb / Page Title -->
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					{{-- Dynamic Page Title based on @yield('page-title') --}}
					<li class="breadcrumb-item active" aria-current="page">@yield('page-title', View::getSection('title', 'Dashboard'))</li>
				</ol>
			</nav>
			
			<!-- Top Bar Actions (Search, Buttons etc.) -->
			<div class="d-flex align-items-center ms-auto">
				@yield('top-bar-search') {{-- Placeholder for search --}}
				
				@yield('top-bar-actions') {{-- Placeholder for buttons like "New Changelog" --}}
				
				{{-- Example: Logout Dropdown (can replace simple button) --}}
				{{-- <div class="dropdown ms-3">
						<button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="profileDropdownTop" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="bi bi-person"></i>
						</button>
						<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdownTop">
								<li><a class="dropdown-item" href="{{ route('settings.account') }}">Profile</a></li>
								<li><hr class="dropdown-divider"></li>
								<li>
										<form method="POST" action="{{ route('logout') }}">
												@csrf
												<button type="submit" class="dropdown-item text-danger">Logout</button>
										</form>
								</li>
						</ul>
				</div> --}}
			</div>
		</div>
	</nav>
	<!-- Top Navbar END -->
	
	<!-- Main Content Area -->
	<main>
		{{-- Session Messages --}}
		@if (session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif
		@if (session('error'))
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				{{ session('error') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif
		@if ($errors->any())
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<h6 class="alert-heading">Errors Found:</h6>
				<ul class="mb-0"> {{-- Removed default list padding/margin --}}
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif
		{{-- Container for JS Notifications (like in settings) --}}
		<div id="alertContainer"></div>
		
		@yield('content')
	</main>
	
	{{-- Optional Footer within main content --}}
	{{-- <footer class="mt-auto px-3 py-2 border-top bg-body-tertiary">
			 <p class="text-center text-muted small mb-0">© {{ date('Y') }} {{ config('app.name') }}</p>
	</footer> --}}

</div>
<!-- Main Content END -->

<!-- Core JS -->
<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
{{-- Bootstrap Toggle JS (used in changelog index) --}}
<script src="/assets/vendor/bootstrap5-toggle-5.1.2/js/bootstrap5-toggle.jquery.min.js"></script>
{{-- EditorMD JS (used in changelog create/edit) --}}
<script src="/editormd/editormd.min.js"></script>
<script src="/editormd/languages/en.js"></script> {{-- Or your preferred language --}}

{{-- Custom Admin JS (if any) --}}
<script>
	// Function for JS notifications used in settings pages
	function showNotification(message, type = 'danger') {
		const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            `;
		const container = document.getElementById('alertContainer');
		if (container) {
			container.innerHTML = alertHtml;
			setTimeout(() => {
				const alertElement = container.querySelector('.alert');
				if (alertElement) {
					const bsAlert = bootstrap.Alert.getOrCreateInstance(alertElement);
					if (bsAlert) {
						bsAlert.close();
					}
				}
			}, 5000);
		} else {
			console.error("Element with ID 'alertContainer' not found.");
		}
	}
	
	// Initialize tooltips
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	})
	
	// Initialize Bootstrap Toggles if present on the page
	$(document).ready(function() {
		// Use a more specific selector if needed
		$('.statusToggle').bootstrapToggle();
	});
	
	// Function to initialize EditorMD, considering dark mode
	function initializeEditorMD(elementId) {
		const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
		const editor = editormd(elementId, {
			width: "100%",
			height: "550px", // Adjust height as needed
			path: "/editormd/lib/",
			theme: isDarkMode ? "dark" : "default",
			previewTheme: isDarkMode ? "dark" : "default",
			editorTheme: isDarkMode ? "pastel-on-dark" : "default", // Or another dark editor theme
			markdown: $(`#${elementId} > textarea`).val(), // Get initial content
			codeFold: true,
			saveHTMLToTextarea: true, // Saves HTML based preview to the textarea (useful for some cases)
			searchReplace: true,
			htmlDecode: "style,script,iframe|on*", // Decode tags
			emoji: true,
			taskList: true,
			tocm: true, // Using [TOCM]
			tex: true, // LaTeX formulas
			flowChart: true, // Flowchart
			sequenceDiagram: true, // Sequence diagram
			// imageUpload: true, // Enable if you implement upload logic
			// imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
			// imageUploadURL: "./php/upload.php", // Your upload handler URL
			onchange: function() {
				// Optional: Sync textarea content if needed immediately
				// this.sync();
			}
		});
		return editor;
	}

</script>

@stack('scripts') {{-- Allow pages to push their specific scripts --}}

</body>
</html>
