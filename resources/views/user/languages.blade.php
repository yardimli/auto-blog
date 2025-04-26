@extends('layouts.admin')

@section('title', __('default.Manage Languages'))
@section('page-title', __('default.Manage Languages'))

@section('top-bar-actions')
	<a href="#" class="btn btn-primary btn-sm ms-3" data-bs-toggle="modal" data-bs-target="#addLanguageModal">
		<i class="bi bi-plus-lg me-1"></i> {{__('default.Add New Language')}}
	</a>
@endsection

@section('content')
	<div class="card">
		<div class="card-header border-bottom pb-3"> {{-- Added border --}}
			<h5 class="card-title mb-0">{{__('default.Manage Languages')}}</h5>
			{{-- Removed paragraph and button as button is in top bar --}}
		</div>
		<div class="card-body">
			@if($languages->isEmpty())
				<div class="text-center py-5">
					<i class="bi bi-translate display-4 text-muted"></i>
					<p class="mt-3 mb-0">{{ __('default.No languages configured yet.') }}</p>
					<p><a href="#" data-bs-toggle="modal" data-bs-target="#addLanguageModal">Add your first language</a>.</p>
				</div>
			@else
				<div class="table-responsive">
					<table class="table table-hover"> {{-- Added hover effect --}}
						<thead>
						<tr>
							<th>{{__('default.Language Name')}}</th>
							<th>{{__('default.Locale')}}</th>
							<th>{{__('default.Status')}}</th>
							<th class="text-end">{{__('default.Actions')}}</th> {{-- Align right --}}
						</tr>
						</thead>
						<tbody>
						@foreach($languages as $language)
							<tr>
								<td class="align-middle">{{ $language->language_name }}</td>
								<td class="align-middle">{{ $language->locale }}</td>
								<td class="align-middle">
                                    <span class="badge rounded-pill bg-{{ $language->active ? 'success' : 'secondary' }}"> {{-- Use secondary for inactive --}}
	                                    {{ $language->active ? __('default.Active') : __('default.Inactive') }}
                                    </span>
								</td>
								<td class="text-end align-middle">
									<button class="btn btn-sm btn-outline-primary edit-language me-1" data-id="{{ $language->id }}" title="{{__('default.Edit')}}">
										<i class="bi bi-pencil"></i>
									</button>
									<button class="btn btn-sm btn-outline-danger delete-language" data-id="{{ $language->id }}" title="{{__('default.Delete')}}">
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
	
	<!-- Add Language Modal -->
	<div class="modal fade" id="addLanguageModal" tabindex="-1" aria-labelledby="addLanguageModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addLanguageModalLabel">{{__('default.Add New Language')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form action="{{ route('languages.store') }}" method="POST" id="addLanguageForm">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label for="language_name" class="form-label">{{__('default.Language Name')}}</label>
							<input type="text" class="form-control" id="language_name" name="language_name" required placeholder="e.g., English, Türkçe">
						</div>
						<div class="mb-3">
							<label for="locale" class="form-label">{{__('default.Locale')}}</label>
							<input type="text" class="form-control" id="locale" name="locale" required placeholder="e.g., en_US, tr_TR">
						</div>
						<div class="mb-3">
							<div class="form-check form-switch"> {{-- Use switch --}}
								<input class="form-check-input" type="checkbox" id="active" name="active" value="1" checked>
								<label class="form-check-label" for="active"> {{__('default.Active')}} </label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('default.Close')}}</button>
						<button type="submit" class="btn btn-primary">{{__('default.Add Language')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Edit Language Modal -->
	<div class="modal fade" id="editLanguageModal" tabindex="-1" aria-labelledby="editLanguageModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editLanguageModalLabel">{{__('default.Edit Language')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="editLanguageForm" method="POST"> {{-- Action will be set via JS --}}
					@csrf
					@method('PUT')
					<div class="modal-body">
						<div class="mb-3">
							<label for="edit_language_name" class="form-label">{{__('default.Language Name')}}</label>
							<input type="text" class="form-control" id="edit_language_name" name="language_name" required>
						</div>
						<div class="mb-3">
							<label for="edit_locale" class="form-label">{{__('default.Locale')}}</label>
							<input type="text" class="form-control" id="edit_locale" name="locale" required>
						</div>
						<div class="mb-3">
							<div class="form-check form-switch"> {{-- Use switch --}}
								<input class="form-check-input" type="checkbox" id="edit_active" name="active" value="1">
								<label class="form-check-label" for="edit_active"> {{__('default.Active')}} </label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('default.Close')}}</button>
						<button type="submit" class="btn btn-primary">{{__('default.Update Language')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		$(document).ready(function () {
			// Handle Add Form Submission via AJAX for smoother UX
			$('#addLanguageForm').on('submit', function (e) {
				e.preventDefault();
				const form = $(this);
				const submitButton = form.find('button[type="submit"]');
				submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');
				
				$.ajax({
					url: form.attr('action'),
					method: 'POST',
					data: form.serialize(),
					success: function (response) {
						$('#addLanguageModal').modal('hide');
						form[0].reset(); // Reset form fields
						// Optionally reload or dynamically add row to table
						window.location.reload(); // Simple reload for now
					},
					error: function (xhr) {
						// Handle errors (e.g., display validation messages)
						console.error(xhr.responseText);
						showNotification(xhr.responseJSON?.message || 'Error adding language. Please check input and try again.', 'danger');
						submitButton.prop('disabled', false).text('{{__('default.Add Language')}}');
					}
				});
			});
			
			// Handle Edit Button Click
			$('.edit-language').click(function () {
				const languageId = $(this).data('id');
				const modal = $('#editLanguageModal');
				const form = $('#editLanguageForm');
				
				$.ajax({
					url: `/languages/${languageId}/edit`, // Ensure this route exists and returns JSON
					method: 'GET',
					success: function (data) {
						form.attr('action', `/languages/${languageId}`); // Set form action URL
						modal.find('#edit_language_name').val(data.language_name);
						modal.find('#edit_locale').val(data.locale);
						modal.find('#edit_active').prop('checked', Boolean(data.active));
						modal.modal('show');
					},
					error: function (xhr) {
						console.error('Error fetching language data:', xhr);
						showNotification('Error fetching language data. Please try again.', 'danger');
					}
				});
			});
			
			// Handle Edit Form Submission via AJAX
			$('#editLanguageForm').on('submit', function (e) {
				e.preventDefault();
				const form = $(this);
				const submitButton = form.find('button[type="submit"]');
				submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
				
				$.ajax({
					url: form.attr('action'),
					method: 'POST', // Method override handled by @method('PUT')
					data: form.serialize(),
					success: function (response) {
						$('#editLanguageModal').modal('hide');
						window.location.reload(); // Simple reload
					},
					error: function (xhr) {
						console.error(xhr.responseText);
						showNotification(xhr.responseJSON?.message || 'Error updating language. Please check input and try again.', 'danger');
						submitButton.prop('disabled', false).text('{{__('default.Update Language')}}');
					}
				});
			});
			
			
			// Handle Delete Language
			$('.delete-language').click(function () {
				if (confirm('{{ __("default.Are you sure you want to delete this language?") }}\n{{ __("default.Cannot delete language with associated content") }}')) {
					const languageId = $(this).data('id');
					const button = $(this);
					button.prop('disabled', true);
					
					$.ajax({
						url: `/languages/${languageId}`,
						type: 'DELETE',
						data: {"_token": "{{ csrf_token() }}"},
						success: function (response) {
							// Remove row or reload
							button.closest('tr').fadeOut(300, function() { $(this).remove(); });
							showNotification(response.message || 'Language deleted successfully', 'success');
							// No reload needed if row is removed dynamically
						},
						error: function (xhr) {
							const response = xhr.responseJSON;
							showNotification(response.message || 'Error deleting language', 'danger');
							button.prop('disabled', false);
						}
					});
				}
			});
		});
	</script>
@endpush
