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
												<a class="nav-link d-flex mb-0" href="#nav-setting-tab-1"
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
												<a class="nav-link d-flex mb-0" href="#nav-setting-tab-4" data-bs-toggle="tab">
													<img class="me-2 h-20px" src="/assets/images/icon/image-outline-filled.svg" alt="">
													<span>{{__('default.Images')}}</span>
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
										<a class="btn btn-secondary-soft btn-sm w-100 mb-2"
										   href="{{route('my-settings')}}">{{__('default.My Books')}} </a>
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
				<div class="col-lg-9 vstack gap-4">
					<!-- Setting Tab content START -->
					<div class="tab-content py-0 mb-0">
						
						<div id="alertContainer"></div>
						
						<!-- Account setting tab START -->
						<div class="tab-pane fade" id="nav-setting-tab-1">
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
												<input type="text" name="openai_api_key" class="form-control"
												       value="{{ old('openai_api_key', $user->openai_api_key) }}">
											</div>
											<div class="col-12">
												<label class="form-label">{{__('default.Anthropic API Key')}}</label>
												<input type="text" name="anthropic_key" class="form-control"
												       value="{{ old('anthropic_key', $user->anthropic_key) }}">
											</div>
											<div class="col-12">
												<label class="form-label">{{__('default.OpenRouter API Key')}}</label>
												<input type="text" name="openrouter_key" class="form-control"
												       value="{{ old('openrouter_key', $user->openrouter_key) }}">
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
										<p
											class="mb-0">{{__('default.If you signed up with Google, leave the current password blank the first time you update your password.')}}</p>
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
						
						<!-- Categories tab START -->
						<div class="tab-pane fade" id="nav-setting-tab-3">
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
						
						<!-- Images tab START -->
						<div class="tab-pane fade" id="nav-setting-tab-4">
							<div class="card">
								<div class="card-header border-0 pb-0">
									<h5 class="card-title">{{__('default.Manage Images')}}</h5>
									<div class="d-flex justify-content-between align-items-center">
										<p class="mb-0">{{__('default.Upload and manage your images')}}</p>
										<div class="d-flex gap-2"> <!-- Added container for buttons -->
											<button class="btn btn-sm btn-primary" id="uploadImageBtn">
												{{__('default.Upload Image')}}
											</button>
											<button class="btn btn-sm btn-success" data-bs-toggle="collapse"
											        data-bs-target="#imageGenSection">
												{{__('default.Generate with AI')}}
											</button>
										</div>
									</div>
								</div>
								
								<!-- Image Generation Section -->
								<div class="collapse" id="imageGenSection">
									<div class="card-body border-bottom">
										<div class="mb-3">
											{{__('default.Prompt Enhancer')}}:
											<textarea class="form-control" id="promptEnhancer" rows="4">##UserPrompt##
Write a prompt to create an image using the above text.: Write in English even if the above text is written in another language. With the above information, compose a image. Write it as a single paragraph. The instructions should focus on the text elements of the image.</textarea>
										</div>
										
										<div class="mb-3">
											{{__('default.User Prompt')}}:
											<textarea class="form-control" id="userPrompt" rows="2"></textarea>
										</div>
										
										<div class="row mb-3">
											<div class="col-md-6">
												<label for="modelSelect" class="form-label">{{__('default.Model')}}</label>
												<select id="modelSelect" class="form-select">
													<option value="https://fal.run/fal-ai/flux/schnell">Flux Schnell (Fast)</option>
													<option value="https://fal.run/fal-ai/flux/dev">Flux Dev (Balanced)</option>
													<option value="https://fal.run/fal-ai/stable-diffusion-v35-large">SD v3.5 Large</option>
													<option value="https://fal.run/fal-ai/stable-diffusion-v3-medium">SD v3 Medium</option>
													<option value="https://fal.run/fal-ai/stable-cascade">Stable Cascade</option>
													<option value="https://fal.run/fal-ai/playground-v25">Playground v2.5</option>
												</select>
											</div>
											<div class="col-md-6">
												<label for="sizeSelect" class="form-label">{{__('default.Size')}}</label>
												<select id="sizeSelect" class="form-select">
													<option value="square_hd">Square HD</option>
													<option value="square">Square</option>
													<option value="portrait_4_3">Portrait 4:3</option>
													<option value="portrait_16_9">Portrait 16:9</option>
													<option value="landscape_4_3">Landscape 4:3</option>
													<option value="landscape_16_9">Landscape 16:9</option>
												</select>
											</div>
										</div>
										
										<div class="mt-5 mb-2">
						
						<span for="llmSelect" class="form-label">{{__('default.AI Engines:')}}
							@if (Auth::user() && Auth::user()->isAdmin())
								<label class="badge bg-danger">Admin</label>
							@endif
						
						</span>
											<select id="llmSelect" class="form-select mx-auto">
												<option value="">{{__('default.Select an AI Engine')}}</option>
												@if (Auth::user() && Auth::user()->isAdmin())
													<option value="anthropic-sonet">anthropic :: claude-3.5-sonnet (direct)</option>
													<option value="anthropic-haiku">anthropic :: haiku (direct)</option>
													<option value="open-ai-gpt-4o">openai :: gpt-4o (direct)</option>
													<option value="open-ai-gpt-4o-mini">openai :: gpt-4o-mini (direct)</option>
												@endif
												@if (Auth::user() && !empty(Auth::user()->anthropic_key))
													<option value="anthropic-sonet">anthropic :: claude-3.5-sonnet (direct)</option>
													<option value="anthropic-haiku">anthropic :: haiku (direct)</option>
												@endif
												@if (Auth::user() && !empty(Auth::user()->openai_api_key))
													<option value="open-ai-gpt-4o">openai :: gpt-4o (direct)</option>
													<option value="open-ai-gpt-4o-mini">openai :: gpt-4o-mini (direct)</option>
												@endif
											</select>
										</div>
										
										<div class="mb-5" id="modelInfo">
											<div class="mt-1 small" style="border: 1px solid #ccc; border-radius: 5px; padding: 5px;">
												<div id="modelDescription"></div>
												<div id="modelPricing"></div>
											</div>
										</div>
										
										<button type="button" class="btn btn-primary" id="generateImageBtn">
											{{__('default.Generate Image')}}
										</button>
									</div>
								</div>
								
								<!-- Generated Image Preview -->
								<div id="generatedImageArea" class="card-body border-bottom d-none">
									<h6>{{__('default.Generated Image Preview')}}</h6>
									<div class="card">
										<img id="generatedImage" src="" class="card-img-top" alt="Generated Image">
										<div class="card-body">
											<p class="card-text" id="image_prompt"></p>
											<p class="card-text"><small class="text-muted" id="tokensDisplay"></small></p>
										</div>
									</div>
								</div>
								
								<div class="card-body">
									<div class="row g-3" id="imageGrid">
										<!-- Images will be loaded here -->
									</div>
								</div>
							</div>
						</div>
						<!-- Images tab END -->
						
						
						<!-- Close account tab START -->
						<div class="tab-pane fade" id="nav-setting-tab-6">
							<!-- Card START -->
							<div class="card">
								<!-- Card header START -->
								<div class="card-header border-0 pb-0">
									<h5 class="card-title">{{__('default.Delete account')}}</h5>
									<p class="mb-2">{{__('default.We are sorry to hear that you wish to delete your account.')}}</p>
									<p
										class="mb-2">{{__('default.Please note that deleting your account may result in the permanent loss of your data.')}}</p>
									<p
										class="mb-2">{{__('default.We are sad to see you go, but we hope that Auto Blog has been an enjoyable experience for you. We wish you the best in your future endeavors. Goodbye!')}}</p>
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
										<label class="form-check-label"
										       for="deleteaccountCheck">{{__('default.Yes, I\'d like to delete my account')}}</label>
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
	
	<!-- Image Upload Modal -->
	<div class="modal fade" id="uploadImageModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{__('default.Upload Image')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form id="uploadImageForm">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">{{__('default.Image')}}</label>
							<input type="file" class="form-control" name="image" accept="image/*" required>
						</div>
						<div class="mb-3">
							<label class="form-label">{{__('default.Alt Text')}}</label>
							<input type="text" class="form-control" name="alt">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('default.Close')}}</button>
						<button type="submit" class="btn btn-primary">{{__('default.Upload')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Edit Image Modal -->
	<div class="modal fade" id="editImageModal" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{__('default.Edit Image')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form id="editImageForm">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">{{__('default.Alt Text')}}</label>
							<input type="text" class="form-control" name="alt" required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('default.Close')}}</button>
						<button type="submit" class="btn btn-primary">{{__('default.Save')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Full Size Image Modal -->
	<div class="modal fade" id="imagePreviewModal" tabindex="-1">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Image Preview</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body text-center">
					<img src="" id="previewImage" class="img-fluid" alt="">
					<p class="mt-2" id="previewImageDescription"></p>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Vendors -->
	<script src="/assets/vendor/pswmeter/pswmeter.js"></script>
	
	<style>
      .preview-image {
          cursor: pointer;
          transition: opacity 0.3s;
      }

      .preview-image:hover {
          opacity: 0.8;
      }
	</style>
	
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
		
		function loadImages(page = 1) {
			$.get('/upload-images', {page: page}, function (response) {
				const grid = $('#imageGrid');
				grid.empty();
				response.images.data.forEach(image => {
					grid.append(createImageCard(image));
				});
				
				// Add pagination
				updatePagination(response.pagination);
				
				// Add click handlers for image preview
				$('.preview-image').on('click', function () {
					const imageUrl = $(this).data('original-url');
					const imageAlt = $(this).data('alt');
					$('#previewImage').attr('src', imageUrl);
					$('#previewImage').attr('alt', imageAlt);
					$('#previewImageDescription').text(imageAlt);
					$('#imagePreviewModal').modal('show');
				});
				
				$('.edit-image').on('click', function () {
					const id = $(this).data('id');
					const alt = $(this).data('alt');
					
					$('#editImageForm').data('id', id);
					$('#editImageForm').find('[name="alt"]').val(alt);
					$('#editImageModal').modal('show');
					response.images.forEach(image => {
						grid.append(createImageCard(image));
					});
				});
				
				// Delete Image
				$('.delete-upload-image').on('click', function () {
					if (confirm('Are you sure you want to delete this image?')) {
						const id = $(this).data('id');
						
						$.ajax({
							url: `/upload-images/${id}`,
							type: 'DELETE',
							data: {"_token": "{{ csrf_token() }}"},
							success: function () {
								loadImages();
								showNotification('Image deleted successfully', 'success');
							},
							error: function () {
								showNotification('Error deleting image');
							}
						});
					}
				});
			});
		}
		
		function createImageCard(image) {
			let image_url = '';
			let image_original_url = '';
			let image_alt = '';
			
			if (image.image_type==='upload') {
				image_url = 'storage/upload-images/small/' + image.image_small_filename;
				image_original_url = 'storage/upload-images/original/' + image.image_filename;
				image_alt = image.image_alt;
			} else
			{
				image_url = 'storage/ai-images/small/' + image.image_small_filename;
				image_original_url = 'storage/ai-images/original/' + image.image_filename;
				image_alt = image.user_prompt;
			}
			
			return `
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card h-100">
                <img src="${image_url}"
                     class="card-img-top preview-image cursor-pointer"
                     alt="${image_alt}"
                     data-original-url="${image_original_url}"
                     data-alt="${image_alt}"
                     style="cursor: pointer;">
                <div class="card-body">
                    <h6 class="card-title">${image_alt}</h6>
                    <p class="card-text small text-muted">
                        ${new Date(image.created_at).toLocaleDateString()}
                        <span class="badge bg-${image.image_type === 'generated' ? 'success' : 'primary'}">${image.image_type}</span>
                    </p>
                    <div>
												${image.image_type === 'upload' ? `
                            <button class="btn btn-sm btn-primary edit-image mr-2"
                                    data-id="${image.id}"
                                    data-alt="${image_alt}">
                                Edit
                            </button>
														<button class="btn btn-sm btn-danger delete-upload-image"
																		data-id="${image.id}">
																Delete
                        ` : `
                            <button class="btn btn-sm btn-primary edit-generated-image mr-2"
                                    data-user-prompt="${image.user_prompt}"
                                    data-llm-prompt="${image.llm_prompt}">
                                Edit
                            </button>
														<button class="btn btn-sm btn-danger delete-generated-image"
                                data-id="${image.id}">
                            Delete
                        </button>                        `}
                       
                    </div>
                </div>
            </div>
        </div>
    `;
		}
		
		function updatePagination(pagination) {
			// Remove existing pagination
			$('.pagination-container').remove();
			
			const paginationHtml = `
        <nav aria-label="Page navigation" class="mt-4 pagination-container">
            <ul class="pagination justify-content-center">
                <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${pagination.current_page - 1}">Previous</a>
                </li>
                ${generatePaginationItems(pagination)}
                <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${pagination.current_page + 1}">Next</a>
                </li>
            </ul>
        </nav>
    `;
			
			$('#imageGrid').after(paginationHtml);
			
			// Add click handlers for pagination
			$('.pagination .page-link').on('click', function (e) {
				e.preventDefault();
				const page = $(this).data('page');
				if (page > 0 && page <= pagination.last_page) {
					loadImages(page);
				}
			});
		}
		
		function generatePaginationItems(pagination) {
			let items = '';
			for (let i = 1; i <= pagination.last_page; i++) {
				items += `
            <li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
			}
			return items;
		}
		
		let savedLlm = localStorage.getItem('image-gen-llm') || 'anthropic/claude-3-haiku:beta';
		let sessionId = null;
		
		function getLLMsData() {
			return new Promise((resolve, reject) => {
				$.ajax({
					url: '/check-llms-json',
					type: 'GET',
					success: function (data) {
						resolve(data);
					},
					error: function (xhr, status, error) {
						reject(error);
					}
				});
			});
		}
		
		function linkify(text) {
			const urlRegex = /(https?:\/\/[^\s]+)/g;
			return text.replace(urlRegex, function (url) {
				return '<a href="' + url + '" target="_blank" rel="noopener noreferrer">' + url + '</a>';
			});
		}
		
		function setActiveTab(tabId) {
			// Remove active class from all tabs
			$('.nav-link').removeClass('active');
			$('.tab-pane').removeClass('show active');
			
			// Add active class to selected tab
			$(`a[href="#${tabId}"]`).addClass('active');
			$(`#${tabId}`).addClass('show active');
			
			// Update URL without reloading the page
			const newUrl = new URL(window.location);
			newUrl.searchParams.set('tab', tabId);
			window.history.pushState({}, '', newUrl);
		}
		
		
		$(document).ready(function () {
			const savedModel = localStorage.getItem('image-gen-model');
			const savedSize = localStorage.getItem('image-gen-size');
			
			if (savedModel) {
				$('#modelSelect').val(savedModel);
			}
			if (savedSize) {
				$('#sizeSelect').val(savedSize);
			}
			
			// Save preferences when changed
			$('#modelSelect').on('change', function () {
				localStorage.setItem('image-gen-model', $(this).val());
			});
			
			$('#sizeSelect').on('change', function () {
				localStorage.setItem('image-gen-size', $(this).val());
			});
			
			
			loadImages();
			
			getLLMsData().then(function (llmsData) {
				const llmSelect = $('#llmSelect');
				
				llmsData.forEach(function (model) {
					
					// Calculate and display pricing per million tokens
					let promptPricePerMillion = ((model.pricing.prompt || 0) * 1000000).toFixed(2);
					let completionPricePerMillion = ((model.pricing.completion || 0) * 1000000).toFixed(2);
					
					llmSelect.append($('<option>', {
						value: model.id,
						text: model.name + ' - $' + promptPricePerMillion + ' / $' + completionPricePerMillion,
						'data-description': model.description,
						'data-prompt-price': model.pricing.prompt || 0,
						'data-completion-price': model.pricing.completion || 0,
					}));
				});
				
				// Set the saved LLM if it exists
				if (savedLlm) {
					llmSelect.val(savedLlm);
				}
				
				llmSelect.on('click', function () {
					$('#modelInfo').removeClass('d-none');
				});
				
				// Show description on change
				llmSelect.change(function () {
					const selectedOption = $(this).find('option:selected');
					const description = selectedOption.data('description');
					const promptPrice = selectedOption.data('prompt-price');
					const completionPrice = selectedOption.data('completion-price');
					$('#modelDescription').html(linkify(description || ''));
					
					// Calculate and display pricing per million tokens
					const promptPricePerMillion = (promptPrice * 1000000).toFixed(2);
					const completionPricePerMillion = (completionPrice * 1000000).toFixed(2);
					
					$('#modelPricing').html(`
                <strong>Pricing (per million tokens):</strong> Prompt: $${promptPricePerMillion} - Completion: $${completionPricePerMillion}
            `);
				});
				
				// Trigger change to show initial description
				llmSelect.trigger('change');
			}).catch(function (error) {
				console.error('Error loading LLMs data:', error);
			});
			
			$("#llmSelect").on('change', function () {
				localStorage.setItem('image-gen-llm', $(this).val());
				savedLlm = $(this).val();
			});
			
			// change $llmSelect to savedLlm
			console.log('set llmSelect to ' + savedLlm);
			var dropdown = document.getElementById('llmSelect');
			var options = dropdown.getElementsByTagName('option');
			
			for (var i = 0; i < options.length; i++) {
				if (options[i].value === savedLlm) {
					dropdown.selectedIndex = i;
				}
			}
			
			// Handle tab clicks
			$('.nav-link').on('click', function (e) {
				const tabId = $(this).attr('href').substring(1); // Remove the # from href
				setActiveTab(tabId);
			});
			
			// Check URL parameters on page load
			const urlParams = new URLSearchParams(window.location.search);
			const activeTab = urlParams.get('tab');
			
			if (activeTab) {
				setActiveTab(activeTab);
			} else {
				// Set default tab if no tab parameter in URL
				setActiveTab('nav-setting-tab-1');
			}
			
			// Handle browser back/forward buttons
			window.addEventListener('popstate', function () {
				const urlParams = new URLSearchParams(window.location.search);
				const activeTab = urlParams.get('tab');
				
				if (activeTab) {
					setActiveTab(activeTab);
				} else {
					setActiveTab('nav-setting-tab-1');
				}
			});
			
			// Handle image generation
			$('#generateImageBtn').on('click', function () {
				const promptEnhancer = $('#promptEnhancer').val();
				const userPrompt = $('#userPrompt').val();
				const llm = $('#llmSelect').val();
				const model = $('#modelSelect').val();
				const size = $('#sizeSelect').val();
				
				$(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Generating...');
				
				$.ajax({
					url: '{{ route('send-image-gen-prompt') }}',
					method: 'POST',
					data: {
						prompt_enhancer: promptEnhancer,
						user_prompt: userPrompt,
						llm: llm,
						model: model,
						size: size
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					dataType: 'json',
					success: function (result) {
						if (result.success) {
							$('#generatedImageArea').removeClass('d-none');
							$('#generatedImage').attr('src', '/storage/ai-images/large/' + result.image_large_filename);
							$('#image_prompt').text(result.image_prompt);
							$('#tokensDisplay').text(`Tokens Used: ${result.prompt_tokens}/${result.completion_tokens}`);
							loadImages();
						}
						$('#generateImageBtn').prop('disabled', false).text('Generate Image');
					},
					error: function () {
						showNotification('Error generating image');
						$('#generateImageBtn').prop('disabled', false).text('Generate Image');
					}
				});
			});
			
			// Delete generated image
			$(document).on('click', '.delete-generated-image', function () {
				if (confirm('Are you sure you want to delete this generated image?')) {
					const sessionId = $(this).data('id');
					$.ajax({
						url: `/image-gen/${sessionId}`,
						type: 'DELETE',
						data: {"_token": "{{ csrf_token() }}"},
						success: function () {
							loadImages();
							showNotification('Generated image deleted successfully', 'success');
						},
						error: function () {
							showNotification('Error deleting generated image');
						}
					});
				}
			});
			
			$(document).on('click', '.edit-generated-image', function () {
				const userPrompt = $(this).data('user-prompt');
				const llmPrompt = $(this).data('llm-prompt');
				
				// Show the image generation section
				$('#imageGenSection').collapse('show');
				
				// Scroll to the form
				$('html, body').animate({
					scrollTop: $('#imageGenSection').offset().top - 100
				}, 500);
				
				// Set the values in the form
				// Decode HTML entities before setting
				const decodedUserPrompt = $('<div/>').html(userPrompt).text();
				const decodedLlmPrompt = $('<div/>').html(llmPrompt).text();
				
				$('#userPrompt').val(decodedUserPrompt);
				$('#promptEnhancer').val(decodedLlmPrompt);
			});
			
			
			// Upload Image
			$('#uploadImageBtn').on('click', function () {
				$('#uploadImageModal').modal('show');
			});
			
			$('#uploadImageForm').submit(function (e) {
				e.preventDefault();
				const formData = new FormData(this);
				
				$.ajax({
					url: '/upload-images',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function () {
						$('#uploadImageModal').modal('hide');
						loadImages();
						showNotification('Image uploaded successfully', 'success');
					},
					error: function () {
						showNotification('Error uploading image');
					}
				});
			});
			
			
			$('#editImageForm').submit(function (e) {
				e.preventDefault();
				const id = $(this).data('id');
				
				$.ajax({
					url: `/upload-images/${id}`,
					type: 'PUT',
					data: $(this).serialize(),
					success: function () {
						$('#editImageModal').modal('hide');
						loadImages();
						showNotification('Image updated successfully', 'success');
					},
					error: function () {
						showNotification('Error updating image');
					}
				});
			});
			
			
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
