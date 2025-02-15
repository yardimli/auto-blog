@extends('user.pages.layout')

@section('user-content')
	
		<div class="container">
			<h2>{{ $pageSettings->title ?? 'Blog Page' }}</h2>
			@if($pageSettings && $pageSettings->description)
				<div class="page-description mb-4">
					{{ $pageSettings->description ?? '' }}
				</div>
			@endif
			
			<div class="row">
			@foreach($articles as $article)
				<div class="col-md-4 mb-4">
					<div class="card h-100">
						@if($article->featuredImage)
							<img src="{{ $article->featuredImage->getMediumUrl() }}"
							     class="card-img-top"
							     alt="{{ $article->featuredImage->image_alt }}">
						@endif
						<div class="card-body">
							<div class="mb-2">
								@foreach($article->categories as $category)
									<span class="badge bg-secondary me-1">{{ $category->category_name }}</span>
								@endforeach
							</div>
							<h2 class="card-title h4">{{ $article->title }}</h2>
							@if($article->subtitle)
								<h3 class="card-subtitle h6 mb-2 text-muted">{{ $article->subtitle }}</h3>
							@endif
							<p class="card-text">{{ $article->short_description }}</p>
							<div class="d-flex justify-content-between align-items-center">
								<a href="{{ route('user.blog.article', ['username' => $user->username, 'slug' => $article->slug]) }}"
								   class="btn btn-primary">Read More</a>
								<small class="text-muted">{{ $article->posted_at->format('M d, Y') }}</small>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
		
		<div class="row">
			<div class="col-12">
				{{ $articles->links() }}
			</div>
		</div>
	</div>
@endsection
