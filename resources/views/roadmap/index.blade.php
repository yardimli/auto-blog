@extends('layouts.admin')

@section('title', __('default.Roadmap'))
@section('page-title', __('default.Roadmap'))

@section('top-bar-actions')
    {{-- Add button when functionality is ready --}}
    {{-- <a href="#" class="btn btn-primary btn-sm ms-3">
         <i class="bi bi-plus-lg me-1"></i> New Roadmap Item
    </a> --}}
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom pb-3">
{{--            <h5 class="card-title mb-0">{{ __('default.Roadmap') }}</h5>--}}
        </div>
        <div class="card-body text-center py-5">
            <i class="bi bi-signpost-split display-1 text-muted"></i>
            <h5 class="mt-3">Roadmap Coming Soon</h5>
            <p class="text-muted">Plan and share your product's future direction here.</p>
            {{-- Link to feedback or planning tools --}}
            {{-- <a href="{{ route('feedback.index') }}" class="btn btn-sm btn-outline-secondary">View Feedback</a> --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Add JS for Roadmap management when ready
        });
    </script>
@endpush
