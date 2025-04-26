@extends('layouts.admin')

@section('title', __('default.Change Log'))
@section('page-title', __('default.Change Log'))

@section('top-bar-actions')
    <a href="{{ route('changelogs.create') }}" class="btn btn-primary btn-sm ms-3">
        <i class="bi bi-plus-lg me-1"></i> {{ __('default.Create Change Log') }}
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom pb-3">
{{--            <h5 class="card-title mb-0">{{ __('default.Change Log') }}</h5>--}}
            {{-- Add filtering/sorting options here if needed --}}
        </div>
        <div class="card-body">
            @if($changelogs->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-list-check display-4 text-muted"></i>
                    <p class="mt-3 mb-0">{{ __('default.No changelogs found') }}</p>
                    <p><a href="{{ route('changelogs.create') }}">Create your first changelog entry</a>.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                        <tr>
                            <th>{{ __('default.Title') }}</th>
                            <th>{{ __('default.Author') }}</th>
                            <th>{{ __('default.Status') }}</th>
                            <th>{{ __('default.Released At') }}</th>
                            <th>{{ __('default.Created At') }}</th>
                            <th class="text-end">{{ __('default.Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($changelogs as $changelog)
                            <tr>
                                <td>
                                    <a href="{{ route('changelogs.edit', $changelog->id) }}" title="{{ $changelog->title }}">
                                        {{ Str::limit($changelog->title, 60) }}
                                    </a>
                                </td>
                                <td>{{ $changelog->user->name ?? 'N/A' }}</td>
                                <td>
                                    {{-- Bootstrap Toggle Switch --}}
                                    <input class="statusToggle" type="checkbox"
                                           {{ $changelog->is_released ? 'checked' : '' }}
                                           data-id="{{$changelog->id}}"
                                           data-toggle="toggle" data-size="sm"
                                           data-onlabel="Released" data-offlabel="Draft"
                                           data-onstyle="success" data-offstyle="warning">
                                </td>
                                <td id="released_at_{{$changelog->id}}">
                                    {{ $changelog->released_at ? $changelog->released_at->format('Y-m-d H:i') : '---' }}
                                </td>
                                <td>{{ $changelog->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Changelog Actions">
                                        <a href="{{ route('changelogs.edit', $changelog->id) }}" class="btn btn-outline-primary" title="{{ __('default.Edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('changelogs.destroy', $changelog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('default.Are you sure you want to delete this log?') }}')">
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
                @if ($changelogs->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $changelogs->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Re-initialize toggles after potential AJAX calls or if loaded dynamically
            // This might be needed if using something like Livewire or HTMX later.
            // For now, the initial $(document).ready in admin.blade.php should suffice.
            // $('.statusToggle').bootstrapToggle(); // Keep this if needed for re-init
            
            $('.statusToggle').change(function () {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var is_released = checkbox.prop('checked');
                var releasedAtCell = $('#released_at_' + id);
                
                // Optional: Provide visual feedback while saving
                checkbox.bootstrapToggle('disable');
                releasedAtCell.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                
                $.ajax({
                    url: `/changelogs/toggleReleased/${id}`, // Use template literal for clarity
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id, // Though redundant as it's in URL
                        is_released: is_released
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        releasedAtCell.text(response.time || '---'); // Update time or show placeholder
                        showNotification('Status updated successfully.', 'success');
                    },
                    error: function (xhr) {
                        // Revert the toggle on error
                        checkbox.prop('checked', !is_released).bootstrapToggle('update');
                        releasedAtCell.text('Error'); // Indicate error
                        console.error("Error toggling status:", xhr.responseText);
                        showNotification(xhr.responseJSON?.message || 'Error updating status.', 'danger');
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
