@extends('layouts.settings')

@section('settings-content')
	
	
	<div class="container">
		<h2>{{ __('default.Page Settings') }}</h2>
		
		@if(session('success'))
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		@endif
		
		<form method="POST" action="{{ route('settings.pages.update') }}">
			@csrf
			@method('PUT')
			
			@foreach(['home', 'blog', 'help', 'roadmap', 'feedback', 'changelog', 'terms', 'privacy', 'cookie'] as $pageType)
				<div class="card mb-4">
					<div class="card-header">
						<h3 class="h5 mb-0">{{ ucfirst($pageType) }} Page</h3>
					</div>
					<div class="card-body">
						<div class="mb-3">
							<label for="{{ $pageType }}_title" class="form-label">Title</label>
							<input type="text"
							       class="form-control"
							       id="{{ $pageType }}_title"
							       name="pages[{{ $pageType }}][title]"
							       value="{{ old('pages.' . $pageType . '.title', $pageSettings[$pageType]->title ?? '') }}">
						</div>
						<div class="mb-3">
							<label for="{{ $pageType }}_description" class="form-label">Description</label>
							<textarea class="form-control"
							          id="{{ $pageType }}_description"
							          name="pages[{{ $pageType }}][description]"
							          rows="3">{{ old('pages.' . $pageType . '.description', $pageSettings[$pageType]->description ?? '') }}</textarea>
						</div>
					</div>
				</div>
			@endforeach
			
			<button type="submit" class="btn btn-primary">Save Changes</button>
		</form>
	</div>
	
@endsection
