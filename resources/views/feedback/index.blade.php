@extends('layouts.admin')

@section('title', __('Manage Feedback'))
@section('page-title', __('Manage Feedback Submitted To You'))

{{-- Keep filters within the card for better context --}}

@section('content')
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
                {{-- Example:
                <div class="col-md-3">
                    <label for="sortByFilter" class="form-label">Sort By:</label>
                    <select name="sort_by" id="sortByFilter" class="form-select form-select-sm">
                        <option value="created_at" {{ ($sortBy ?? 'created_at') == 'created_at' ? 'selected' : '' }}>Submitted Date</option>
                        <option value="votes_count" {{ ($sortBy ?? '') == 'votes_count' ? 'selected' : '' }}>Votes</option>
                        <option value="title" {{ ($sortBy ?? '') == 'title' ? 'selected' : '' }}>Title</option>
                    </select>
                    <input type="hidden" name="sort_dir" value="{{ $sortDir ?? 'desc' }}"> // Toggle this if needed
                </div>
                 --}}
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
        <div class="card-header border-bottom pb-3">
            <h5 class="card-title mb-0">{{ __('Feedback Items') }}</h5>
        </div>
        <div class="card-body">
            @if($feedbackItems->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-chat-left-dots display-4 text-muted"></i>
                    <p class="mt-3 mb-0">{{ __('No feedback items found matching your criteria.') }}</p>
                </div>
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
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($feedbackItems as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('feedback.show', $item) }}" title="{{ $item->details }}">
                                        {{ Str::limit($item->title, 50) }}
                                    </a>
                                </td>
                                <td>
                                    {{-- Use submitter info --}}
                                    @if($item->submitter)
                                        <img src="{{ $item->submitter_avatar }}" alt="" class="avatar avatar-xs rounded-circle me-1" title="{{ $item->submitter->name }}">
                                        {{ $item->submitter->name }}
                                    @else
                                        <img src="{{ $item->submitter_avatar }}" alt="" class="avatar avatar-xs rounded-circle me-1" title="Guest">
                                        Guest {{ $item->guest_email ? '('.$item->guest_email.')' : '' }}
                                    @endif
                                </td>
                                <td>
                                    {{-- Consider dynamic badge colors based on status --}}
                                    @php
                                        $statusColor = match($item->status) {
                                            'Open' => 'info',
                                            'Planned' => 'primary',
                                            'In Progress' => 'warning',
                                            'Completed' => 'success',
                                            'Closed' => 'secondary',
                                            default => 'light'
                                        };
                                    @endphp
                                    <span class="badge rounded-pill bg-{{ $statusColor }}">{{ $item->status }}</span>
                                </td>
                                <td>{{ $item->votes_count }}</td>
                                <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    {{-- Action buttons now operate on feedback owned by logged-in user --}}
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Feedback Actions">
                                        <a href="{{ route('feedback.show', $item->id) }}" class="btn btn-outline-secondary" title="View Details"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('feedback.edit', $item->id) }}" class="btn btn-outline-primary" title="Edit Status/Details"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('feedback.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this feedback item?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Feedback"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                @if ($feedbackItems->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{-- Links already include query params due to appends() in controller --}}
                        {{ $feedbackItems->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Add any specific JS for this page if needed --}}
@endpush
