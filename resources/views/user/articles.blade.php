@extends('layouts.app')

@section('content')
	<main>
		<div class="container" style="min-height: calc(88vh);">
			
			@if(session('success'))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					{{ session('success') }}
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			@endif
			
			<div class="row mt-3">
				<div class="col-12">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<h5>{{ __('default.Articles') }}</h5>
						<a href="{{ route('articles.create') }}" class="btn btn-primary">
							{{ __('default.Create New Article') }}
						</a>
					</div>
					
					<!-- Articles List -->
					<div class="card">
						<div class="card-body">
							@if($articles->isEmpty())
								<p class="text-center my-3">{{ __('default.No articles found') }}</p>
							@else
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
										<tr>
											<th>{{ __('default.Title') }}</th>
											<th>{{ __('default.Language') }}</th>
											<th>{{ __('default.Status') }}</th>
											<th>{{ __('default.Created At') }}</th>
											<th>{{ __('default.Actions') }}</th>
										</tr>
										</thead>
										<tbody>
										@foreach($articles as $article)
											<tr>
												<td style="vertical-align: middle;">{{ Str::limit($article->title, 50) }}</td>
												<td style="vertical-align: middle;">{{ $article->language->language_name }}</td>
												<td style="vertical-align: middle;">
                                                    <span class="badge bg-{{ $article->is_published ? 'success' : 'warning' }}">
                                                        {{ $article->is_published ? __('default.Published') : __('default.Draft') }}
                                                    </span>
												</td>
												<td style="vertical-align: middle;">{{ $article->created_at->format('Y-m-d H:i') }}</td>
												<td style="vertical-align: middle;">
													<form action="{{ route('articles.destroy', $article->id) }}"
														  method="POST"
														  class="d-inline"
														  onsubmit="return confirm('{{ __('default.Are you sure you want to delete this article?') }}')">
														<div class="btn-group">
															<a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-primary">
																{{ __('default.Edit') }}
															</a>
															@csrf
															@method('DELETE')
															<button type="submit" class="btn btn-sm btn-danger">
																{{ __('default.Delete') }}
															</button>
														</div>
													</form>
												</td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
								
								<!-- Pagination -->
								<div class="d-flex justify-content-center mt-4">
									{{ $articles->links('pagination::bootstrap-5') }}
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection
