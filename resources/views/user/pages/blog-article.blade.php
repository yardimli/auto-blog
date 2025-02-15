@extends('user.pages.layout')

@section('user-content')
	<div class="container">
		<article class="blog-post">
			
			
			<h1>{{ $article->title }}</h1>
			
			@if($article->subtitle)
				<h2 class="text-muted h4 mb-3">{{ $article->subtitle }}</h2>
			@endif
			
			<div class="meta text-muted mb-4">
				<span>Published on {{ $article->posted_at->format('F d, Y') }}</span>
			</div>
			
			@if($article->featuredImage)
				<div class="featured-image mb-4 text-center">
					<img src="{{ $article->featuredImage->getLargeUrl() }}"
					     class="img-fluid" style="max-width: 600px; border-radius: 10px;"
					     alt="{{ $article->featuredImage->image_alt }}">
				</div>
			@endif
			
			<div class="mb-3">
				@foreach($article->categories as $category)
					<span class="badge bg-secondary me-1">{{ $category->category_name }}</span>
				@endforeach
			</div>
			@if($article->short_description)
				<div class="lead mb-4">
					{{ $article->short_description }}
				</div>
			@endif
			
			<div class="article-content">
				{!! $article->body !!}
			</div>
		</article>
		
		@if($recentArticles->count() > 0)
			<div class="related-posts mt-5">
				<h3>More Articles in this Category</h3>
				<div class="row mt-4">
					@foreach($recentArticles as $recentArticle)
						<div class="col-md-4 mb-4">
							<div class="card h-100">
								@if($recentArticle->featuredImage)
									<img src="{{ $recentArticle->featuredImage->getMediumUrl() }}"
									     class="card-img-top"
									     alt="{{ $recentArticle->featuredImage->image_alt }}">
								@endif
								<div class="card-body">
									<h4 class="card-title h5">{{ $recentArticle->title }}</h4>
									<p class="card-text">{{ Str::limit($recentArticle->short_description, 100) }}</p>
									<a href="{{ route('user.blog.article', ['username' => $user->username, 'slug' => $recentArticle->slug]) }}"
									   class="btn btn-sm btn-primary">Read More</a>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		@endif
	</div>
@endsection
