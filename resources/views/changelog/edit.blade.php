@extends('layouts.admin')

@section('title', isset($changelog) ? __('default.Edit Change Log') : __('default.Create Change Log'))
@section('page-title')
    {{ isset($changelog) ? __('default.Edit Change Log') : __('default.Create Change Log') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
{{--            <h5 class="card-title mb-0">{{ isset($changelog) ? __('default.Edit Change Log') : __('default.Create Change Log') }}</h5>--}}
        </div>
        <div class="card-body">
            <form id="changelogForm" method="POST" action="{{ isset($changelog) ? route('changelogs.update', $changelog->id) : route('changelogs.store') }}">
                @csrf
                @if(isset($changelog))
                    @method('PUT')
                @endif
                
                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">{{ __('default.Title') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ isset($changelog) ? $changelog->title : old('title') }}" required>
                </div>
                
                {{-- Change Log Body using Editor.md --}}
                <div class="mb-3">
                    <label for="body" class="form-label">{{ __('default.Content') }} <span class="text-danger">*</span></label>
                    {{-- Editor.md requires a specific div structure --}}
                    <div id="editormd">
                        {{-- The textarea will be hidden and used by Editor.md --}}
                        <textarea name="body" style="display:none;">{{ isset($changelog) ? $changelog->body : old('body', "### New Changelog Entry\n\n*   Feature: Describe the new feature.\n*   Improvement: Detail the improvement made.\n*   Fix: Explain the bug fix.") }}</textarea>
                    </div>
                </div>
                

                <div class="form-check form-switch mb-3">
										<input class="form-check-input" type="checkbox" id="is_released" name="is_released" value="1" {{ (isset($changelog) && $changelog->is_released) ? 'checked' : '' }}>
										<label class="form-check-label" for="is_released">Mark as Released</label>
								</div>
                
                
                <div class="text-end"> {{-- Align buttons right --}}
                    <a href="{{ route('changelogs.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button id="saveChangeLogBtn" type="submit" class="btn btn-primary">
                        {{ isset($changelog) ? __('default.Save Change Log') : __('default.Create Change Log') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- EditorMD requires its JS to be loaded *after* the #editormd div exists --}}
    {{-- The necessary JS files are included in admin.blade.php layout --}}
    <script>
        var editorInstance; // Make instance accessible if needed elsewhere
        $(document).ready(function () {
            // Initialize EditorMD using the function from admin.blade.php
            editorInstance = initializeEditorMD("editormd");
            
            // Optional: Handle form submission to ensure editor content is synced
            // $('#changelogForm').on('submit', function() {
            //     if (editorInstance) {
            //         editorInstance.sync(); // Syncs the markdown content to the hidden textarea
            //     }
            // });
        });
    </script>
@endpush
