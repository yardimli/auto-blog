@extends('layouts.company-app') {{-- Or your main user layout --}}

{{-- Use $user passed from controller/view share --}}
@section('title', ($user->company_name ?? $user->name) . ' - Help Center')

@push('styles')
	{{-- Add specific styles if needed --}}
	<style>
      .help-category-card {
          transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
          height: 100%; /* Ensure cards in a row have same height */
      }
      .help-category-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      }
      .help-category-card .card-body {
          display: flex;
          flex-direction: column;
      }
      .help-category-card .card-text {
          flex-grow: 1; /* Pushes footer down */
      }
      .help-category-icon {
          font-size: 1.5rem;
          width: 40px;
          height: 40px;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          border-radius: 0.375rem; /* Bootstrap's default */
      }
      .help-sidebar .nav-link {
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
      }
      .help-sidebar .nav-link .bi-chevron-down {
          transition: transform 0.3s ease;
      }
      .help-sidebar .nav-link[aria-expanded="true"] .bi-chevron-down {
          transform: rotate(-180deg);
      }
      .help-sidebar .collapse .nav-link {
          font-size: 0.85rem;
          color: var(--bs-secondary-color);
      }
      .help-sidebar .collapse .nav-link.active {
          font-weight: bold;
          color: var(--bs-primary);
          background-color: transparent !important; /* Override pill background */
      }
      .help-sidebar .collapse .nav-link:hover {
          color: var(--bs-primary);
      }
	
	</style>
@endpush

@section('content')
	<main class="pt-5 pb-5"> {{-- Add padding --}}
		<div class="container">
			<div class="row g-0 g-lg-4">
				
				{{-- Sidebar Column --}}
				<div class="col-lg-3">
					{{-- Include the sidebar partial --}}
					{{-- Data ($user, $helpCategoriesForSidebar, $groupedHelpArticlesForSidebar) is shared via View::share() --}}
					@include('user.pages.partials.help-sidebar', [
							'currentCategory' => null,  /* No specific category active on index */
							'helpArticle' => null       /* No specific article active on index */
					])
				</div>
				
				{{-- Main Content Column --}}
				<div class="col-lg-9">
					{{-- Header --}}
					<div class="text-center mb-4">
						<h1 class="display-5">How can we help you?</h1>
						<p class="lead text-muted">
							Search for any helpful articles from our team to find an answer to your question.
						</p>
					</div>
					
					{{-- Search Bar --}}
					<div class="mb-5">
						<form action="#" method="GET"> {{-- Add search route later --}}
							<div class="input-group input-group-lg">
								<input type="search" class="form-control" name="query" placeholder="Search for articles..." aria-label="Search for articles">
								<button class="btn btn-primary mb-0" type="submit"><i class="bi bi-search me-1"></i> Search</button>
							</div>
						</form>
					</div>
					
					{{-- Featured Categories Grid --}}
					@if($featuredCategories->isNotEmpty())
						<div class="row row-cols-1 row-cols-md-2 g-4">
							@foreach($featuredCategories as $categoryName => $articles)
								@php
									// Find the category object to get the slug
									$category = $helpCategoriesForSidebar->firstWhere('category_name', $categoryName);
									// Placeholder icon logic (replace with real icons later)
									$iconClass = 'bi-book'; // Default
									if (stripos($categoryName, 'start') !== false) $iconClass = 'bi-house-door';
									if (stripos($categoryName, 'feedback') !== false) $iconClass = 'bi-chat-dots';
									if (stripos($categoryName, 'roadmap') !== false) $iconClass = 'bi-signpost-split';
									if (stripos($categoryName, 'changelog') !== false) $iconClass = 'bi-megaphone';
								@endphp
								@if($category) {{-- Ensure category exists --}}
								<div class="col">
									<div class="card shadow-sm help-category-card">
										<div class="card-body">
											<div class="d-flex align-items-start mb-3">
												<div class="bg-primary bg-opacity-10 text-primary p-2 rounded me-3">
													<i class="bi {{ $iconClass }} help-category-icon"></i>
												</div>
												<div>
													<h5 class="card-title mb-0">
														<a href="{{ route('user.help.category', ['username' => $user->username, 'category_slug' => $category->category_slug]) }}" class="text-reset stretched-link">
															{{ $categoryName }}
														</a>
													</h5>
													{{-- Add description if available in Category model --}}
													<p class="card-text text-muted small mt-1">{{ $category->category_description ?? 'Find articles related to ' . $categoryName . '.' }}</p>
												</div>
											</div>
											{{-- Placeholder for description or first few article titles --}}
											{{-- <p class="card-text text-muted">{{ $category->category_description ?? 'Learn more about ' . $categoryName }}</p> --}}
										</div>
										<div class="card-footer bg-transparent border-top-0 pt-0 d-flex justify-content-between align-items-center">
											{{-- Article Count --}}
											<span class="text-muted small">{{ $articles->count() }} {{ Str::plural('article', $articles->count()) }}</span>
											{{-- Author Avatars (Placeholder) --}}
											{{-- <div class="avatar-group avatar-group-sm">
													<img class="avatar-img rounded-circle" src="/assets/images/avatar/01.jpg" alt="avatar">
													<img class="avatar-img rounded-circle" src="/assets/images/avatar/02.jpg" alt="avatar">
											</div> --}}
										</div>
									</div>
								</div>
								@endif
							@endforeach
						</div>
					@else
						<div class="text-center text-muted mt-5">
							<i class="bi bi-journal-x fs-1"></i>
							<p class="mt-3">No help articles have been published yet.</p>
						</div>
					@endif
				
				</div>
			</div>
		</div>
	</main>
@endsection

@push('scripts')
	{{-- Add specific scripts if needed --}}
@endpush
