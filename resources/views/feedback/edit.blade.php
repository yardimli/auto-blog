@extends('layouts.admin')

@section('title', 'Edit Feedback')
@section('page-title', 'Edit Feedback Item')

@section('content')
	<div class="row justify-content-center">
		<div class="col-lg-9"> {{-- Wider column for edit form --}}
			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="mb-0">Edit Feedback: {{ Str::limit($feedback->title, 40) }}</h5>
					<a href="{{ route('feedback.index') }}" class="btn btn-sm btn-outline-secondary">
						<i class="bi bi-arrow-left me-1"></i> Back to List
					</a>
				</div>
				<div class="card-body">
					{{-- Form action points to the update route for this specific item --}}
					<form action="{{ route('feedback.update', $feedback->id) }}" method="POST">
						@csrf
						@method('PUT')
						
						{{-- Title --}}
						<div class="mb-3">
							<label for="title" class="form-label">Title <span class="text-danger">*</span></label>
							<input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $feedback->title) }}" required>
							@error('title')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						
						{{-- Details --}}
						<div class="mb-3">
							<label for="details" class="form-label">Details <span class="text-danger">*</span></label>
							<textarea class="form-control @error('details') is-invalid @enderror" id="details" name="details" rows="5" required>{{ old('details', $feedback->details) }}</textarea>
							@error('details')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						
						{{-- Status --}}
						<div class="mb-3">
							<label for="status" class="form-label">Status <span class="text-danger">*</span></label>
							<select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
								@foreach($statuses as $s)
									<option value="{{ $s }}" {{ old('status', $feedback->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
								@endforeach
							</select>
							@error('status')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						
						{{-- Submitted By Info (Readonly) --}}
						<div class="mb-3">
							<label class="form-label">Submitted By</label>
							<div class="d-flex align-items-center p-2 bg-body-tertiary rounded border">
								<img src="{{ $feedback->submitter_avatar }}" alt="" class="avatar avatar-xs rounded-circle me-2">
								<span>
                                    {{-- Use submitter relationship --}}
									@if($feedback->submitter)
										{{ $feedback->submitter->name }} ({{ $feedback->submitter->email }})
									@else
										Guest {{ $feedback->guest_email ? '('.$feedback->guest_email.')' : '' }}
									@endif
                                    - Submitted: {{ $feedback->created_at->format('Y-m-d H:i') }}
                                </span>
							</div>
						</div>
						
						<div class="mb-4">
							<label class="form-label">Votes</label>
							<p class="fs-5 fw-bold mb-0">{{ $feedback->votes_count }}</p>
						</div>
						
						{{-- Add fields for assigning owner, category, tags if needed --}}
						
						<div class="d-flex justify-content-end border-top pt-3 mt-3">
							<a href="{{ route('feedback.index') }}" class="btn btn-secondary me-2">Cancel</a>
							<button type="submit" class="btn btn-primary">Update Feedback</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
