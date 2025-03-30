@extends('layouts.app')
@section('title', $user->name . ' - Feedback') {{-- Updated title --}}

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
			@if(session('error'))
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					{{ session('error') }}
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
			
			<div class="row">
				{{-- Main Content Area --}}
				<div class="col-lg-12">
					<h4>Feature Requests</h4>
					
					{{-- Submission Form Card --}}
					<div class="card mb-4">
						<div class="card-body">
							{{-- UPDATE: Form action includes username --}}
							<form action="{{ route('user.feedback.store', ['username' => $user->username]) }}" method="POST">
								@csrf
								<div class="mb-3">
									<label for="title" class="form-label visually-hidden">Title</label>
									<input type="text" class="form-control" id="title" name="title" placeholder="Short, descriptive title" value="{{ old('title') }}" required>
								</div>
								<div class="mb-3">
									<label for="details" class="form-label visually-hidden">Details</label>
									<textarea class="form-control" id="details" name="details" rows="3" placeholder="Any additional details..." required>{{ old('details') }}</textarea>
								</div>
								@guest
									{{-- Show email field only for guests --}}
									<div class="mb-3">
										<label for="guest_email" class="form-label visually-hidden">Your Email (Optional, for updates)</label>
										<input type="email" class="form-control" id="guest_email" name="guest_email" placeholder="Your Email (Optional, for updates)" value="{{ old('guest_email') }}">
										<small class="form-text text-muted">We might use this to notify you if your feedback status changes.</small>
									</div>
								@endguest
								<div class="d-flex justify-content-end align-items-center">
									<button type="reset" class="btn btn-link text-secondary me-2">Cancel</button>
									<button type="submit" class="btn btn-primary">Create Post</button>
								</div>
							</form>
						</div>
					</div>
					
					{{-- Filtering and Sorting Controls --}}
					<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
						<div>
							Showing
							<div class="dropdown d-inline-block mx-1">
								<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
									{{ $sort }}
								</button>
								<ul class="dropdown-menu" aria-labelledby="sortDropdown">
									{{-- UPDATE: Sorting links include username --}}
									<li><a class="dropdown-item {{ $sort === 'Trending' ? 'active' : '' }}" href="{{ route('user.feedback.index', ['username' => $user->username, 'sort' => 'Trending', 'search' => $search]) }}">Trending</a></li>
									<li><a class="dropdown-item {{ $sort === 'Newest' ? 'active' : '' }}" href="{{ route('user.feedback.index', ['username' => $user->username, 'sort' => 'Newest', 'search' => $search]) }}">Newest</a></li>
								</ul>
							</div>
							posts
						</div>
						<div class="ms-auto">
							{{-- UPDATE: Search form action includes username --}}
							<form action="{{ route('user.feedback.index', ['username' => $user->username]) }}" method="GET" class="d-inline-block">
								<input type="hidden" name="sort" value="{{ $sort }}"> {{-- Keep sort param during search --}}
								<div class="input-group input-group-sm">
									<span class="input-group-text"><i class="bi bi-search"></i></span>
									<input type="search" name="search" class="form-control" placeholder="Search..." value="{{ $search }}">
								</div>
							</form>
						</div>
					</div>
					
					{{-- Feedback List --}}
					@if($feedbackItems->isEmpty())
						<div class="text-center text-muted my-5">
							<p>No feedback submitted yet.</p>
							<p>Be the first to suggest a feature!</p>
						</div>
					@else
						<div class="list-group mb-4">
							@foreach($feedbackItems as $item)
								<div class="list-group-item d-flex align-items-start gap-3">
									{{-- Vote Button --}}
									{{-- UPDATE: Vote form action includes username and feedback item --}}
									<form action="{{ route('user.feedback.vote', ['username' => $user->username, 'feedback' => $item]) }}" method="POST" class="text-center">
										@csrf
										<button type="submit" class="btn btn-sm {{ $item->has_voted ? 'btn-primary' : 'btn-outline-primary' }} d-flex flex-column align-items-center px-2 py-1" @guest title="Login to vote" disabled @endguest>
											<i class="bi bi-caret-up-fill"></i>
											<span class="vote-count">{{ $item->votes_count }}</span>
										</button>
									</form>
									{{-- Feedback Details --}}
									<div class="flex-grow-1">
										<h6 class="mb-1">{{ $item->title }}</h6>
										<p class="mb-1 text-muted small">
											{{ Str::limit($item->details, 150) }}
										</p>
										<small class="text-muted">
											Submitted {{ $item->created_at->diffForHumans() }} by {{ $item->submitter_name }}
											@if($item->status != \App\Models\Feedback::STATUS_OPEN) {{-- Use constant for comparison --}}
											<span class="badge bg-secondary ms-2">{{ $item->status }}</span>
											@endif
										</small>
									</div>
								</div>
							@endforeach
						</div>
						
						{{-- Pagination Links --}}
						<div class="d-flex justify-content-center">
							{{-- Pagination links should automatically retain the base URL (/user/feedback) and appended query strings --}}
							{{ $feedbackItems->links() }}
						</div>
					@endif
				</div>
			</div>
		</div>
	</main>
@endsection

@push('scripts')
	<script>
		$(document).ready(function () {
			// Add any specific JS for this page if needed, e.g., AJAX voting
		});
	</script>
@endpush
