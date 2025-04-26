@extends('layouts.admin')

@section('title', __('default.Knowledge Base'))
@section('page-title', __('default.Knowledge Base'))

@section('top-bar-actions')
    {{-- Add button when functionality is ready --}}
    {{-- <a href="#" class="btn btn-primary btn-sm ms-3">
         <i class="bi bi-plus-lg me-1"></i> New KB Article
    </a> --}}
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom pb-3">
{{--            <h5 class="card-title mb-0">{{ __('default.Knowledge Base') }}</h5>--}}
        </div>
        <div class="card-body text-center py-5">
            <i class="bi bi-book display-1 text-muted"></i>
            <h5 class="mt-3">Knowledge Base Coming Soon</h5>
            <p class="text-muted">This section is under construction. You'll be able to manage your help articles here.</p>
            {{-- Link to settings or help docs if applicable --}}
            {{-- <a href="#" class="btn btn-sm btn-primary">Learn More</a> --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Add JS for KB management when ready
        });
    </script>
@endpush
