@extends('layouts.company-app') {{-- Or your main user layout --}}

{{-- Use $user and $currentCategory passed from controller/view share --}}
@section('title', ($user->company_name ?? $user->name) . ' - Help: ' . $currentCategory->category_name)

@push('styles')
	{{-- Reuse styles from help.blade.php or add new ones --}}
	<style>
      .help-sidebar .nav-link { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
      .help-sidebar .nav-link .bi-chevron-down { transition: transform 0.3s ease; }
      .help-sidebar .nav-link[aria-expanded="true"] .bi-chevron-down { transform: rotate(-180deg); }
      .help-sidebar .collapse .nav-link { font-size: 0.85rem; color: var(--bs-secondary-color); }
      .help-sidebar .collapse .nav-link.active { font-weight: bold; color: var(--bs-primary); background-color: transparent !important; }
      .help-sidebar .collapse .nav-link:hover { color: var(--bs-primary); }
      .article-list-item .article-excerpt {
          color: var(--bs-secondary-color);
          font-size: 0.9rem;
      }
	</style>
@endpush

@section('content')
	<main class="pt-5 pb-5"> {{-- Add padding --}}
		<div class="container">
			<div class="row g-0 g-lg-4">
				
				{{-- Sidebar Column --}}
				<div class="col-lg-3">
					{{-- Include the sidebar partial, passing the current category --}}
					@include('user.pages.partials.help-sidebar', [
							'currentCategory' => $currentCategory,
							'helpArticle' => null /* No specific article active */
					])
				</div>
				
				{{-- Main Content Column --}}
				<div class="col-lg-9">
					{{-- Breadcrumbs --}}
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb breadcrumb-dots mb-4">
							<li class="breadcrumb-item"><a href="{{ route('user.home', $user->username) }}"><i class="bi bi-house me-1"></i> Home</a></li>
							<li class="breadcrumb-item"><a href="{{ route('user.help.index', $user->username) }}">Help Center</a></li>
							<li class="breadcrumb-item active" aria-current="page">{{ $currentCategory->category_name }}</li>
						</ol>
					</nav>
					
					{{-- Category Header --}}
					<div class="mb-4">
						<h1>{{ $currentCategory->category_name }}</h1>
						@if($currentCategory->category_description)
							<p class="lead text-muted">{{ $currentCategory->category_description }}</p>
						@endif
					</div>
					
					{{-- Article List --}}
					@if($articlesInCategory->isNotEmpty())
						<div class="list-group list-group-flush">
							@foreach($articlesInCategory as $article)
								<a href="{{ route('user.help.article', ['username' => $user->username, 'help' => $article->id]) }}" class="list-group-item list-group-item-action article-list-item py-3">
									<h5 class="mb-1">{{ $article->title }}</h5>
									{{-- Simple excerpt (plain text, limited length) --}}
									{{-- More advanced: Strip tags and limit --}}
									@php
										$excerpt = strip_tags($article->body ?? ''); // Basic strip tags
										// Remove markdown characters for a cleaner excerpt (optional)
										// $excerpt = preg_replace('/[#*`~->|!\[\]()]/', '', $excerpt);
										$excerpt = Str::limit($excerpt, 150);
									@endphp
									<p class="mb-1 article-excerpt">{{ $excerpt }}</p>
									{{-- <small class="text-muted">Updated {{ $article->updated_at->diffForHumans() }}</small> --}}
								</a>
							@endforeach
						</div>
					@else
						<div class="text-center text-muted mt-5">
							<i class="bi bi-journal-x fs-1"></i>
							<p class="mt-3">No help articles found in this category yet.</p>
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
