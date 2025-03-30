@extends('layouts.app')

@section('title', 'View Feedback')

@section('content')
	<main>
		<div class="container mt-4 mb-5">
			<div class="row justify-content-center">
				<div class="col-lg-9">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<h4 class="mb-0">Feedback Details</h4>
						<div>
							<a href="{{ route('feedback.edit', $feedback->id) }}" class="btn btn-sm btn-primary">Edit</a>
							<a href="{{ route('feedback.index') }}" class="btn btn-sm btn-outline-secondary">Back to List</a>
						</div>
					</div>
					
					<div class="card">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="mb-0">{{ $feedback->title }}</h5>
							<span class="badge bg-info">{{ $feedback->status }}</span>
						</div>
						<div class="card-body">
							<p><strong>Details:</strong></p>
							<p style="white-space: pre-wrap;">{{ $feedback->details }}</p> {{-- Preserve whitespace/newlines --}}
							<hr>
							<div class="row">
								<div class="col-md-6">
									<p><strong>Submitted By:</strong></p>
									<div class="d-flex align-items-center mb-2">
										<img src="{{ $feedback->submitter_avatar }}" alt="" class="avatar avatar-xs rounded-circle me-2">
										<span>
                                         @if($feedback->user)
												{{ $feedback->user->name }} ({{ $feedback->user->email }})
											@else
												Guest {{ $feedback->guest_email ? '('.$feedback->guest_email.')' : '' }}
											@endif
                                     </span>
									</div>
									<p><small class="text-muted">Submitted on: {{ $feedback->created_at->format('M d, Y H:i A') }}</small></p>
									<p><small class="text-muted">Last updated: {{ $feedback->updated_at->format('M d, Y H:i A') }}</small></p>
								</div>
								<div class="col-md-6">
									<p><strong>Votes:</strong> {{ $feedback->votes_count }}</p>
									{{-- Display list of voters if needed --}}
									@if($feedback->votes->count() > 0)
										<p><small>Voted by:</small></p>
										<ul class="list-inline list-inline-dotted small">
											@foreach($feedback->votes as $vote)
												<li class="list-inline-item">{{ $vote->user->name }}</li>
											@endforeach
										</ul>
									@endif
								</div>
							</div>
							
							{{-- Add sections for comments, linked changelogs, etc. later --}}
						
						</div>
						<div class="card-footer text-end">
							<form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this feedback item?') }}')">
								@csrf
								@method('DELETE')
								<button type="submit" class="btn btn-sm btn-outline-danger">Delete Feedback</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection
