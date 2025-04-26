@extends('layouts.admin')

@section('title', __('default.Articles'))
@section('page-title', __('default.Articles'))

@section('top-bar-actions')
	<a href="{{ route('articles.create') }}" class="btn btn-primary btn-sm ms-3">
		<i class="bi bi-plus-lg me-1"></i> {{ __('default.Create New Article') }}
	</a>
@endsection

{{-- Optional: Add search to top bar --}}
@section('top-bar-search')
<form action="{{ route('articles.index') }}" method="GET" class="d-flex me-3">
    <input type="search" name="search" class="form-control form-control-sm me-2" placeholder="Search articles..." value="{{ request('search') }}">
    <button type="submit" class="btn btn-sm btn-outline-secondary">Search</button>
</form>
@endsection

@section('content')
	<div class="card">
		<div class="card-header border-bottom pb-3">
{{--			<h5 class="card-title mb-0">{{ __('default.Articles') }}</h5>--}}
			{{-- Add filtering options here if needed --}}
		</div>
		<div class="card-body">
			@if($articles->isEmpty())
				<div class="text-center py-5">
					<i class="bi bi-file-earmark-text display-4 text-muted"></i>
					<p class="mt-3 mb-0">{{ __('default.No articles found') }}</p>
					<p><a href="{{ route('articles.create') }}">Create your first article</a>.</p>
				</div>
			@else
				<div class="table-responsive">
					<table class="table table-hover align-middle"> {{-- Added align-middle --}}
						<thead>
						<tr>
							<th>{{ __('default.Title') }}</th>
							<th>{{ __('default.Language') }}</th>
							<th>{{ __('default.Status') }}</th>
							<th>{{ __('default.Created At') }}</th>
							<th class="text-end">{{ __('default.Actions') }}</th>
						</tr>
						</thead>
						<tbody>
						@foreach($articles as $article)
							<tr>
								<td>
									<a href="{{ route('articles.edit', $article->id) }}" title="{{ $article->title }}">
										{{ Str::limit($article->title, 60) }}
									</a>
									@if($article->subtitle)
										<small class="d-block text-muted">{{ Str::limit($article->subtitle, 70) }}</small>
									@endif
								</td>
								<td>{{ $article->language->language_name }}</td>
								<td>
                                    <span class="badge rounded-pill bg-{{ $article->is_published ? 'success' : 'warning' }}">
                                        {{ $article->is_published ? __('default.Published') : __('default.Draft') }}
                                    </span>
								</td>
								<td>{{ $article->created_at->format('Y-m-d H:i') }}</td>
								<td class="text-end">
									{{-- Use button group for actions --}}
									<div class="btn-group btn-group-sm" role="group" aria-label="Article Actions">
										<a href="{{ route('articles.edit', $article->id) }}" class="btn btn-outline-primary" title="{{ __('default.Edit') }}">
											<i class="bi bi-pencil"></i>
										</a>
										{{-- Add view button if applicable --}}
										{{-- <a href="#" class="btn btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a> --}}
										<form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('default.Are you sure you want to delete this article?') }}')">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-outline-danger" title="{{ __('default.Delete') }}">
												<i class="bi bi-trash"></i>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				
				<!-- Pagination -->
				@if ($articles->hasPages())
					<div class="d-flex justify-content-center mt-4">
						{{ $articles->links('pagination::bootstrap-5') }}
					</div>
				@endif
			@endif
		</div>
	</div>
@endsection
