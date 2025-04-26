@extends('layouts.admin')

@section('title', 'View Feedback Details')
@section('page-title', 'Feedback Details')

@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-10"> {{-- Wider column for details --}}
			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between align-items-center flex-wrap"> {{-- Allow wrapping --}}
					<h5 class="mb-0 me-3">{{ $feedback->title }}</h5>
					<div class="d-flex align-items-center gap-2 mt-2 mt-md-0"> {{-- Buttons group --}}
						{{-- Status Badge --}}
						@php
							$statusColor = match($feedback->status) {
									'Open' => 'info', 'Planned' => 'primary', 'In Progress' => 'warning',
									'Completed' => 'success', 'Closed' => 'secondary', default => 'light'
							};
						@endphp
						<span class="badge rounded-pill fs-6 bg-{{ $statusColor }} me-2">{{ $feedback->status }}</span>
						
						{{-- Action Buttons --}}
						<a href="{{ route('feedback.edit', $feedback->id) }}" class="btn btn-sm btn-outline-primary">
							<i class="bi bi-pencil me-1"></i> Edit
						</a>
						<a href="{{ route('feedback.index') }}" class="btn btn-sm btn-outline-secondary">
							<i class="bi bi-arrow-left me-1"></i> Back to List
						</a>
					</div>
				</div>
				<div class="card-body">
					<p><strong>Details:</strong></p>
					{{-- Use pre-wrap to preserve formatting --}}
					<div class="bg-body-tertiary p-3 rounded border mb-4" style="white-space: pre-wrap;">{{ $feedback->details }}</div>
					
					<hr class="my-4">
					
					<div class="row">
						<div class="col-md-6 mb-3 mb-md-0">
							<h6>Submitted By:</h6>
							<div class="d-flex align-items-center mb-2">
								<img src="{{ $feedback->submitter_avatar }}" alt="" class="avatar avatar-sm rounded-circle me-2"> {{-- Increased size --}}
								<span>
                                    {{-- Use submitter relationship --}}
									@if($feedback->submitter)
										<strong>{{ $feedback->submitter->name }}</strong> ({{ $feedback->submitter->email }})
									@else
										<strong>Guest</strong> {{ $feedback->guest_email ? '('.$feedback->guest_email.')' : '' }}
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
								<ul class="list-unstyled small mb-0">
									@foreach($feedback->votes as $vote)
										{{-- Ensure $vote->user relationship is loaded --}}
										<li class="mb-1 d-flex align-items-center">
											@if($vote->user)
												<img src="{{ $vote->user->profile_photo_url }}" alt="" class="avatar avatar-xs rounded-circle me-1">
												{{ $vote->user->name }}
											@else
												{{-- Should not happen if votes require login --}}
												<i class="bi bi-person-circle me-1 text-muted"></i> Unknown Voter
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
				<div class="card-footer text-end bg-body-tertiary"> {{-- Use tertiary bg --}}
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
@endsection
