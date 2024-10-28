@extends('layouts.app')

@section('title', 'Settings')

@section('content')
	
	<!-- **************** MAIN CONTENT START **************** -->
	<main>
		<!-- Container START -->
		<div class="container">
			<div class="row">
				
				<!-- Sidenav START -->
				<div class="col-lg-3">
					
					<!-- Advanced filter responsive toggler START -->
					<!-- Divider -->
					<div class="d-flex align-items-center mb-4 d-lg-none">
						<button class="border-0 bg-transparent" type="button" data-bs-toggle="offcanvas"
						        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
							<span class="btn btn-primary"><i class="fa-solid fa-sliders-h"></i></span>
							<span class="h6 mb-0 fw-bold d-lg-none ms-2">{{__('default.Settings')}}</span>
						</button>
					</div>
					<!-- Advanced filter responsive toggler END -->
					
					<nav class="navbar navbar-light navbar-expand-lg mx-0">
						<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">
							<!-- Offcanvas header -->
							<div class="offcanvas-header">
								<button type="button" class="btn-close text-reset ms-auto" data-bs-dismiss="offcanvas"
								        aria-label="{{__('default.Close')}}"></button>
							</div>
							
							<!-- Offcanvas body -->
							<div class="offcanvas-body p-0">
								<!-- Card START -->
								<div class="card w-100">
									<!-- Card body START -->
									<div class="card-body">
										
										<!-- Side Nav START -->
										<ul class="nav nav-tabs nav-pills nav-pills-soft flex-column fw-bold gap-2 border-0">
											<li class="nav-item" data-bs-dismiss="offcanvas">
												<a class="nav-link d-flex mb-0 active" href="#nav-setting-tab-1"
												   data-bs-toggle="tab"> <img
														class="me-2 h-20px "
														src="/assets/images/icon/person-outline-filled.svg"
														alt=""><span>{{__('default.Account')}}</span></a>
											</li>
											
											<li class="nav-item" data-bs-dismiss="offcanvas">
												<a class="nav-link d-flex mb-0" href="#nav-setting-tab-2" data-bs-toggle="tab">
													<img class="me-2 h-20px" src="/assets/images/icon/earth-outline-filled.svg" alt="">
													<span>{{__('default.Languages')}}</span>
												</a>
											</li>
											<li class="nav-item" data-bs-dismiss="offcanvas">
												<a class="nav-link d-flex mb-0" href="#nav-setting-tab-3" data-bs-toggle="tab">
													<img class="me-2 h-20px" src="/assets/images/icon/folder-outline-filled.svg" alt="">
													<span>{{__('default.Categories')}}</span>
												</a>
											</li>
											
											<li class="nav-item" data-bs-dismiss="offcanvas">
												<a class="nav-link d-flex mb-0" href="#nav-setting-tab-6"
												   data-bs-toggle="tab"> <img
														class="me-2 h-20px "
														src="/assets/images/icon/trash-var-outline-filled.svg"
														alt=""><span>{{__('default.Close Account')}}</span></a>
											</li>
										</ul>
										<!-- Side Nav END -->
									
									</div>
									<!-- Card body END -->
									<!-- Card footer -->
									<div class="card-footer text-center py-2 pb-3">
										<a class="btn btn-secondary-soft btn-sm w-100 mb-2" href="{{route('my-settings')}}">{{__('default.My Books')}} </a>
{{--										<a class="btn btn-info-soft btn-sm w-100" href="{{route('buy-packages')}}">Purchase Tokens</a>--}}
									</div>
								
								</div>
								<!-- Card END -->
							</div>
							<!-- Offcanvas body -->
						</div>
					</nav>
				</div>
				<!-- Sidenav END -->
				
				<!-- Main content START -->
				<div class="col-lg-6 vstack gap-4">
					<!-- Setting Tab content START -->
					<div class="tab-content py-0 mb-0">
						
						<div id="alertContainer"></div>
						
						<!-- Account setting tab START -->
						<div class="tab-pane show active fade" id="nav-setting-tab-1">
							<!-- Account settings START -->
							<div class="card mb-4">
								<!-- Show success message if available -->
								@if (session('status'))
									<div class="alert alert-success" role="alert">
										{{ session('status') }}
									</div>
								@endif
								
								<!-- Title START -->
								<div class="card-header border-0 pb-0">
									<h1 class="h5 card-title">{{__('default.Account Settings')}}</h1>
								</div>
								<!-- Card header START -->
								<!-- Card body START -->
								<div class="card-body">
									<!-- Form settings START -->
									
									<!-- Display success or error messages -->
									@if (session('success'))
										<div class="alert alert-success mt-2">
											{{ session('success') }}
										</div>
									@endif
									
									<form action="{{ route('settings-update') }}" method="post" class="row g-3"
									      enctype="multipart/form-data">
										@csrf
										<!-- First name -->
										<div class="col-sm-6 col-lg-6">
											<label class="form-label">{{__('default.Name')}}</label>
											<input type="text" name="name" class="form-control" placeholder=""
											       value="{{ old('name', $user->name) }}">
										</div>
										<!-- User name -->
										<div class="col-sm-6">
											<label class="form-label">{{__('default.User name')}}</label>
											<input type="text" name="username" class="form-control" placeholder=""
											       value="{{ old('username', $user->username) }}">
										</div>
										
										<!-- Email address -->
										<div class="col-sm-6">
											<label class="form-label">{{__('default.Email')}}</label>
											<input type="email" name="email" class="form-control" placeholder=""
											       value="{{ old('email', $user->email) }}">
										</div>
										
										<!-- Avatar upload -->
										<div class="col-sm-6">
											<label class="form-label">{{__('default.Avatar')}}</label>
											<input type="file" name="avatar" class="form-control" accept="image/*">
										</div>
										
										<!-- Button -->
										<div class="col-12 text-start">
											<button type="submit" class="btn btn-sm btn-primary mb-0">{{__('default.Save changes')}}
											</button>
										</div>
										
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
									</form>
									<!-- Settings END -->
								</div>
								<!-- Card body END -->
								
								<!-- API Keys START -->
								<div class="card mb-4">
									<div class="card-header border-0 pb-0">
										<h1 class="h5 card-title">{{__('default.API Keys')}}</h1>
										<p class="mb-0">{{__('default.Set your personal API keys for unmetered usage.')}}</p>
									</div>
									<div class="card-body">
										<form action="{{ route('settings-update-api-keys') }}" method="post" class="row g-3">
											@csrf
											<div class="col-12">
												<label class="form-label">{{__('default.OpenAI API Key')}}</label>
												<input type="text" name="openai_api_key" class="form-control" value="{{ old('openai_api_key', $user->openai_api_key) }}">
											</div>
											<div class="col-12">
												<label class="form-label">{{__('default.Anthropic API Key')}}</label>
												<input type="text" name="anthropic_key" class="form-control" value="{{ old('anthropic_key', $user->anthropic_key) }}">
											</div>
											<div class="col-12">
												<label class="form-label">{{__('default.OpenRouter API Key')}}</label>
												<input type="text" name="openrouter_key" class="form-control" value="{{ old('openrouter_key', $user->openrouter_key) }}">
											</div>
											<div class="col-12 text-end">
												<button type="submit" class="btn btn-primary mb-0">{{__('default.Update API Keys')}}</button>
											</div>
										</form>
									</div>
								</div>
								<!-- API Keys END -->
								
								<!-- Account settings END -->
								
								<!-- Change your password START -->
								
								<div class="card">
									<!-- Title START -->
									<div class="card-header border-0 pb-0">
										<h5 class="card-title">{{__('default.Change your password')}}</h5>
										<p class="mb-0">{{__('default.If you signed up with Google, leave the current password blank the first time you update your password.')}}</p>
									</div>
									<!-- Title START -->
									<div class="card-body">
										
										<form action="{{ route('settings-password-update') }}" method="post"
										      class="row g-3">
											@csrf
											<!-- Current password -->
											<div class="col-12">
												<label class="form-label">{{__('default.Current password')}}</label>
												<input type="password" name="current_password" class="form-control"
												       placeholder="">
											</div>
											<!-- New password -->
											<div class="col-12">
												<label class="form-label">{{__('default.New password')}}</label>
												<!-- Input group -->
												<div class="input-group">
													<input class="form-control fakepassword psw-input" type="password"
													       name="new_password" id="psw-input"
													       placeholder="Enter new password">
													<span class="input-group-text p-0">
                          <i class="fakepasswordicon fa-solid fa-eye-slash cursor-pointer p-2 w-40px"></i>
                        </span>
												</div>
												<!-- Pswmeter -->
												<div id="pswmeter" class="mt-2"></div>
												<div id="pswmeter-message" class="rounded mt-1"></div>
											</div>
											
											<!-- Confirm new password -->
											<div class="col-12">
												<label class="form-label">{{__('default.Confirm password')}}</label>
												<input type="password" name="new_password_confirmation"
												       class="form-control" placeholder="">
											</div>
											<!-- Button -->
											<div class="col-12 text-end">
												<button type="submit" class="btn btn-primary mb-0">{{__('default.Update password')}}
												</button>
											</div>
											
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
										</form>
										
										<!-- Settings END -->
									</div>
								</div>
								<!-- Card END -->
							</div>
						</div>
						<!-- Account setting tab END -->
						
						<!-- Languages tab START -->
						<div class="tab-pane fade" id="nav-setting-tab-2">
							<div class="card">
								<div class="card-header border-0 pb-0">
									<h5 class="card-title">{{__('default.Manage Languages')}}</h5>
									<div class="d-flex justify-content-between align-items-center">
										<p class="mb-0">{{__('default.Configure available languages for your content')}}</p>
										<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addLanguageModal">
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
						
						<!-- Categories tab START -->
						<div class="tab-pane fade" id="nav-setting-tab-3">
							<div class="card">
								<div class="card-header border-0 pb-0">
									<h5 class="card-title">{{__('default.Manage Categories')}}</h5>
									<div class="d-flex justify-content-between align-items-center">
										<p class="mb-0">{{__('default.Organize your content with categories')}}</p>
										<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
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
						
						<!-- Close account tab START -->
						<div class="tab-pane fade" id="nav-setting-tab-6">
							<!-- Card START -->
							<div class="card">
								<!-- Card header START -->
								<div class="card-header border-0 pb-0">
									<h5 class="card-title">{{__('default.Delete account')}}</h5>
									<p class="mb-2">{{__('default.We are sorry to hear that you wish to delete your account.')}}</p>
									<p class="mb-2">{{__('default.Please note that deleting your account may result in the permanent loss of your data.')}}</p>
									<p class="mb-2">{{__('default.We are sad to see you go, but we hope that Auto Blog has been an enjoyable experience for you. We wish you the best in your future endeavors. Goodbye!')}}</p>
								</div>
								<!-- Card header START -->
								<!-- Card body START -->
								<div class="card-body">
									<!-- Delete START -->
									<h6>{{__('default.Before you go...')}}</h6>
									<ul>
										<li>{{__('default.If you delete your account, you will lose your all data.')}}</li>
									</ul>
									<div class="form-check form-check-md my-4">
										<input class="form-check-input" type="checkbox" value=""
										       id="deleteaccountCheck">
										<label class="form-check-label" for="deleteaccountCheck">{{__('default.Yes, I\'d like to delete my account')}}</label>
									</div>
									<a href="#" class="btn btn-success-soft btn-sm mb-2 mb-sm-0">{{__('default.Keep my account')}}</a>
									<a href="#" class="btn btn-danger btn-sm mb-0">{{__('default.Delete my account')}}</a>
									<!-- Delete END -->
								</div>
								<!-- Card body END -->
							</div>
							<!-- Card END -->
						</div>
						<!-- Close account tab END -->
					
					</div>
					<!-- Setting Tab content END -->
				</div>
			
			</div> <!-- Row END -->
		</div>
		<!-- Container END -->
	
	</main>
	<!-- **************** MAIN CONTENT END **************** -->
	
	
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
	<div class="modal fade" id="editLanguageModal" tabindex="-1" aria-labelledby="editLanguageModalLabel" aria-hidden="true">
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
	<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
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
	
	
	<!-- Vendors -->
	<script src="/assets/vendor/pswmeter/pswmeter.js"></script>
	
	@include('layouts.footer')

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
		
		// Auto-generate slug for new category
		$('#category_name').on('input', function() {
			if (!$('#category_slug').data('manual')) {
				$('#category_slug').val(generateSlug($(this).val()));
			}
		});
		
		// Auto-generate slug for edit category
		$('#edit_category_name').on('input', function() {
			if (!$('#edit_category_slug').data('manual')) {
				$('#edit_category_slug').val(generateSlug($(this).val()));
			}
		});
		
		// Mark slug as manually edited when user types in slug field
		$('#category_slug').on('input', function() {
			$(this).data('manual', true);
		});
		
		$('#edit_category_slug').on('input', function() {
			$(this).data('manual', true);
		});
		
		$('#addLanguageForm').on('submit', function(e) {
			e.preventDefault();
			
			$.ajax({
				url: $(this).attr('action'),
				method: 'POST',
				data: $(this).serialize(),
				success: function(response) {
					$('#addLanguageForm').modal('hide');
					// Reload the page or update the categories table
					window.location.reload();
				},
				error: function(xhr) {
					// Handle errors
					alert('Error adding language. Please try again.');
				}
			});
		});
		
		// Handle Language Edit Button Click
		$('.edit-language').click(function() {
			const languageId = $(this).data('id');
			
			$.ajax({
				url: `/languages/${languageId}/edit`,
				method: 'GET',
				success: function(data) {
					$('#edit_language_name').val(data.language_name);
					$('#edit_locale').val(data.locale);
					$('#edit_active').prop('checked', Boolean(data.active));
					$('#editLanguageForm').attr('action', `/languages/${languageId}`);
					$('#editLanguageModal').modal('show');
				},
				error: function(xhr) {
					console.error('Error fetching language data:', xhr);
					alert('Error fetching language data. Please try again.');
				}
			});
		});
		
		
		$('#addCategoryForm').on('submit', function(e) {
			e.preventDefault();
			
			$.ajax({
				url: $(this).attr('action'),
				method: 'POST',
				data: $(this).serialize(),
				success: function(response) {
					$('#addCategoryModal').modal('hide');
					// Reload the page or update the categories table
					window.location.reload();
				},
				error: function(xhr) {
					// Handle errors
					alert('Error adding category. Please try again.');
				}
			});
		});
		
		// Handle Category Edit Button Click
		$('.edit-category').click(function() {
			const categoryId = $(this).data('id');
			
			$.ajax({
				url: `/categories/${categoryId}/edit`,
				method: 'GET',
				success: function(data) {
					$('#edit_category_name').val(data.category_name);
					$('#edit_category_slug').val(data.category_slug);
					$('#edit_language_id').val(data.language_id);
					$('#edit_parent_id').val(data.parent_id);
					$('#edit_category_description').val(data.category_description);
					$('#editCategoryForm').attr('action', `/categories/${categoryId}`);
					$('#editCategoryModal').modal('show');
					$('#edit_category_slug').data('manual', false);
				},
				error: function(xhr) {
					console.error('Error fetching category data:', xhr);
					alert('Error fetching category data. Please try again.');
				}
			});
		});
		
		// Handle Delete Language
		$('.delete-language').click(function() {
			if (confirm('Are you sure you want to delete this language?')) {
				const languageId = $(this).data('id');
				$.ajax({
					url: `/languages/${languageId}`,
					type: 'DELETE',
					data: { "_token": "{{ csrf_token() }}" },
					success: function() {
						location.reload();
					},
					error: function(xhr) {
						const response = xhr.responseJSON;
						showNotification(response.message || 'Error deleting language');
					}
				});
			}
		});
		
		// Handle Delete Category
		$('.delete-category').click(function() {
			if (confirm('Are you sure you want to delete this category?')) {
				const categoryId = $(this).data('id');
				$.ajax({
					url: `/categories/${categoryId}`,
					type: 'DELETE',
					data: {
						"_token": "{{ csrf_token() }}"
					},
					success: function() {
						location.reload();
					},
					error: function(xhr) {
						const response = xhr.responseJSON;
						showNotification(response.message || 'Error deleting language');
					}
				});
			}
		});
	</script>
@endpush
