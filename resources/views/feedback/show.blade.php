@extends('layouts.app')
@section('title', 'View Feedback Details')

@section('content')
	<main>
		<div class="container mt-4 mb-5">
			<div class="row justify-content-center">
				<div class="col-lg-9">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<h4 class="mb-0">Feedback Details</h4>
						<div>
							<a href="{{ route('feedback.edit', $feedback->id) }}" class="btn btn-sm btn-primary">Edit Status/Details</a>
							<a href="{{ route('feedback.index') }}" class="btn btn-sm btn-outline-secondary">Back to Your Feedback List</a>
						</div>
					</div>
					<div class="card shadow-sm">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="mb-0">{{ $feedback->title }}</h5>
							{{-- Consider dynamic badge colors --}}
							<span class="badge rounded-pill bg-info text-dark">{{ $feedback->status }}</span>
						</div>
						<div class="card-body">
							<p><strong>Details:</strong></p>
							{{-- Use pre-wrap to preserve formatting --}}
							<p style="white-space: pre-wrap;" class="bg-light p-3 rounded border">{{ $feedback->details }}</p>
							
							<hr class="my-4">
							
							<div class="row">
								<div class="col-md-6 mb-3 mb-md-0">
									<h6>Submitted By:</h6>
									<div class="d-flex align-items-center mb-2">
										<img src="{{ $feedback->submitter_avatar }}" alt="" class="avatar avatar-sm rounded-circle me-2"> {{-- Increased size --}}
										<span>
                                        {{-- Use submitter relationship --}}
											@if($feedback->submitter)
												<strong>{{ $feedback->submitter->name }}</strong>
												({{ $feedback->submitter->email }})
											@else
												<strong>Guest</strong>
												{{ $feedback->guest_email ? '('.$feedback->guest_email.')' : '' }}
											@endif
                                    </span>
									</div>
									<p class="mb-1"><small class="text-muted">Submitted on: {{ $feedback->created_at->format('M d, Y H:i A') }}</small></p>
									<p class="mb-0"><small class="text-muted">Last updated: {{ $feedback->updated_at->format('M d, Y H:i A') }}</small></p>
								</div>
								<div class="col-md-6">
									<h6>Votes: <span class="fw-bold fs-5">{{ $feedback->votes_count }}</span></h6>
									{{-- Display list of voters if needed --}}
									@if($feedback->votes->count() > 0)
										<p class="mb-1"><small>Voted by:</small></p>
										{{-- Tooltips or avatars could be nice here --}}
										<ul class="list-inline small mb-0">
											@foreach($feedback->votes as $vote)
												{{-- Ensure $vote->user relationship is loaded --}}
												<li class="list-inline-item border rounded px-2 py-1 mb-1">
													@if($vote->user)
														<img src="{{ $vote->user->profile_photo_url }}" alt="" class="avatar avatar-xs rounded-circle me-1">
														{{ $vote->user->name }}
													@else
														{{-- Should not happen if votes require login --}}
														Unknown Voter
													@endif
												</li>
											@endforeach
										</ul>
									@else
										<p><small class="text-muted">No votes yet.</small></p>
									@endif
								</div>
							</div>
							{{-- Add sections for comments, linked changelogs, etc. later --}}
						</div>
						<div class="card-footer text-end bg-light">
							{{-- Delete button action remains the same --}}
							<form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to permanently delete this feedback item and its votes?') }}')">
								@csrf
								@method('DELETE')
								<button type="submit" class="btn btn-sm btn-outline-danger">
									<i class="bi bi-trash me-1"></i> Delete Feedback
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	
	@include('layouts.footer')

@endsection
