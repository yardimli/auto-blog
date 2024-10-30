@extends('layouts.settings')

@section('settings-content')
	
	<!-- Categories tab START -->
<div class="tab-pane" id="nav-setting-tab-3">
	<div class="card">
		<div class="card-header border-0 pb-0">
			<h5 class="card-title">{{__('default.Manage Categories')}}</h5>
			<div class="d-flex justify-content-between align-items-center">
				<p class="mb-0">{{__('default.Organize your content with categories')}}</p>
				<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
				   data-bs-target="#addCategoryModal">
					{{__('default.Add New Category')}}
				</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table">
					<thead>
					<tr>
						<th>{{__('default.Category Name')}}</th>
						<th>{{__('default.Language')}}</th>
						<th>{{__('default.Parent Category')}}</th>
						<th>{{__('default.Actions')}}</th>
					</tr>
					</thead>
					<tbody>
					@foreach($categories as $category)
						<tr>
							<td>{{ $category->category_name }}</td>
							<td>{{ $category->language->language_name }}</td>
							<td>{{ $category->parent ? $category->parent->category_name : '-' }}</td>
							<td>
								<button class="btn btn-sm btn-primary edit-category" data-id="{{ $category->id }}">
									{{__('default.Edit')}}
								</button>
								<button class="btn btn-sm btn-danger delete-category" data-id="{{ $category->id }}">
									{{__('default.Delete')}}
								</button>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Categories tab END -->
	
	<!-- Add Category Modal -->
	<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
	     aria-hidden="true">
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
						<div class="mb-3">
							<label for="category_slug" class="form-label">{{__('default.Slug')}}</label>
							<input type="text" class="form-control" id="category_slug" name="category_slug">
						</div>
						<div class="mb-3">
							<label for="language_id" class="form-label">{{__('default.Language')}}</label>
							<select class="form-select" id="language_id" name="language_id" required>
								@foreach($languages as $language)
									<option value="{{ $language->id }}">{{ $language->language_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3">
							<label for="parent_id" class="form-label">{{__('default.Parent Category')}}</label>
							<select class="form-select" id="parent_id" name="parent_id">
								<option value="">{{__('default.None')}}</option>
								@foreach($categories as $category)
									<option value="{{ $category->id }}">{{ $category->category_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3">
							<label for="category_description" class="form-label">{{__('default.Description')}}</label>
							<textarea class="form-control" id="category_description" name="category_description"></textarea>
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
	<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
	     aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editCategoryModalLabel">{{__('default.Edit Category')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="editCategoryForm" method="POST">
					@csrf
					@method('PUT')
					<div class="modal-body">
						<div class="mb-3">
							<label for="edit_category_name" class="form-label">{{__('default.Category Name')}}</label>
							<input type="text" class="form-control" id="edit_category_name" name="category_name" required>
						</div>
						<!-- After edit_category_name field -->
						<div class="mb-3">
							<label for="edit_category_slug" class="form-label">{{__('default.Slug')}}</label>
							<input type="text" class="form-control" id="edit_category_slug" name="category_slug">
						</div>
						<div class="mb-3">
							<label for="edit_language_id" class="form-label">{{__('default.Language')}}</label>
							<select class="form-select" id="edit_language_id" name="language_id" required>
								@foreach($languages as $language)
									<option value="{{ $language->id }}">{{ $language->language_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3">
							<label for="edit_parent_id" class="form-label">{{__('default.Parent Category')}}</label>
							<select class="form-select" id="edit_parent_id" name="parent_id">
								<option value="">{{__('default.None')}}</option>
								@foreach($categories as $category)
									<option value="{{ $category->id }}">{{ $category->category_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3">
							<label for="edit_category_description" class="form-label">{{__('default.Description')}}</label>
							<textarea class="form-control" id="edit_category_description" name="category_description"></textarea>
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
		function generateSlug(text) {
			return text
				.toString()
				.toLowerCase()
				.trim()
				.replace(/\s+/g, '-')           // Replace spaces with -
				.replace(/[^\w\-]+/g, '')       // Remove all non-word chars
				.replace(/\-\-+/g, '-')         // Replace multiple - with single -
				.replace(/^-+/, '')             // Trim - from start of text
				.replace(/-+$/, '');            // Trim - from end of text
		}

		$(document).ready(function () {
			
			
			// Auto-generate slug for new category
			$('#category_name').on('input', function () {
				if (!$('#category_slug').data('manual')) {
					$('#category_slug').val(generateSlug($(this).val()));
				}
			});
			
			// Auto-generate slug for edit category
			$('#edit_category_name').on('input', function () {
				if (!$('#edit_category_slug').data('manual')) {
					$('#edit_category_slug').val(generateSlug($(this).val()));
				}
			});
			
			// Mark slug as manually edited when user types in slug field
			$('#category_slug').on('input', function () {
				$(this).data('manual', true);
			});
			
			$('#edit_category_slug').on('input', function () {
				$(this).data('manual', true);
			});
			
			$('#addCategoryForm').on('submit', function (e) {
				e.preventDefault();
				
				$.ajax({
					url: $(this).attr('action'),
					method: 'POST',
					data: $(this).serialize(),
					success: function (response) {
						$('#addCategoryModal').modal('hide');
						// Reload the page or update the categories table
						window.location.reload();
					},
					error: function (xhr) {
						// Handle errors
						alert('Error adding category. Please try again.');
					}
				});
			});
			
			// Handle Category Edit Button Click
			$('.edit-category').click(function () {
				const categoryId = $(this).data('id');
				
				$.ajax({
					url: `/categories/${categoryId}/edit`,
					method: 'GET',
					success: function (data) {
						$('#edit_category_name').val(data.category_name);
						$('#edit_category_slug').val(data.category_slug);
						$('#edit_language_id').val(data.language_id);
						$('#edit_parent_id').val(data.parent_id);
						$('#edit_category_description').val(data.category_description);
						$('#editCategoryForm').attr('action', `/categories/${categoryId}`);
						$('#editCategoryModal').modal('show');
						$('#edit_category_slug').data('manual', false);
					},
					error: function (xhr) {
						console.error('Error fetching category data:', xhr);
						alert('Error fetching category data. Please try again.');
					}
				});
			});
			
			// Handle Delete Category
			$('.delete-category').click(function () {
				if (confirm('Are you sure you want to delete this category?')) {
					const categoryId = $(this).data('id');
					$.ajax({
						url: `/categories/${categoryId}`,
						type: 'DELETE',
						data: {
							"_token": "{{ csrf_token() }}"
						},
						success: function () {
							location.reload();
						},
						error: function (xhr) {
							const response = xhr.responseJSON;
							showNotification(response.message || 'Error deleting language');
						}
					});
				}
			});
			
			
		});
	</script>
@endpush
