@extends('layouts.app')

@section('title', 'Edit Feedback')

@section('content')
	<main>
		<div class="container mt-4 mb-5">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="d-flex justify-content-between align-items-center mb-4">
						<h4 class="mb-0">Edit Feedback</h4>
						<a href="{{ route('feedback.index') }}" class="btn btn-sm btn-outline-secondary">Back to List</a>
					</div>
					
					
					<div class="card">
						<div class="card-body">
							<form action="{{ route('feedback.update', $feedback->id) }}" method="POST">
								@csrf
								@method('PUT')
								
								{{-- Title --}}
								<div class="mb-3">
									<label for="title" class="form-label">Title</label>
									<input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $feedback->title) }}" required>
									@error('title')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								
								{{-- Details --}}
								<div class="mb-3">
									<label for="details" class="form-label">Details</label>
									<textarea class="form-control @error('details') is-invalid @enderror" id="details" name="details" rows="5" required>{{ old('details', $feedback->details) }}</textarea>
									@error('details')
									<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								
								{{-- Status --}}
								<div class="mb-3">
									<label for="status" class="form-label">Status</label>
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
									<div class="d-flex align-items-center">
										<img src="{{ $feedback->submitter_avatar }}" alt="" class="avatar avatar-xs rounded-circle me-2">
										<span>
                                         @if($feedback->user)
												{{ $feedback->user->name }} ({{ $feedback->user->email }})
											@else
												Guest {{ $feedback->guest_email ? '('.$feedback->guest_email.')' : '' }}
											@endif
                                         - {{ $feedback->created_at->format('Y-m-d H:i') }}
                                     </span>
									</div>
								</div>
								
								<div class="mb-3">
									<label class="form-label">Votes</label>
									<p>{{ $feedback->votes_count }}</p>
								</div>
								
								
								{{-- Add fields for assigning owner, category, tags if needed --}}
								
								<div class="d-flex justify-content-end">
									<a href="{{ route('feedback.index') }}" class="btn btn-link text-secondary me-2">Cancel</a>
									<button type="submit" class="btn btn-primary">Update Feedback</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
@endsection
