@extends('user.pages.layout')
@section('title', $user->company_name . ' - ' . ($pageSettings->title ?? 'Help Page'))

@section('user-content')
	<div class="container">
		<h2>{{ $pageSettings->title ?? 'Help Page' }}</h2>
		@if($pageSettings && $pageSettings->description)
			<div class="page-description mb-4">
				{{ $pageSettings->description ?? '' }}
			</div>
		@endif

		<div class="container">
			<!-- Main content START -->
			
			<!-- Help search START -->
			<div class="row align-items-center pt-5 pb-5 pb-lg-3">
				<div class="col-md-3">
					@include('layouts.svg3-image')
				</div>
				<!-- Card START -->
				<div class="col-md-6 text-center">
					<!-- Title -->
					<h1>Hi Cer, we're here to help.</h1>
					<p class="mb-4">Start here to get answers to your questions.</p>
				</div>
				<div class="col-md-3">
					@include('layouts.svg4-image')
				</div>
			</div>
			<!-- Help search START -->
				
				<!-- Recommended topics START -->
			<div class="py-5">
				<!-- Titles -->
				<h4 class="text-center mb-4">Topics</h4>
				<!-- Row START -->
				<div class="row g-4">
					@foreach($helpArticles as $category => $articles)
						<div class="col-md-4">
							<!-- Get started START -->
							<div class="card h-100">
								<!-- Title START -->
								<div class="card-header pb-0 border-0">
									<h5 class="card-title mb-0 mt-2"><a class="nav-link d-flex" href="/{{'@'.Auth::user()->username}}/help/{{$category}}">{{$category}}</a></h5>
								</div>
								<!-- Title END -->
								<!-- List START -->
								<div class="card-body">
									<ul class="nav flex-column">
										@php $i = 0; @endphp
										@foreach($articles as $article)
											@php $i++; @endphp
											@if($i > 5) @break @endif
											<li class="nav-item"><a class="nav-link d-flex" href="/{{'@'.Auth::user()->username}}/help/{{$category}}" target="_blank"><i class="fa-solid fa-angle-right text-primary pt-1  me-2"></i>{{$article['title']}}</a></li>
										@endforeach
									</ul>
								</div>
								<!-- List END -->
							</div>
							<!-- Get started END -->
						</div>
					@endforeach
				</div>
				<!-- Row END -->
			</div>
		</div>
		
		
		{{-- Rest of the page content --}}
	</div>
@endsection
