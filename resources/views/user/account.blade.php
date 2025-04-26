@extends('layouts.admin')

@section('title', __('default.Account Settings'))
@section('page-title', __('default.Account Settings'))

@section('content')
	<!-- Account settings START -->
	<div class="card mb-4">
		<!-- Title START -->
		<div class="card-header border-0 pb-0">
{{--			<h5 class="card-title">{{__('default.Account Settings')}}</h5> --}}
		</div>
		<!-- Card header START -->
		<!-- Card body START -->
		<div class="card-body">
			<!-- Form settings START -->
			<form action="{{ route('settings-update') }}" method="post" class="row g-3" enctype="multipart/form-data">
				@csrf
				<!-- First name -->
				<div class="col-sm-6 col-lg-6">
					<label class="form-label">{{__('default.Name')}}</label>
					<input type="text" name="name" class="form-control" placeholder="" value="{{ old('name', $user->name) }}">
				</div>
				<!-- User name -->
				<div class="col-sm-6">
					<label class="form-label">{{__('default.User name')}}</label>
					<input type="text" name="username" class="form-control" placeholder="" value="{{ old('username', $user->username) }}">
				</div>
				<!-- Email address -->
				<div class="col-sm-6">
					<label class="form-label">{{__('default.Email')}}</label>
					<input type="email" name="email" class="form-control" placeholder="" value="{{ old('email', $user->email) }}">
				</div>
				<!-- Company name -->
				<div class="col-sm-6">
					<label class="form-label">{{__('default.Company Name')}}</label>
					<input type="text" name="company_name" class="form-control" placeholder="Enter your company name" value="{{ old('company_name', $user->company_name) }}">
				</div>
				<!-- Company description -->
				<div class="col-12">
					<label class="form-label">{{__('default.Company Description')}}</label>
					<textarea name="company_description" class="form-control" rows="3" placeholder="Enter your company description">{{ old('company_description', $user->company_description) }}</textarea>
				</div>
				<!-- Avatar upload -->
				<div class="col-sm-6">
					<label class="form-label">{{__('default.Avatar')}}</label>
					<input type="file" name="avatar" class="form-control" accept="image/jpeg,image/png,image/jpg"> {{-- More specific mimes --}}
				</div>
				<!-- Button -->
				<div class="col-12 text-end"> {{-- Align button right --}}
					<button type="submit" class="btn btn-primary mb-0">{{__('default.Save changes')}} </button>
				</div>
			</form>
			<!-- Settings END -->
		</div>
		<!-- Card body END -->
	</div>
	<!-- Account settings END -->
	
	<!-- API Keys START -->
	<div class="card mb-4">
		<div class="card-header border-0 pb-0">
			<h5 class="card-title">{{__('default.API Keys')}}</h5>
			<p class="mb-0 text-muted small">{{__('default.Set your personal API keys for unmetered usage.')}}</p> {{-- Added text-muted small --}}
		</div>
		<div class="card-body">
			<form action="{{ route('settings-update-api-keys') }}" method="post" class="row g-3">
				@csrf
				<div class="col-12">
					<label class="form-label">{{__('default.OpenRouter API Key')}}</label>
					<input type="password" name="openrouter_key" class="form-control" value="{{ old('openrouter_key', $user->openrouter_key ? '********' : '') }}"> {{-- Show password type, prefill with stars if exists --}}
				</div>
				<div class="col-12 text-end">
					<button type="submit" class="btn btn-primary mb-0">{{__('default.Update API Keys')}}</button>
				</div>
			</form>
		</div>
	</div>
	<!-- API Keys END -->
	
	<!-- Change your password START -->
	<div class="card">
		<!-- Title START -->
		<div class="card-header border-0 pb-0">
			<h5 class="card-title">{{__('default.Change your password')}}</h5>
			<p class="mb-0 text-muted small">{{__('default.If you signed up with Google, leave the current password blank the first time you update your password.')}}</p>
		</div>
		<!-- Title START -->
		<div class="card-body">
			<form action="{{ route('settings-password-update') }}" method="post" class="row g-3">
				@csrf
				<!-- Current password -->
				<div class="col-12">
					<label class="form-label">{{__('default.Current password')}}</label>
					<input type="password" name="current_password" class="form-control" placeholder="">
				</div>
				<!-- New password -->
				<div class="col-md-6"> {{-- Use columns for better layout --}}
					<label class="form-label">{{__('default.New password')}}</label>
					{{-- Input group for potential show/hide toggle (using fakepassword classes from original) --}}
					<div class="input-group">
						<input class="form-control fakepassword psw-input" type="password" name="new_password" id="psw-input" placeholder="Enter new password">
						<span class="input-group-text p-0">
                            <i class="fakepasswordicon bi bi-eye-slash cursor-pointer p-2 w-40px"></i> {{-- Swapped FontAwesome for Bootstrap Icon --}}
                        </span>
					</div>
					{{-- Pswmeter (ensure CSS/JS is loaded if you keep this) --}}
					<div id="pswmeter" class="mt-2"></div>
					<div id="pswmeter-message" class="form-text mt-1"></div> {{-- Use form-text --}}
				</div>
				<!-- Confirm new password -->
				<div class="col-md-6">
					<label class="form-label">{{__('default.Confirm password')}}</label>
					<input type="password" name="new_password_confirmation" class="form-control" placeholder="">
				</div>
				<!-- Button -->
				<div class="col-12 text-end">
					<button type="submit" class="btn btn-primary mb-0">{{__('default.Update password')}} </button>
				</div>
			</form>
			<!-- Settings END -->
		</div>
	</div>
	<!-- Card END -->
@endsection

@push('scripts')
	{{-- Include pswmeter JS if you are using it --}}
	{{-- <script src="/assets/vendor/pswmeter/pswmeter.js"></script> --}}
	<script>
		// Basic password show/hide toggle if not using pswmeter's built-in one
		$(document).ready(function() {
			$('.fakepasswordicon').on('click', function() {
				const input = $(this).closest('.input-group').find('.psw-input');
				const icon = $(this);
				if (input.attr('type') === 'password') {
					input.attr('type', 'text');
					icon.removeClass('bi-eye-slash').addClass('bi-eye');
				} else {
					input.attr('type', 'password');
					icon.removeClass('bi-eye').addClass('bi-eye-slash');
				}
			});
			
			// Initialize pswmeter if the element exists
			if ($('#psw-input').length && typeof pswmeter === 'function') {
				const myPassMeter = pswmeter(document.getElementById('psw-input'), {
					display: {
						container: document.getElementById('pswmeter'),
						message: document.getElementById('pswmeter-message'),
					}
					// Add other pswmeter options if needed
				});
				// Optional: Add event listeners for pswmeter if required
				// myPassMeter.container.addEventListener('onScoreUpdate', function(e) {
				//     console.log('Password score:', e.detail.score);
				// });
			}
		});
	</script>
@endpush
