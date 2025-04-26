@extends('layouts.admin')

@section('title', __('default.Page Settings'))
@section('page-title', __('default.Page Settings'))

@section('content')
	<h2 class="mb-4">{{ __('default.Page Settings') }}</h2> {{-- Use h2 for main page title --}}
	
	<form method="POST" action="{{ route('settings.pages.update') }}">
		@csrf
		@method('PUT')
		
		@php
			$pageTypes = ['home', 'blog', 'help', 'roadmap', 'feedback', 'changelog', 'terms', 'privacy', 'cookie'];
			$pageIcons = [
					'home' => 'bi-house',
					'blog' => 'bi-file-text',
					'help' => 'bi-question-circle',
					'roadmap' => 'bi-signpost-split',
					'feedback' => 'bi-chat-left-dots',
					'changelog' => 'bi-list-check',
					'terms' => 'bi-file-earmark-text',
					'privacy' => 'bi-shield-check',
					'cookie' => 'bi-cookie'
			];
		@endphp
		
		@foreach($pageTypes as $pageType)
			<div class="card mb-4">
				<div class="card-header">
					<h5 class="mb-0 d-flex align-items-center">
						<i class="{{ $pageIcons[$pageType] ?? 'bi-file-earmark' }} me-2"></i>
						{{ ucfirst($pageType) }} Page
					</h5>
				</div>
				<div class="card-body">
					<div class="mb-3">
						<label for="{{ $pageType }}_title" class="form-label">Title</label>
						<input type="text" class="form-control" id="{{ $pageType }}_title" name="pages[{{ $pageType }}][title]" value="{{ old('pages.' . $pageType . '.title', $pageSettings[$pageType]->title ?? '') }}">
					</div>
					<div class="mb-3">
						<label for="{{ $pageType }}_description" class="form-label">Meta Description</label> {{-- Clarify it's meta description --}}
						<textarea class="form-control" id="{{ $pageType }}_description" name="pages[{{ $pageType }}][description]" rows="3" placeholder="Brief description for search engines (usually ~160 characters)">{{ old('pages.' . $pageType . '.description', $pageSettings[$pageType]->description ?? '') }}</textarea>
					</div>
				</div>
			</div>
		@endforeach
		
		<div class="text-end"> {{-- Align button right --}}
			<button type="submit" class="btn btn-primary">Save Changes</button>
		</div>
	</form>
@endsection
