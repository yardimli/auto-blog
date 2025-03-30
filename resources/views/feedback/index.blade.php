@extends('layouts.app')
@section('title', 'Manage Your Feedback') {{-- Updated title --}}

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
                        {{-- Updated heading --}}
                        <h5>{{ __('Manage Feedback Submitted To You') }}</h5>
                        {{-- Users don't create feedback for themselves here --}}
                        {{-- <a href="{{ route('feedback.create') }}" class="btn btn-primary"> Create Feedback </a> --}}
                    </div>
                    
                    {{-- Filtering and Search Form --}}
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('feedback.index') }}" method="GET" class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label for="searchFilter" class="form-label">Search Title/Details:</label>
                                    <input type="search" name="search" id="searchFilter" class="form-control form-control-sm" placeholder="Search..." value="{{ $search ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="statusFilter" class="form-label">Filter by Status:</label>
                                    <select name="status" id="statusFilter" class="form-select form-select-sm">
                                        <option value="">All Statuses</option>
                                        @foreach($statuses as $s)
                                            <option value="{{ $s }}" {{ ($status ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Add sorting controls if desired --}}
                                <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'created_at' }}">
                                <input type="hidden" name="sort_dir" value="{{ $sortDir ?? 'desc' }}">
                                
                                <div class="col-md-auto">
                                    <button type="submit" class="btn btn-sm btn-secondary w-100">Filter / Search</button>
                                </div>
                                <div class="col-md-auto">
                                    <a href="{{ route('feedback.index') }}" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    {{-- Feedback List Table --}}
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if($feedbackItems->isEmpty())
                                <p class="text-center my-4 text-muted">{{ __('No feedback items found matching your criteria.') }}</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                        <tr>
                                            {{-- Add Sortable Headers Later if needed --}}
                                            <th>Title</th>
                                            <th>Submitted By</th>
                                            <th>Status</th>
                                            <th>Votes</th>
                                            <th>Submitted At</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($feedbackItems as $item)
                                            <tr>
                                                <td title="{{ $item->details }}">
                                                    <a href="{{ route('feedback.show', $item) }}">{{ Str::limit($item->title, 50) }}</a>
                                                </td>
                                                <td>
                                                    {{-- Use submitter info --}}
                                                    @if($item->submitter)
                                                        <img src="{{ $item->submitter_avatar }}" alt="" class="avatar avatar-xs rounded-circle me-1">
                                                        {{ $item->submitter->name }}
                                                    @else
                                                        <img src="{{ $item->submitter_avatar }}" alt="" class="avatar avatar-xs rounded-circle me-1">
                                                        Guest {{ $item->guest_email ? '('.$item->guest_email.')' : '' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- Consider dynamic badge colors --}}
                                                    <span class="badge rounded-pill bg-info text-dark">{{ $item->status }}</span>
                                                </td>
                                                <td>{{ $item->votes_count }}</td>
                                                <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    {{-- Action buttons now operate on feedback owned by logged-in user --}}
                                                    <form action="{{ route('feedback.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this feedback item?') }}')">
                                                        <div class="btn-group btn-group-sm" role="group" aria-label="Feedback Actions">
                                                            <a href="{{ route('feedback.edit', $item->id) }}" class="btn btn-outline-primary">Edit</a>
                                                            <a href="{{ route('feedback.show', $item->id) }}" class="btn btn-outline-secondary">View</a>
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger">Delete</button>
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
                                    {{-- Links already include query params due to appends() in controller --}}
                                    {{ $feedbackItems->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    @include('layouts.footer')

@endsection

@push('scripts')
    {{-- Add any specific JS for this page if needed --}}
@endpush
