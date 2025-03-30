{{-- auto-blog/resources/views/user/pages/feedback.blade.php --}}
@extends('user.pages.layout')
{{-- Use owner's name in the title --}}
@section('title', $user->company_name . ' - ' . ($pageSettings->title ?? 'Feedback'))

@section('content')
	<main>
		<div class="container mt-4 mb-5" style="min-height: calc(80vh);">
			{{-- Session Messages --}}
			@if(session('success'))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					{{ session('success') }}
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			@endif
			{{-- Display Validation Errors --}}
			@if ($errors->any())
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong class="d-block mb-2">Please fix the following errors:</strong>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			@endif
			
			<h4>{{ $pageSettings?->title ?? 'Feature Requests for ' . $user->name }}</h4>
			{{-- Use owner's name or generic title --}}
			<p>{{ $pageSettings?->description ?? 'Suggest features or improvements. Vote on existing ideas!' }}</p>
			{{-- Start Two-Column Layout --}}
			<div class="row mt-4">
				
				{{-- Left Column: Suggestion Form --}}
				<div class="col-lg-5 mb-4 mb-lg-0">
					<div class="card shadow-sm sticky-lg-top" style="top: 80px;"> {{-- Added sticky-lg-top for larger screens --}}
						<div class="card-header">Suggest an Idea</div>
						<div class="card-body">
							{{-- Form action includes username of the OWNER --}}
							<form action="{{ route('user.feedback.store', ['username' => $user->username]) }}" method="POST">
								@csrf
								<div class="mb-3">
									<label for="title" class="form-label visually-hidden">Title</label>
									<input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Short, descriptive title" value="{{ old('title') }}" required>
									@error('title')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="mb-3">
									<label for="details" class="form-label visually-hidden">Details</label>
									<textarea class="form-control @error('details') is-invalid @enderror" id="details" name="details" rows="3" placeholder="Any additional details..." required>{{ old('details') }}</textarea>
									@error('details')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								@guest
									{{-- Show email field only for guests --}}
									<div class="mb-3">
										<label for="guest_email" class="form-label visually-hidden">Your Email (Optional, for updates)</label>
										<input type="email" class="form-control @error('guest_email') is-invalid @enderror" id="guest_email" name="guest_email" placeholder="Your Email (Optional)" value="{{ old('guest_email') }}">
										@error('guest_email')
										<div class="invalid-feedback">{{ $message }}</div>
										@enderror
										<small class="form-text text-muted">We might use this to notify you if the status changes.</small>
									</div>
								@endguest
								<div class="d-flex justify-content-end align-items-center">
									{{-- <button type="reset" class="btn btn-link text-secondary me-2">Clear</button> --}}
									<button type="submit" class="btn btn-primary">Submit Idea</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				{{-- End Left Column --}}
				
				{{-- Right Column: Existing Feedback List --}}
				<div class="col-lg-7">
					{{-- Filtering and Sorting Controls --}}
					<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
						<div>
							Sort by:
							<div class="dropdown d-inline-block mx-1">
								<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
									{{ $sort }}
								</button>
								<ul class="dropdown-menu" aria-labelledby="sortDropdown">
									{{-- Sorting links include username of OWNER --}}
									<li><a class="dropdown-item {{ $sort === 'Trending' ? 'active' : '' }}" href="{{ route('user.feedback.index', ['username' => $user->username, 'sort' => 'Trending', 'search' => $search]) }}">Trending</a></li>
									<li><a class="dropdown-item {{ $sort === 'Newest' ? 'active' : '' }}" href="{{ route('user.feedback.index', ['username' => $user->username, 'sort' => 'Newest', 'search' => $search]) }}">Newest</a></li>
								</ul>
							</div>
						</div>
						<div class="ms-md-auto"> {{-- ms-md-auto to push search to right on medium+ screens --}}
							{{-- Search form action includes username of OWNER --}}
							<form action="{{ route('user.feedback.index', ['username' => $user->username]) }}" method="GET" class="d-inline-block">
								<input type="hidden" name="sort" value="{{ $sort }}"> {{-- Keep sort param --}}
								<div class="input-group input-group-sm">
									<input type="search" name="search" class="form-control" placeholder="Search ideas..." value="{{ $search }}" aria-label="Search feedback">
									<button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
								</div>
							</form>
						</div>
					</div>
					
					{{-- Feedback List --}}
					@if($feedbackItems->isEmpty())
						<div class="text-center text-muted my-5 py-5 bg-light rounded shadow-sm">
							<i class="bi bi-lightbulb fs-1 mb-3"></i>
							<h4>No ideas found{{ $search ? ' matching your search' : '' }}.</h4>
							<p>Be the first to suggest something using the form!</p>
						</div>
					@else
						<div class="list-group mb-4 shadow-sm">
							@foreach($feedbackItems as $item)
								{{-- Add ID for potential jump-to target --}}
								<div class="list-group-item list-group-item-action d-flex align-items-start gap-3 py-3" id="feedback-{{ $item->id }}">
									{{-- Vote Button --}}
									{{-- Vote form action includes username of OWNER and feedback item --}}
									<form action="{{ route('user.feedback.vote', ['username' => $user->username, 'feedback' => $item]) }}" method="POST" class="text-center flex-shrink-0">
										@csrf
										<button type="submit" class="btn btn-sm {{ $item->has_voted ? 'btn-primary' : 'btn-outline-primary' }} d-flex flex-column align-items-center px-2 py-1 {{ Auth::guest() ? 'disabled' : '' }}"
										        @guest title="Login to vote" disabled @else title="{{ $item->has_voted ? 'Remove vote' : 'Upvote' }}" @endguest>
											<i class="bi bi-caret-up-fill"></i>
											<span class="vote-count fw-bold">{{ $item->votes_count }}</span>
										</button>
									</form>
									
									{{-- Feedback Details --}}
									<div class="flex-grow-1">
										<div class="d-flex w-100 justify-content-between align-items-start mb-1 flex-wrap"> {{-- Added flex-wrap --}}
											<h6 class="mb-1 me-2">{{ $item->title }}</h6> {{-- Added me-2 and mb-1 --}}
											{{-- Show Status Badge --}}
											@if($item->status !== \App\Models\Feedback::STATUS_OPEN)
												<span class="badge rounded-pill bg-info text-dark ms-auto flex-shrink-0 mb-1">{{ $item->status }}</span> {{-- Added ms-auto and mb-1 --}}
											@endif
										</div>
										<p class="mb-1 text-muted small">
											{{-- Use pre-wrap to preserve line breaks from input --}}
											<span style="white-space: pre-wrap;">{{ Str::limit($item->details, 150) }}</span>
											@if (strlen($item->details) > 150)
												{{-- Add a 'read more' link/modal trigger later if needed --}}
											@endif
										</p>
										<small class="text-muted d-flex align-items-center gap-2">
											<img src="{{ $item->submitter_avatar }}" alt="{{ $item->submitter_name }} avatar" class="avatar avatar-xs rounded-circle">
											<span>Submitted {{ $item->created_at->diffForHumans() }} by <strong>{{ $item->submitter_name }}</strong></span>
											{{-- Display guest email discreetly if needed, maybe only for owner? --}}
											{{-- @if(!$item->submitter && $item->guest_email) ({{ $item->guest_email }}) @endif --}}
										</small>
									</div>
								</div>
							@endforeach
						</div>
						
						{{-- Pagination Links --}}
						<div class="d-flex justify-content-center mt-4">
							{{-- Links will automatically include 'sort' and 'search' query params via appends() in controller --}}
							{{ $feedbackItems->links() }}
						</div>
					@endif
				</div>
				{{-- End Right Column --}}
			
			</div> {{-- End .row --}}
		</div> {{-- End .container --}}
	</main>
@endsection

@push('scripts')
	<script>
		$(document).ready(function () {
			// Example: Smooth scroll to feedback item if URL has hash
			if(window.location.hash) {
				var hash = window.location.hash;
				if ($(hash).length) {
					$('html, body').animate({
						// Adjust offset for fixed navbar height + some padding
						scrollTop: $(hash).offset().top - 100
					}, 800);
				}
			}
			
			// Add more specific JS if needed
		});
	</script>
@endpush
