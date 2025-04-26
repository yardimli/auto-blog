@extends('layouts.admin')

@section('title', __('default.Manage Categories'))
@section('page-title', __('default.Manage Categories'))

@section('top-bar-actions')
	<a href="#" class="btn btn-sm btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
		<i class="bi bi-plus-lg me-1"></i> {{__('default.Add New Category')}}
	</a>
@endsection

@section('content')
	<div class="card">
		<div class="card-header border-bottom pb-3">
			<h5 class="card-title mb-0">{{__('default.Manage Categories')}}</h5>
			{{-- Removed paragraph and button --}}
		</div>
		<div class="card-body">
			@if($categories->isEmpty())
				<div class="text-center py-5">
					<i class="bi bi-folder2-open display-4 text-muted"></i>
					<p class="mt-3 mb-0">{{ __('default.No categories found.') }}</p>
					<p><a href="#" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add your first category</a>.</p>
				</div>
			@else
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
						<tr>
							<th>{{__('default.Category Name')}}</th>
							<th>{{__('default.Language')}}</th>
							<th>{{__('default.Parent Category')}}</th>
							<th class="text-end">{{__('default.Actions')}}</th>
						</tr>
						</thead>
						<tbody>
						@foreach($categories as $category)
							<tr>
								<td class="align-middle">{{ $category->category_name }}</td>
								<td class="align-middle">{{ $category->language->language_name }}</td>
								<td class="align-middle">{{ $category->parent ? $category->parent->category_name : '-' }}</td>
								<td class="text-end align-middle">
									<button class="btn btn-sm btn-outline-primary edit-category me-1" data-id="{{ $category->id }}" title="{{__('default.Edit')}}">
										<i class="bi bi-pencil"></i>
									</button>
									<button class="btn btn-sm btn-outline-danger delete-category" data-id="{{ $category->id }}" title="{{__('default.Delete')}}">
										<i class="bi bi-trash"></i>
									</button>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			@endif
		</div>
	</div>
	
	<!-- Add Category Modal -->
	<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addCategoryModalLabel">{{__('default.Add New Category')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form action="{{ route('categories.store') }}" method="POST" id="addCategoryForm">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label for="category_name" class="form-label">{{__('default.Category Name')}}</label>
							<input type="text" class="form-control" id="category_name" name="category_name" required>
						</div>
						{{-- Slug is generated automatically in controller, no need for input usually --}}
						{{-- <div class="mb-3">
								<label for="category_slug" class="form-label">{{__('default.Slug')}}</label>
								<input type="text" class="form-control" id="category_slug" name="category_slug">
						</div> --}}
						<div class="mb-3">
							<label for="language_id" class="form-label">{{__('default.Language')}}</label>
							<select class="form-select" id="language_id" name="language_id" required>
								<option value="" disabled selected>{{ __('default.Select Language') }}</option>
								@foreach($languages as $language)
									@if($language->active) {{-- Only show active languages --}}
									<option value="{{ $language->id }}">{{ $language->language_name }}</option>
									@endif
								@endforeach
							</select>
						</div>
						<div class="mb-3">
							<label for="parent_id" class="form-label">{{__('default.Parent Category')}}</label>
							<select class="form-select" id="parent_id" name="parent_id">
								<option value="">{{__('default.None')}}</option>
								{{-- Populate dynamically based on selected language? Or show all? Showing all for now. --}}
								@foreach($categories as $cat)
									<option value="{{ $cat->id }}">{{ $cat->category_name }} ({{ $cat->language->language_name }})</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3">
							<label for="category_description" class="form-label">{{__('default.Description')}} <span class="text-muted small">(Optional)</span></label>
							<textarea class="form-control" id="category_description" name="category_description" rows="3"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('default.Close')}}</button>
						<button type="submit" class="btn btn-primary">{{__('default.Add Category')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Edit Category Modal -->
	<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editCategoryModalLabel">{{__('default.Edit Category')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="editCategoryForm" method="POST"> {{-- Action set via JS --}}
					@csrf
					@method('PUT')
					<div class="modal-body">
						<div class="mb-3">
							<label for="edit_category_name" class="form-label">{{__('default.Category Name')}}</label>
							<input type="text" class="form-control" id="edit_category_name" name="category_name" required>
						</div>
						{{-- Slug generated in controller --}}
						{{-- <div class="mb-3">
								<label for="edit_category_slug" class="form-label">{{__('default.Slug')}}</label>
								<input type="text" class="form-control" id="edit_category_slug" name="category_slug">
						</div> --}}
						<div class="mb-3">
							<label for="edit_language_id" class="form-label">{{__('default.Language')}}</label>
							<select class="form-select" id="edit_language_id" name="language_id" required>
								<option value="" disabled>{{ __('default.Select Language') }}</option>
								@foreach($languages as $language)
									@if($language->active)
										<option value="{{ $language->id }}">{{ $language->language_name }}</option>
									@endif
								@endforeach
							</select>
						</div>
						<div class="mb-3">
							<label for="edit_parent_id" class="form-label">{{__('default.Parent Category')}}</label>
							<select class="form-select" id="edit_parent_id" name="parent_id">
								<option value="">{{__('default.None')}}</option>
								{{-- Need to exclude the current category being edited from this list --}}
								@foreach($categories as $cat)
									{{-- JS will need to handle disabling/hiding the current category --}}
									<option value="{{ $cat->id }}">{{ $cat->category_name }} ({{ $cat->language->language_name }})</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3">
							<label for="edit_category_description" class="form-label">{{__('default.Description')}} <span class="text-muted small">(Optional)</span></label>
							<textarea class="form-control" id="edit_category_description" name="category_description" rows="3"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('default.Close')}}</button>
						<button type="submit" class="btn btn-primary">{{__('default.Update Category')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		$(document).ready(function () {
			// Handle Add Form Submission via AJAX
			$('#addCategoryForm').on('submit', function (e) {
				e.preventDefault();
				const form = $(this);
				const submitButton = form.find('button[type="submit"]');
				submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Adding...');
				
				$.ajax({
					url: form.attr('action'),
					method: 'POST',
					data: form.serialize(),
					dataType: 'json', // Expect JSON response
					success: function (response) {
						$('#addCategoryModal').modal('hide');
						form[0].reset();
						window.location.reload(); // Simple reload
					},
					error: function (xhr) {
						console.error(xhr.responseText);
						showNotification(xhr.responseJSON?.message || 'Error adding category. Please check input.', 'danger');
						submitButton.prop('disabled', false).text('{{__('default.Add Category')}}');
					}
				});
			});
			
			// Handle Edit Button Click
			$('.edit-category').click(function () {
				const categoryId = $(this).data('id');
				const modal = $('#editCategoryModal');
				const form = $('#editCategoryForm');
				
				$.ajax({
					url: `/categories/${categoryId}/edit`, // Ensure this route returns JSON
					method: 'GET',
					success: function (data) {
						form.attr('action', `/categories/${categoryId}`); // Set form action URL
						modal.find('#edit_category_name').val(data.category_name);
						modal.find('#edit_language_id').val(data.language_id);
						modal.find('#edit_parent_id').val(data.parent_id);
						modal.find('#edit_category_description').val(data.category_description);
						
						// Disable the current category in the parent dropdown
						modal.find('#edit_parent_id option').prop('disabled', false); // Re-enable all first
						modal.find(`#edit_parent_id option[value="${categoryId}"]`).prop('disabled', true);
						
						modal.modal('show');
					},
					error: function (xhr) {
						console.error('Error fetching category data:', xhr);
						showNotification('Error fetching category data.', 'danger');
					}
				});
			});
			
			// Handle Edit Form Submission via AJAX
			$('#editCategoryForm').on('submit', function (e) {
				e.preventDefault();
				const form = $(this);
				const submitButton = form.find('button[type="submit"]');
				submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Updating...');
				
				$.ajax({
					url: form.attr('action'),
					method: 'POST', // Method override handled by @method('PUT')
					data: form.serialize(),
					dataType: 'json', // Expect JSON response
					success: function (response) {
						$('#editCategoryModal').modal('hide');
						window.location.reload(); // Simple reload
					},
					error: function (xhr) {
						console.error(xhr.responseText);
						showNotification(xhr.responseJSON?.message || 'Error updating category. Please check input.', 'danger');
						submitButton.prop('disabled', false).text('{{__('default.Update Category')}}');
					}
				});
			});
			
			
			// Handle Delete Category
			$('.delete-category').click(function () {
				if (confirm('{{ __("default.Are you sure you want to delete this category?") }}\n{{ __("default.Cannot delete category with child categories") }}\n{{ __("default.Cannot delete category with associated articles") }}')) {
					const categoryId = $(this).data('id');
					const button = $(this);
					button.prop('disabled', true);
					
					$.ajax({
						url: `/categories/${categoryId}`,
						type: 'DELETE',
						data: {"_token": "{{ csrf_token() }}"},
						dataType: 'json', // Expect JSON response (even if null on success)
						success: function () {
							button.closest('tr').fadeOut(300, function() { $(this).remove(); });
							showNotification('Category deleted successfully', 'success');
						},
						error: function (xhr) {
							const response = xhr.responseJSON;
							showNotification(response.message || 'Error deleting category', 'danger');
							button.prop('disabled', false);
						}
					});
				}
			});
		});
	</script>
@endpush
