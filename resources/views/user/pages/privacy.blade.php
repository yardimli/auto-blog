@extends('user.pages.layout')
@section('title', $user->company_name . ' - ' . ($pageSettings->title ?? 'Privacy Policy'))

@section('user-content')
	<div class="container">
		<h2>{{ $pageSettings->title ?? 'Privacy Page' }}</h2>
		@if($pageSettings && $pageSettings->description)
			<div class="page-description mb-4">
				{{ $pageSettings->description ?? '' }}
			</div>
		@endif
		
		{{-- Rest of the page content --}}
	</div>
@endsection
