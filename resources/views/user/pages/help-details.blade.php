@extends('layouts.company-app') {{-- Or your main user layout --}}

{{-- Use $user and $helpArticle passed from controller/view share --}}
@section('title', ($user->company_name ?? $user->name) . ' - Help: ' . $helpArticle->title)

@push('styles')
	{{-- Reuse styles or add new ones --}}
	<link rel="stylesheet" href="/editormd/css/editormd.preview.css" /> {{-- Use preview CSS --}}
	<style>
      .help-sidebar .nav-link { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
      .help-sidebar .nav-link .bi-chevron-down { transition: transform 0.3s ease; }
      .help-sidebar .nav-link[aria-expanded="true"] .bi-chevron-down { transform: rotate(-180deg); }
      .help-sidebar .collapse .nav-link { font-size: 0.85rem; color: var(--bs-secondary-color); }
      .help-sidebar .collapse .nav-link.active { font-weight: bold; color: var(--bs-primary); background-color: transparent !important; }
      .help-sidebar .collapse .nav-link:hover { color: var(--bs-primary); }

      /* Styles for the preview area */
      .editormd-preview-container {
          padding: 1rem; /* Add some padding */
          border: 1px solid var(--bs-border-color);
          border-radius: 0.375rem;
          background-color: var(--bs-body-bg); /* Match page background */
      }
      /* Ensure dark mode compatibility for preview */
      [data-bs-theme="dark"] .editormd-preview-theme-dark {
          background-color: var(--bs-tertiary-bg) !important; /* Slightly different background */
          color: var(--bs-body-color) !important;
          border-color: var(--bs-border-color-translucent) !important;
      }
      [data-bs-theme="dark"] .editormd-preview-theme-dark h1,
      [data-bs-theme="dark"] .editormd-preview-theme-dark h2,
      [data-bs-theme="dark"] .editormd-preview-theme-dark h3,
      [data-bs-theme="dark"] .editormd-preview-theme-dark h4,
      [data-bs-theme="dark"] .editormd-preview-theme-dark h5,
      [data-bs-theme="dark"] .editormd-preview-theme-dark h6 {
          color: var(--bs-emphasis-color) !important;
          border-bottom-color: var(--bs-border-color) !important;
      }
      [data-bs-theme="dark"] .editormd-preview-theme-dark a { color: var(--bs-link-color) !important; }
      [data-bs-theme="dark"] .editormd-preview-theme-dark code { background-color: var(--bs-secondary-bg) !important; color: var(--bs-body-color) !important; }
      [data-bs-theme="dark"] .editormd-preview-theme-dark pre { background-color: var(--bs-dark-bg-subtle) !important; border-color: var(--bs-border-color) !important; }
      [data-bs-theme="dark"] .editormd-preview-theme-dark blockquote { border-left-color: var(--bs-border-color) !important; color: var(--bs-secondary-color) !important; background-color: var(--bs-secondary-bg) !important; }
      [data-bs-theme="dark"] .editormd-preview-theme-dark table th,
      [data-bs-theme="dark"] .editormd-preview-theme-dark table td { border-color: var(--bs-border-color) !important; }
      [data-bs-theme="dark"] .editormd-preview-theme-dark table tr:nth-child(2n) { background-color: var(--bs-tertiary-bg) !important; }
	
	</style>
@endpush

@section('content')
	<main class="pt-5 pb-5"> {{-- Add padding --}}
		<div class="container">
			<div class="row g-0 g-lg-4">
				
				{{-- Sidebar Column --}}
				<div class="col-lg-3">
					{{-- Include the sidebar partial, passing the current article --}}
					@include('user.pages.partials.help-sidebar', [
							'currentCategory' => $helpArticle->category, /* Pass category from article */
							'helpArticle' => $helpArticle
					])
				</div>
				
				{{-- Main Content Column --}}
				<div class="col-lg-9">
					{{-- Breadcrumbs --}}
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb breadcrumb-dots mb-4">
							<li class="breadcrumb-item"><a href="{{ route('user.home', $user->username) }}"><i class="bi bi-house me-1"></i> Home</a></li>
							<li class="breadcrumb-item"><a href="{{ route('user.help.index', $user->username) }}">Help Center</a></li>
							@if($helpArticle->category)
								<li class="breadcrumb-item"><a href="{{ route('user.help.category', ['username' => $user->username, 'category_slug' => $helpArticle->category->category_slug]) }}">{{ $helpArticle->category->category_name }}</a></li>
							@endif
							<li class="breadcrumb-item active" aria-current="page">{{ Str::limit($helpArticle->title, 50) }}</li> {{-- Limit title in breadcrumb --}}
						</ol>
					</nav>
					
					{{-- Article Header --}}
					<div class="mb-4">
						<h1>{{ $helpArticle->title }}</h1>
						{{-- Optional: Author/Date info --}}
						{{-- <p class="text-muted small">
								Written by {{ $helpArticle->user->name }} <span class="mx-1">•</span>
								Last updated {{ $helpArticle->updated_at->format('M d, Y') }}
						</p> --}}
					</div>
					
					{{-- Article Body - Rendered Markdown --}}
					<div id="article-content">
						{{-- This div will be populated by the script --}}
						<textarea style="display:none;">{{ $helpArticle->body }}</textarea> {{-- Hidden textarea for source --}}
					</div>
				
				</div>
			</div>
		</div>
	</main>
@endsection

@push('scripts')
	{{-- EditorMD scripts for rendering --}}
	{{-- Make sure jQuery is loaded before this (usually in the main layout) --}}
	<script src="/editormd/editormd.min.js"></script>
	{{-- <script src="/editormd/languages/en.js"></script> --}} {{-- Language not needed for preview only --}}
	<script src="/editormd/lib/marked.min.js"></script>
	<script src="/editormd/lib/prettify.min.js"></script>
	{{-- <script src="/editormd/lib/raphael.min.js"></script> --}} {{-- Only if using flowcharts/sequence --}}
	{{-- <script src="/editormd/lib/underscore.min.js"></script> --}} {{-- Only if using flowcharts/sequence --}}
	{{-- <script src="/editormd/lib/sequence-diagram.min.js"></script> --}} {{-- Only if using sequence --}}
	{{-- <script src="/editormd/lib/flowchart.min.js"></script> --}} {{-- Only if using flowcharts --}}
	{{-- <script src="/editormd/lib/jquery.flowchart.min.js"></script> --}} {{-- Only if using flowcharts --}}
	
	<script>
		$(document).ready(function() {
			// Render the markdown content into the #article-content div
			editormd.markdownToHTML("article-content", {
				// markdown : "[TOC]\n### Hello world!\n## Heading 2", // You could override markdown here, but we use the textarea
				htmlDecode      : "style,script,iframe|on*", // Decode HTML tags, filter potentially dangerous ones
				emoji           : true,
				taskList        : true,
				tex             : true,  // Assuming you support LaTeX
				flowChart       : true, // Assuming you support flow charts
				sequenceDiagram : true, // Assuming you support sequence diagrams
				previewTheme    : document.documentElement.getAttribute('data-bs-theme') === 'dark' ? "dark" : "default", // Match theme
				// theme : "...", // Not needed for preview
				// editorTheme : "...", // Not needed for preview
			});
			
			// Small fix for Bootstrap collapse persistence on page load/back button
			// Ensure the correct category is expanded if navigating back/forward
			var activeCollapse = $('.help-sidebar .collapse.show');
			if(activeCollapse.length > 0) {
				var collapseElement = activeCollapse[0];
				var bsCollapse = new bootstrap.Collapse(collapseElement, {
					toggle: false // Prevent toggling again
				});
				bsCollapse.show(); // Ensure it's shown
			}
		});
	</script>
@endpush
