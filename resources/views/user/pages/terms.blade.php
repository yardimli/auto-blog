@extends('user.pages.layout')
@section('title', $user->company_name . ' - ' . ($pageSettings->title ?? 'Terms Page'))

@section('user-content')
	<div class="container">
		<h2>{{ $pageSettings->title ?? 'Terms Page' }}</h2>
		@if($pageSettings && $pageSettings->description)
			<div class="page-description mb-4">
				{{ $pageSettings->description ?? '' }}
			</div>
		@endif
		
		{{-- Rest of the page content --}}
	</div>
@endsection
