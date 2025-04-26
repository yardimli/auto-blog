@extends('layouts.admin') {{-- Changed from layouts.app --}}

@section('title', __('Help Articles')) {{-- Added title section --}}
@section('page-title', __('Help Articles')) {{-- Added page-title section --}}

{{-- Moved Create button to top bar actions --}}
@section('top-bar-actions')
    <a href="{{ route('helps.create') }}" class="btn btn-primary btn-sm ms-3">
        <i class="bi bi-plus-lg me-1"></i> {{ __('Create Help Article') }}
    </a>
@endsection

@section('content') {{-- Added content section --}}

{{-- Removed main and container divs, admin layout handles this --}}
{{-- Removed row/col divs, card directly in content --}}

{{-- Display success messages (optional, admin layout might have a standard way) --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header border-bottom pb-3">
        {{-- <h5 class="card-title mb-0">{{ __('default.Help Articles') }}</h5> --}}
        {{-- Header can be simpler as title is in page-title --}}
    </div>
    <div class="card-body">
        @if($helps->isEmpty())
            <div class="text-center py-5"> {{-- Added standard empty state styling --}}
                <i class="bi bi-journal-text display-4 text-muted"></i>
                <p class="mt-3 mb-0">{{ __('No help articles found') }}</p>
                <p><a href="{{ route('helps.create') }}">Create your first help article</a>.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle"> {{-- Added align-middle --}}
                    <thead>
                    <tr>
                        <th>{{ __('default.Title') }}</th>
                        <th>{{ __('default.Category') }}</th>
                        <th>{{ __('default.Status') }}</th>
                        <th>{{ __('default.Author') }}</th>
                        <th>{{ __('default.Published At') }}</th>
                        <th>{{ __('default.Created At') }}</th>
                        <th class="text-end">{{ __('default.Actions') }}</th> {{-- Added text-end --}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($helps as $help)
                        <tr>
                            <td>{{ Str::limit($help->title, 50) }}</td>
                            <td>{{ Str::limit($help->category->category_name) }}</td>
                            <td>
                                {{-- Bootstrap Toggle Switch --}}
                                {{-- Ensure bootstrap5-toggle JS/CSS is loaded in layouts.admin --}}
                                <input class="statusToggle" type="checkbox" {{ $help->is_published ? 'checked' : '' }}
                                data-id="{{$help->id}}"
                                       data-toggle="toggle" data-size="sm"
                                       data-onlabel="Published" data-offlabel="Draft"
                                       data-onstyle="success" data-offstyle="warning">
                            </td>
                            <td>{{ $help->user->name }}</td>
                            <td id="published_at_{{$help->id}}">{{ ($help->published_at) ? $help->published_at->format('Y-m-d H:i') : '---' }}</td> {{-- Show placeholder --}}
                            <td>{{ $help->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                {{-- Use button group for consistency --}}
                                <div class="btn-group btn-group-sm" role="group" aria-label="Help Article Actions">
                                    <a href="{{ route('helps.edit', $help->id) }}" class="btn btn-outline-primary" title="{{ __('default.Edit') }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('helps.destroy', $help->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('default.Are you sure you want to delete this help article?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="{{ __('default.Delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if ($helps->hasPages()) {{-- Added check for pages --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $helps->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @endif
    </div>
</div>

@endsection {{-- End content section --}}

{{-- Removed @include('layouts.footer') --}}

@push('scripts')
    {{-- Ensure bootstrap5-toggle JS is loaded via layouts.admin or uncomment below --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.1.1/js/bootstrap5-toggle.ecmas.min.js"></script> --}}
    <script>
        $(document).ready(function () {
            // Initialize toggles if not done globally in admin layout
            // $('.statusToggle').bootstrapToggle();
            
            $('.statusToggle').change(function () {
                var checkbox = $(this); // Cache the jQuery object
                var id = checkbox.data('id');
                var is_published = checkbox.prop('checked');
                var publishedAtCell = $('#published_at_' + id);
                
                // Optional: Visual feedback while saving
                checkbox.bootstrapToggle('disable');
                publishedAtCell.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                
                $.ajax({
                    url: `/helps/togglePublished/${id}`, // Use template literal or ensure route is correct
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        // id: id, // Redundant as it's in URL
                        is_published: is_published // Send boolean directly
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        publishedAtCell.text(response.time || '---'); // Update time or show placeholder
                        // Use admin layout's notification system if available
                        // showNotification('Status updated successfully.', 'success');
                    },
                    error: function (xhr) {
                        // Revert the toggle on error
                        checkbox.prop('checked', !is_published).bootstrapToggle('update');
                        publishedAtCell.text('Error'); // Indicate error
                        console.error("Error toggling status:", xhr.responseText);
                        // Use admin layout's notification system if available
                        // showNotification(xhr.responseJSON?.message || 'Error updating status.', 'danger');
                    },
                    complete: function () {
                        // Re-enable the toggle after request completes
                        checkbox.bootstrapToggle('enable');
                    }
                });
            });
        });
    </script>
@endpush
