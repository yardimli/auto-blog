@extends('layouts.app')

@section('title', 'Settings')

@section('content')
	<main>
		<div class="container">
			<div class="row">
				<!-- Sidenav START -->
				<div class="col-lg-3">
					<nav class="navbar navbar-light navbar-expand-lg mx-0">
						<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">
							<div class="offcanvas-header">
								<button type="button" class="btn-close text-reset ms-auto" data-bs-dismiss="offcanvas"></button>
							</div>
							<div class="offcanvas-body p-0">
								<div class="card w-100">
									<div class="card-body">
										<ul class="nav nav-tabs nav-pills nav-pills-soft flex-column fw-bold gap-2 border-0">
											<li class="nav-item">
												<a class="nav-link {{ Route::currentRouteName() == 'settings.account' ? 'active' : '' }}"
												   style="margin-bottom: 0px;"
												   href="{{ route('settings.account') }}">
													<img class="me-2 h-20px" src="/assets/images/icon/person-outline-filled.svg" alt="">
													{{__('default.Account')}}
												</a>
											</li>
											<li class="nav-item">
												<a href="{{ route('settings.pages') }}"
												   style="margin-bottom: 0px;"
												   class="nav-link {{ Request::routeIs('settings.pages') ? 'active' : '' }}">
													<img class="me-2 h-20px" src="/assets/images/icon/folder-open-outline-filled.svg" alt="">
													{{ __('default.Page Settings') }}
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link {{ Route::currentRouteName() == 'settings.languages' ? 'active' : '' }}"
												   style="margin-bottom: 0px;"
												   href="{{ route('settings.languages') }}">
													<img class="me-2 h-20px" src="/assets/images/icon/earth-outline-filled.svg" alt="">
													{{__('default.Languages')}}
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link {{ Route::currentRouteName() == 'settings.categories' ? 'active' : '' }}"
												   style="margin-bottom: 0px;"
												   href="{{ route('settings.categories') }}">
													<img class="me-2 h-20px" src="/assets/images/icon/folder-outline-filled.svg" alt="">
													{{__('default.Categories')}}
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link {{ Route::currentRouteName() == 'settings.images' ? 'active' : '' }}"
												   style="margin-bottom: 0px;"
												   href="{{ route('settings.images') }}">
													<img class="me-2 h-20px" src="/assets/images/icon/image-outline-filled.svg" alt="">
													{{__('default.Images')}}
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link {{ Route::currentRouteName() == 'settings.close-account' ? 'active' : '' }}"
												   style="margin-bottom: 0px;"
												   href="{{ route('settings.close-account') }}">
													<img class="me-2 h-20px" src="/assets/images/icon/trash-var-outline-filled.svg" alt="">
													{{__('default.Close Account')}}
												</a>
											</li>
										</ul>
									</div>
									
									<div class="card-footer text-center py-2 pb-3">
										<a class="btn btn-secondary-soft btn-sm w-100 mb-2"
										   href="{{route('settings.account')}}">{{__('default.My Books')}} </a>
										{{--										<a class="btn btn-info-soft btn-sm w-100" href="{{route('buy-packages')}}">Purchase Tokens</a>--}}
									</div>
								</div>
							</div>
						</div>
					</nav>
				</div>
				<!-- Main content START -->
				<div class="col-lg-9 vstack">
					<!-- Show success message if available -->
					<!-- Display success or error messages -->
					@if (session('success'))
						<div class="alert alert-success mt-2">
							{{ session('success') }}
						</div>
					@endif
					
					@if ($errors->any())
						<div class="alert alert-danger mt-2">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					<div id="alertContainer"></div>
					
					@yield('settings-content')
				</div>
			</div>
		</div>
	</main>
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<script>
		
		function showNotification(message, type = 'danger') {
			const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
			
			$('#alertContainer').html(alertHtml);
			
			// Auto dismiss after 5 seconds
			setTimeout(() => {
				$('.alert').alert('close');
			}, 5000);
		}
		
		$(document).ready(function () {
		});
	
	</script>
@endpush
