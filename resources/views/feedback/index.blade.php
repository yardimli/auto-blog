@extends('layouts.app')

@section('title', 'Admin Feedback')

@section('content')
    <main>
        <div class="container" style="min-height: calc(88vh);">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="row mt-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5>{{ __('Admin Feedback Management') }}</h5>
                        {{-- Optional: Add button if needed <a href="{{ route('feedback.create') }}" class="btn btn-primary"> Create Feedback </a> --}}
                    </div>
                    
                    {{-- Filtering Form --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('feedback.index') }}" method="GET" class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label for="statusFilter" class="form-label">Filter by Status:</label>
                                    <select name="status" id="statusFilter" class="form-select form-select-sm">
                                        <option value="">All Statuses</option>
                                        @foreach($statuses as $s)
                                            <option value="{{ $s }}" {{ $status == $s ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Add other filters here (e.g., search, user) --}}
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-sm btn-secondary w-100">Filter</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('feedback.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    {{-- Feedback List Table --}}
                    <div class="card">
                        <div class="card-body">
                            @if($feedbackItems->isEmpty())
                                <p class="text-center my-3">{{ __('No feedback found matching your criteria.') }}</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            {{-- Add Sortable Headers Later if needed --}}
                                            <th>Title</th>
                                            <th>Submitted By</th>
                                            <th>Status</th>
                                            <th>Votes</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($feedbackItems as $item)
                                            <tr>
                                                <td style="vertical-align: middle;" title="{{ $item->details }}">
                                                    <a href="{{ route('feedback.show', $item) }}">{{ Str::limit($item->title, 50) }}</a>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    @if($item->user)
                                                        <img src="{{ $item->submitter_avatar }}" alt="" class="avatar avatar-xs rounded-circle me-2">
                                                        {{ $item->user->name }}
                                                    @else
                                                        <img src="{{ $item->submitter_avatar }}" alt="" class="avatar avatar-xs rounded-circle me-2">
                                                        Guest {{ $item->guest_email ? '('.$item->guest_email.')' : '' }}
                                                    @endif
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <span class="badge bg-info">{{ $item->status }}</span> {{-- Use different bg colors based on status? --}}
                                                </td>
                                                <td style="vertical-align: middle;">{{ $item->votes_count }}</td>
                                                <td style="vertical-align: middle;">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                                <td style="vertical-align: middle;">
                                                    <form action="{{ route('feedback.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this feedback item?') }}')">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="{{ route('feedback.edit', $item->id) }}" class="btn btn-primary">Edit</a>
                                                            <a href="{{ route('feedback.show', $item->id) }}" class="btn btn-secondary">View</a>
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- Pagination --}}
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $feedbackItems->appends(request()->query())->links() }} {{-- Keep filters on pagination --}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    {{-- Add any specific JS for admin feedback page if needed --}}
@endpush
