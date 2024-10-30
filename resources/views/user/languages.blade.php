@extends('layouts.settings')

@section('settings-content')
	
	<!-- Languages tab START -->
	<div class="tab-pane" id="nav-setting-tab-2">
		<div class="card">
			<div class="card-header border-0 pb-0">
				<h5 class="card-title">{{__('default.Manage Languages')}}</h5>
				<div class="d-flex justify-content-between align-items-center">
					<p class="mb-0">{{__('default.Configure available languages for your content')}}</p>
					<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
					   data-bs-target="#addLanguageModal">
						{{__('default.Add New Language')}}
					</a>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table">
						<thead>
						<tr>
							<th>{{__('default.Language Name')}}</th>
							<th>{{__('default.Locale')}}</th>
							<th>{{__('default.Status')}}</th>
							<th>{{__('default.Actions')}}</th>
						</tr>
						</thead>
						<tbody>
						@foreach($languages as $language)
							<tr>
								<td>{{ $language->language_name }}</td>
								<td>{{ $language->locale }}</td>
								<td>
                                <span class="badge bg-{{ $language->active ? 'success' : 'danger' }}">
                                    {{ $language->active ? __('default.Active') : __('default.Inactive') }}
                                </span>
								</td>
								<td>
									<button class="btn btn-sm btn-primary edit-language" data-id="{{ $language->id }}">
										{{__('default.Edit')}}
									</button>
									<button class="btn btn-sm btn-danger delete-language" data-id="{{ $language->id }}">
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
	<!-- Languages tab END -->
	
	
	<!-- Add Language Modal -->
	<div class="modal fade" id="addLanguageModal" tabindex="-1" aria-labelledby="addLanguageModalLabel"
	     aria-hidden="true">
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
							<input type="text" class="form-control" id="language_name" name="language_name" required>
						</div>
						<div class="mb-3">
							<label for="locale" class="form-label">{{__('default.Locale')}}</label>
							<input type="text" class="form-control" id="locale" name="locale" required>
						</div>
						<div class="mb-3">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="active" name="active" value="1">
								<label class="form-check-label" for="active">
									{{__('default.Active')}}
								</label>
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
	<div class="modal fade" id="editLanguageModal" tabindex="-1" aria-labelledby="editLanguageModalLabel"
	     aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editLanguageModalLabel">{{__('default.Edit Language')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="editLanguageForm" method="POST">
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
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="edit_active" name="active" value="1">
								<label class="form-check-label" for="edit_active">
									{{__('default.Active')}}
								</label>
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
			
			$('#addLanguageForm').on('submit', function (e) {
				e.preventDefault();
				
				$.ajax({
					url: $(this).attr('action'),
					method: 'POST',
					data: $(this).serialize(),
					success: function (response) {
						$('#addLanguageForm').modal('hide');
						// Reload the page or update the categories table
						window.location.reload();
					},
					error: function (xhr) {
						// Handle errors
						alert('Error adding language. Please try again.');
					}
				});
			});
			
			// Handle Language Edit Button Click
			$('.edit-language').click(function () {
				const languageId = $(this).data('id');
				
				$.ajax({
					url: `/languages/${languageId}/edit`,
					method: 'GET',
					success: function (data) {
						$('#edit_language_name').val(data.language_name);
						$('#edit_locale').val(data.locale);
						$('#edit_active').prop('checked', Boolean(data.active));
						$('#editLanguageForm').attr('action', `/languages/${languageId}`);
						$('#editLanguageModal').modal('show');
					},
					error: function (xhr) {
						console.error('Error fetching language data:', xhr);
						alert('Error fetching language data. Please try again.');
					}
				});
			});
			
			// Handle Delete Language
			$('.delete-language').click(function () {
				if (confirm('Are you sure you want to delete this language?')) {
					const languageId = $(this).data('id');
					$.ajax({
						url: `/languages/${languageId}`,
						type: 'DELETE',
						data: {"_token": "{{ csrf_token() }}"},
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
