@extends('layouts.admin')

@section('title', __('default.Close Account'))
@section('page-title', __('default.Close Account'))

@section('content')
	<div class="card border-danger"> {{-- Added border color --}}
		<!-- Card header START -->
		<div class="card-header border-danger pb-0"> {{-- Match border color --}}
			<h5 class="card-title text-danger">{{__('default.Delete account')}}</h5>
			<p class="mb-2">{{__('default.We are sorry to hear that you wish to delete your account.')}}</p>
			<p class="mb-2">{{__('default.Please note that deleting your account may result in the permanent loss of your data.')}}</p>
			<p class="mb-2">{{__('default.We are sad to see you go, but we hope that Contentero has been an enjoyable experience for you. We wish you the best in your future endeavors. Goodbye!')}}</p>
		</div>
		<!-- Card header START -->
		<!-- Card body START -->
		<div class="card-body">
			<!-- Delete START -->
			<h6>{{__('default.Before you go...')}}</h6>
			<ul>
				<li>{{__('default.If you delete your account, you will lose your all data.')}}</li>
				<li>This action is irreversible.</li> {{-- Add emphasis --}}
			</ul>
			<div class="form-check form-check-md my-4">
				<input class="form-check-input border-danger" type="checkbox" value="" id="deleteaccountCheck"> {{-- Add border color --}}
				<label class="form-check-label" for="deleteaccountCheck">{{__('default.Yes, I\'d like to delete my account')}}</label>
			</div>
			<div class="d-flex justify-content-end gap-2"> {{-- Use flex for button alignment --}}
				{{-- Keep account button (maybe link to support or feedback?) --}}
				<a href="{{ route('settings.account') }}" class="btn btn-secondary btn-sm">{{__('default.Keep my account')}}</a>
				{{-- Delete button - Add JS confirmation and disable logic --}}
				<button type="button" id="deleteAccountBtn" class="btn btn-danger btn-sm" disabled> {{-- Disabled initially --}}
					{{__('default.Delete my account')}}
				</button>
			</div>
			<!-- Delete END -->
		</div>
		<!-- Card body END -->
	</div>
@endsection

@push('scripts')
	<script>
		$(document).ready(function () {
			const deleteCheckbox = $('#deleteaccountCheck');
			const deleteButton = $('#deleteAccountBtn');
			
			deleteCheckbox.on('change', function() {
				deleteButton.prop('disabled', !this.checked);
			});
			
			deleteButton.on('click', function() {
				if (deleteCheckbox.prop('checked')) {
					if (confirm('ARE YOU ABSOLUTELY SURE?\n\nThis action cannot be undone and all your data will be permanently lost.')) {
						// Proceed with account deletion logic
						// Example: Submit a form or make an AJAX call
						alert('Account deletion initiated (replace this with actual logic)');
						// Example AJAX call:
						/*
						$.ajax({
								url: '/settings/account/delete', // Define this route
								type: 'DELETE',
								data: { "_token": "{{ csrf_token() }}" },
                    beforeSend: function() {
                        deleteButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Deleting...');
                    },
                    success: function(response) {
                        // Redirect to a logged-out page or show success message
                        window.location.href = '/'; // Redirect to home
                    },
                    error: function(xhr) {
                        showNotification(xhr.responseJSON?.message || 'Error deleting account.', 'danger');
                        deleteButton.prop('disabled', false).text('{{__('default.Delete my account')}}');
                    }
                });
                */
					}
				}
			});
		});
	</script>
@endpush
