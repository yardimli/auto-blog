@extends('layouts.admin') {{-- Changed from layouts.app --}}

@section('title', isset($help) ? __('default.Edit Help Article') : __('default.Create Help Article'))
@section('page-title', isset($help) ? __('default.Edit Help Article') : __('default.Create Help Article'))

@section('content')
	
	<div class="card">
		<div class="card">
			<div class="card-header">
				{{--            <h5 class="card-title mb-0">{{ isset($help) ? __('default.Edit Help Article') : __('default.Create Help Article') }}</h5>--}}
			</div>
			<div class="card-body">
				<form id="articleForm" method="POST"
				      action="{{ isset($help) ? route('helps.update', $help->id) : route('helps.store') }}">
					@csrf
					@if(isset($help))
						@method('PUT')
					@endif
					{{-- Hidden field for order - consider if this needs UI --}}
					<input type="hidden" name="order" value="{{ isset($help) ? $help->order : '0' }}">
					
					<div class="card-body">
						<!-- Title -->
						<div class="mb-3">
							<label for="title" class="form-label">{{ __('default.Title') }} <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="title" name="title"
							       value="{{ isset($help) ? $help->title : old('title') }}" required>
						</div>
						
						<!-- Category -->
						<div class="mb-3">
							<label for="category_id" class="form-label">{{ __('Category') }} <span
									class="text-danger">*</span></label> {{-- Changed label ID --}}
							<select class="form-select" id="category_id" name="category_id" required>
								<option value=""
								        disabled {{ !isset($help) && !old('category_id') ? 'selected' : '' }}>{{ __('Select Category') }}</option> {{-- Improved default selection --}}
								@foreach($categories as $category)
									<option
										value="{{ $category->id }}" {{ (isset($help) && $help->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
										{{ $category->category_name }}
									</option>
								@endforeach
							</select>
						</div>
						
						{{-- Help Article Body using EditorMD --}}
						<div class="mb-3">
							<label for="body" class="form-label">{{ __('default.Content') }} <span class="text-danger">*</span></label>
							{{-- Editor.md requires a specific div structure --}}
							<div id="editormd">
								{{-- The textarea will be hidden and used by Editor.md --}}
								<textarea name="body" style="display:none;">{{ isset($help) ? $help->body : old('body', "### New Help Article\n\nWrite your help content here using Markdown.") }}</textarea>
							</div>
						</div>
						
						
						<div class="mb-3">
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" id="is_published" name="is_published"
								       value="1" {{ (isset($help) && $help->is_published) || old('is_published') ? 'checked' : '' }}>
								<label class="form-check-label" for="is_published">
									{{ __('default.Publish Article') }}
								</label>
							</div>
						</div>
					
					</div> {{-- End card-body --}}
					
					<div class="card-footer text-end"> {{-- Added card-footer for buttons --}}
						<a href="{{ route('helps.index') }}" class="btn btn-secondary me-2">{{ __('default.Cancel') }}</a>
						<button id="{{isset($help) ? 'updateHelp' : 'createHelp'}}" type="submit" class="btn btn-primary">
							{{ isset($help) ? __('default.Save Help Article') : __('default.Create Help Article') }}
						</button>
					</div> {{-- End card-footer --}}
				
				</form>
			</div>
		</div> {{-- End card --}}
		
		@endsection {{-- End content section --}}
		
		@push('scripts')
			<script>
				$(document).ready(function () {
					
					var editorInstance; // Make instance accessible if needed elsewhere
					$(document).ready(function () {
						// Initialize EditorMD using the function from admin.blade.php
						editorInstance = initializeEditorMD("editormd");
						
						// Optional: Handle form submission to ensure editor content is synced
						// $('#articleForm').on('submit', function() {
						//     if (editorInstance) {
						//         editorInstance.sync(); // Syncs the markdown content to the hidden textarea
						//     }
						// });
					});
					
					// Optional: Force update on form submit just in case
					$('#articleForm').submit(function () {
						$('textarea[name="body"]').val(editor.getMarkdown());
					});
				});
			</script>
	@endpush
