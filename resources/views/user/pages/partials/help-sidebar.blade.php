{{-- resources/views/user/pages/partials/help-sidebar.blade.php --}}
{{-- Requires $user, $helpCategoriesForSidebar, $groupedHelpArticlesForSidebar to be passed or shared --}}
{{-- Also requires $currentCategory (optional) and $helpArticle (optional) to highlight active items --}}
@php
	$activeCategorySlug = $currentCategory->category_slug ?? ($helpArticle->category->category_slug ?? null);
	$activeArticleId = $helpArticle->id ?? null;
@endphp
<div class="help-sidebar border-end pe-3 me-lg-4" style="min-width: 240px;"> {{-- Adjust width as needed --}}
	{{-- Logo/Brand (Optional - might be in main layout) --}}
	{{-- <a href="{{ route('user.home', $user->username) }}" class="d-block mb-3 h5 text-body">
			<img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="avatar-xs me-2">
			{{ $user->company_name ?? $user->name }}
	</a> --}}
	
	{{-- Search (Optional - can be here or main content) --}}
	{{-- <div class="mb-3 position-relative">
			<input type="search" class="form-control" placeholder="Search articles...">
			<button class="btn btn-sm btn-light position-absolute top-50 end-0 translate-middle-y me-2" type="submit"><i class="bi bi-search"></i></button>
	</div> --}}
	
	<nav class="nav flex-column nav-pills nav-pills-soft">
		<li class="nav-item">
			<a class="nav-link {{ Request::routeIs('user.help.index') ? 'active' : '' }}" href="{{ route('user.help.index', $user->username) }}"><i class="bi bi-house-door me-2"></i>Help Home</a>
		</li>
		
		@foreach($helpCategoriesForSidebar as $category)
			@php
				$categoryArticles = $groupedHelpArticlesForSidebar[$category->category_name] ?? collect();
				$isCategoryActive = $activeCategorySlug === $category->category_slug;
			@endphp
			@if($categoryArticles->isNotEmpty()) {{-- Only show categories with published articles --}}
			<li class="nav-item">
				<a class="nav-link {{ $isCategoryActive ? 'active' : '' }}"
				   data-bs-toggle="collapse"
				   href="#collapse-{{ $category->id }}"
				   role="button"
				   aria-expanded="{{ $isCategoryActive ? 'true' : 'false' }}"
				   aria-controls="collapse-{{ $category->id }}">
					{{-- Add Icon based on category name maybe? --}}
					<i class="bi bi-folder me-2"></i> {{ $category->category_name }}
					<i class="bi bi-chevron-down float-end small mt-1"></i>
				</a>
				<!-- Submenu -->
				<div class="collapse {{ $isCategoryActive ? 'show' : '' }}" id="collapse-{{ $category->id }}">
					<ul class="nav flex-column ps-3">
						@foreach($categoryArticles as $article)
							<li class="nav-item">
								<a class="nav-link py-1 {{ $activeArticleId === $article->id ? 'active' : '' }}"
								   href="{{ route('user.help.article', ['username' => $user->username, 'help' => $article->id]) }}">
									{{ $article->title }}
								</a>
							</li>
						@endforeach
						{{-- Link to see all articles in this category --}}
						<li class="nav-item">
							<a class="nav-link py-1 fw-bold small" href="{{ route('user.help.category', ['username' => $user->username, 'category_slug' => $category->category_slug]) }}">
								View all {{ $categoryArticles->count() }} articles...
							</a>
						</li>
					</ul>
				</div>
			</li>
			@endif
		@endforeach
	</nav>
	
	{{-- Bottom Links (Example) --}}
	<hr>
	<nav class="nav flex-column small">
		<li class="nav-item">
			<a class="nav-link" href="{{ route('user.feedback.index', $user->username) }}"><i class="bi bi-lightbulb me-2"></i>Give us feedback</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{ route('user.changelog', $user->username) }}"><i class="bi bi-megaphone me-2"></i>Latest changes</a>
		</li>
		{{-- Add other links as needed --}}
	</nav>
</div>
