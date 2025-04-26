@extends('layouts.admin')

@section('title', __('default.Manage Images'))
@section('page-title', __('default.Manage Images'))

@section('top-bar-actions')
	<div class="d-flex gap-2 ms-3">
		<button class="btn btn-sm btn-primary" id="uploadImageBtn">
			<i class="bi bi-upload me-1"></i> {{__('default.Upload Image')}}
		</button>
		<button class="btn btn-sm btn-success" data-bs-toggle="collapse" data-bs-target="#imageGenSection" aria-expanded="false" aria-controls="imageGenSection">
			<i class="bi bi-stars me-1"></i> {{__('default.Generate with AI')}}
		</button>
	</div>
@endsection

@section('content')
	<!-- Image Generation Section -->
	<div class="collapse mb-4" id="imageGenSection">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">{{__('default.Generate Image with AI')}}</h5>
			</div>
			<div class="card-body">
				{{-- Prompt Enhancer --}}
				<div class="mb-3">
					<label for="promptEnhancer" class="form-label">{{__('default.Prompt Enhancer')}}:</label>
					<textarea class="form-control form-control-sm" id="promptEnhancer" rows="4">##UserPrompt## Write a prompt to create an image using the above text.: Write in English even if the above text is written in another language. With the above information, compose a image. Write it as a single paragraph. The instructions should focus on the text elements of the image.</textarea>
					<small class="form-text text-muted">Uses the selected AI Engine below to refine your User Prompt before sending it to the image generator. Use `##UserPrompt##` placeholder.</small>
				</div>
				
				{{-- User Prompt --}}
				<div class="mb-3">
					<label for="userPrompt" class="form-label">{{__('default.User Prompt')}}:</label>
					<textarea class="form-control" id="userPrompt" rows="2" placeholder="e.g., A futuristic cityscape at sunset"></textarea>
				</div>
				
				{{-- Model & Size Selection --}}
				<div class="row mb-3">
					<div class="col-md-6">
						<label for="modelSelect" class="form-label">{{__('default.Image Model')}}</label>
						<select id="modelSelect" class="form-select form-select-sm">
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
						<select id="sizeSelect" class="form-select form-select-sm">
							<option value="square_hd">Square HD (1024x1024)</option>
							<option value="square">Square (512x512)</option>
							<option value="portrait_4_3">Portrait 4:3</option>
							<option value="portrait_16_9">Portrait 16:9</option>
							<option value="landscape_4_3">Landscape 4:3</option>
							<option value="landscape_16_9">Landscape 16:9</option>
						</select>
					</div>
				</div>
				
				{{-- LLM Selector for Prompt Enhancer --}}
				<div class="mb-3">
					<label for="llmSelect" class="form-label">{{__('default.AI Engine (for Prompt Enhancer):')}}
						@if (Auth::user() && Auth::user()->isAdmin())
							<span class="badge bg-danger ms-1">Admin</span>
						@endif
					</label>
					<select id="llmSelect" class="form-select form-select-sm">
						<option value="">{{__('default.Select an AI Engine')}}</option>
						{{-- Options loaded via JS --}}
					</select>
					<div id="modelInfo" class="mt-2" style="display: none;"> {{-- Initially hidden --}}
						<div class="border rounded p-2 bg-body-tertiary small">
							<div id="modelDescription" class="mb-1"></div>
							<div id="modelPricing" class="text-muted"></div>
						</div>
					</div>
				</div>
				
				{{-- Generate Button --}}
				<div class="text-end">
					<button type="button" class="btn btn-success" id="generateImageBtn">
						<span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
						{{__('default.Generate Image')}}
					</button>
				</div>
				
				{{-- Generated Image Preview Area --}}
				<div id="generatedImageArea" class="mt-4 border-top pt-4 d-none">
					<h6>{{__('default.Generated Image Preview')}}</h6>
					<div class="card mb-3">
						<img id="generatedImage" src="" class="card-img-top" alt="Generated Image" style="max-height: 400px; object-fit: contain;">
						<div class="card-body">
							<p class="card-text small" id="image_prompt"></p>
							<p class="card-text"><small class="text-muted" id="tokensDisplay"></small></p>
						</div>
					</div>
					<p class="text-muted small">The generated image has been automatically saved to your library below.</p>
				</div>
			
			</div>
		</div>
	</div>
	
	<!-- Image Library -->
	<div class="card">
		<div class="card-header border-bottom pb-3">
			<h5 class="card-title mb-0">{{__('default.Image Library')}}</h5>
		</div>
		<div class="card-body">
			<div class="row g-3" id="imageGrid">
				<!-- Images will be loaded here -->
				<div class="col-12 text-center py-5">
                    <span class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
					<p class="mt-2">Loading images...</p>
				</div>
			</div>
			<!-- Pagination container -->
			<div id="imagePaginationContainer" class="mt-4 d-flex justify-content-center">
				<!-- Pagination will be added here -->
			</div>
		</div>
	</div>
	
	
	<!-- Image Upload Modal -->
	<div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="uploadImageModalLabel">{{__('default.Upload Image')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="uploadImageForm">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label for="uploadFile" class="form-label">{{__('default.Image File')}}</label>
							<input type="file" class="form-control" id="uploadFile" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required>
							<small class="form-text text-muted">Max 5MB. Allowed types: JPG, PNG, GIF, WEBP.</small>
						</div>
						<div class="mb-3">
							<label for="uploadAlt" class="form-label">{{__('default.Alt Text')}} <span class="text-muted small">(Optional, defaults to filename)</span></label>
							<input type="text" class="form-control" id="uploadAlt" name="alt" placeholder="Describe the image for accessibility">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('default.Close')}}</button>
						<button type="submit" class="btn btn-primary">
							<span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
							{{__('default.Upload')}}
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Edit Image Modal -->
	<div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editImageModalLabel">{{__('default.Edit Image Alt Text')}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="editImageForm"> {{-- Action and ID set via JS --}}
					@csrf
					@method('PUT')
					<div class="modal-body">
						<div class="mb-3">
							<label for="editAlt" class="form-label">{{__('default.Alt Text')}}</label>
							<input type="text" class="form-control" id="editAlt" name="alt" required placeholder="Describe the image for accessibility">
						</div>
						{{-- Add other editable fields if needed (e.g., title, caption) --}}
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('default.Close')}}</button>
						<button type="submit" class="btn btn-primary">
							<span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
							{{__('default.Save Changes')}}
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Full Size Image Preview Modal -->
	<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered"> {{-- Larger, centered --}}
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body text-center">
					<img src="" id="previewImage" class="img-fluid rounded" alt="Image Preview" style="max-height: 70vh;">
					<p class="mt-2 text-muted small" id="previewImageDescription"></p>
				</div>
			</div>
		</div>
	</div>
	
	<style>
      #imageGrid .card {
          transition: box-shadow 0.2s ease-in-out;
      }
      #imageGrid .card:hover {
          box-shadow: var(--bs-box-shadow);
      }
      #imageGrid .card-img-top {
          aspect-ratio: 1 / 1; /* Make images square */
          object-fit: cover;
          cursor: pointer;
          border-bottom: 1px solid var(--bs-border-color);
      }
      #imageGrid .card-body {
          padding: 0.75rem;
      }
      #imageGrid .card-title {
          font-size: 0.9rem;
          margin-bottom: 0.25rem;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
      }
      #imageGrid .card-text {
          font-size: 0.8rem;
          margin-bottom: 0.5rem;
      }
      #imageGrid .badge {
          font-size: 0.7rem;
          vertical-align: middle;
      }
      #imageGrid .btn-group-sm .btn {
          padding: 0.15rem 0.4rem;
          font-size: 0.75rem;
      }
	</style>
@endsection

@push('scripts')
	<script>
		// --- Global Vars ---
		let currentPage = 1;
		let savedLlm = localStorage.getItem('image-gen-llm') || 'anthropic/claude-3-haiku:beta'; // Default LLM for enhancer
		
		// --- Image Loading & Card Creation ---
		function loadImages(page = 1) {
			currentPage = page;
			const grid = $('#imageGrid');
			const paginationContainer = $('#imagePaginationContainer');
			grid.html(`
            <div class="col-12 text-center py-5">
                <span class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></span>
                <p class="mt-2">Loading images (Page ${page})...</p>
            </div>`); // Show loading state
			paginationContainer.empty();
			
			$.get('/upload-images', { page: page }, function (response) {
				grid.empty(); // Clear loading state
				if (response.images && response.images.data && response.images.data.length > 0) {
					response.images.data.forEach(image => {
						grid.append(createImageCard(image));
					});
					updatePagination(response.pagination);
				} else {
					grid.html('<div class="col-12 text-center py-5"><i class="bi bi-image display-4 text-muted"></i><p class="mt-3 mb-0">No images found.</p></div>');
				}
			}).fail(function() {
				grid.html('<div class="col-12 text-center py-5 text-danger"><i class="bi bi-exclamation-triangle display-4"></i><p class="mt-3 mb-0">Failed to load images.</p></div>');
			});
		}
		
		function createImageCard(image) {
			let image_url = '';
			let image_original_url = '';
			let image_alt = '';
			let type_badge = '';
			let edit_button = '';
			let delete_button = '';
			
			if (image.image_type === 'upload') {
				image_url = `/storage/upload-images/small/${image.image_small_filename}`;
				image_original_url = `/storage/upload-images/original/${image.image_original_filename}`; // Corrected original filename
				image_alt = image.image_alt || 'Uploaded Image';
				type_badge = `<span class="badge rounded-pill bg-primary ms-1">Upload</span>`;
				edit_button = `<button class="btn btn-sm btn-outline-primary edit-image" data-id="${image.id}" data-alt="${image_alt}" title="Edit Alt Text"><i class="bi bi-pencil"></i></button>`;
				delete_button = `<button class="btn btn-sm btn-outline-danger delete-upload-image" data-id="${image.id}" title="Delete Image"><i class="bi bi-trash"></i></button>`;
			} else { // Assuming 'generated'
				image_url = `/storage/ai-images/small/${image.image_small_filename}`;
				image_original_url = `/storage/ai-images/original/${image.image_original_filename}`; // Corrected original filename
				image_alt = image.user_prompt || 'AI Generated Image'; // Use user prompt as alt
				type_badge = `<span class="badge rounded-pill bg-success ms-1">AI</span>`;
				// Add data attributes for user_prompt and llm_prompt (enhancer)
				edit_button = `<button class="btn btn-sm btn-outline-secondary edit-generated-image"
                                    data-user-prompt="${escapeHtml(image.user_prompt || '')}"
                                    data-llm-prompt="${escapeHtml(image.llm_prompt || '')}"
                                    title="Edit & Regenerate Prompt"><i class="bi bi-arrow-repeat"></i></button>`;
				delete_button = `<button class="btn btn-sm btn-outline-danger delete-generated-image" data-id="${image.id}" title="Delete Generated Image"><i class="bi bi-trash"></i></button>`;
			}
			
			// Format date nicely
			const formattedDate = new Date(image.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
			
			return `
            <div class="col-6 col-md-4 col-lg-3 mb-4">
			<div class="card h-100 shadow-sm">
					<img src="${image_url}" class="card-img-top preview-image" alt="${escapeHtml(image_alt)}"
                         data-original-url="${image_original_url}" data-alt="${escapeHtml(image_alt)}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title" title="${escapeHtml(image_alt)}">${escapeHtml(image_alt)}</h6>
                        <p class="card-text text-muted">
                            ${formattedDate} ${type_badge}
                        </p>
                        <div class="mt-auto d-flex justify-content-end btn-group btn-group-sm"> ${edit_button} ${delete_button} </div>
                    </div>
                </div>
            </div>`;
		}
		
		// --- Pagination ---
		function updatePagination(pagination) {
			const container = $('#imagePaginationContainer');
			container.empty();
			if (!pagination || pagination.last_page <= 1) {
				return; // No pagination needed if only one page or no data
			}
			
			const paginationHtml = `
            <nav aria-label="Image library navigation">
                <ul class="pagination pagination-sm">
                    <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link page-nav-link" href="#" data-page="${pagination.current_page - 1}">&laquo;</a>
                    </li>
                    ${generatePaginationItems(pagination)}
                    <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                        <a class="page-link page-nav-link" href="#" data-page="${pagination.current_page + 1}">&raquo;</a>
                    </li>
                </ul>
            </nav>`;
			container.html(paginationHtml);
		}
		
		function generatePaginationItems(pagination) {
			let items = '';
			const maxPagesToShow = 5; // Adjust number of page links shown
			let startPage = Math.max(1, pagination.current_page - Math.floor(maxPagesToShow / 2));
			let endPage = Math.min(pagination.last_page, startPage + maxPagesToShow - 1);
			
			// Adjust startPage if endPage reaches the limit first
			if (endPage === pagination.last_page) {
				startPage = Math.max(1, endPage - maxPagesToShow + 1);
			}
			
			if (startPage > 1) {
				items += `<li class="page-item"><a class="page-link page-nav-link" href="#" data-page="1">1</a></li>`;
				if (startPage > 2) {
					items += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
				}
			}
			
			for (let i = startPage; i <= endPage; i++) {
				items += `
                <li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                    <a class="page-link page-nav-link" href="#" data-page="${i}">${i}</a>
                </li>`;
			}
			
			if (endPage < pagination.last_page) {
				if (endPage < pagination.last_page - 1) {
					items += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
				}
				items += `<li class="page-item"><a class="page-link page-nav-link" href="#" data-page="${pagination.last_page}">${pagination.last_page}</a></li>`;
			}
			
			return items;
		}
		
		// --- LLM Data Loading ---
		function getLLMsData() {
			return $.ajax({
				url: '/check-llms-json', // Make sure this route exists and returns JSON
				type: 'GET',
				dataType: 'json' // Ensure jQuery parses the response as JSON
			});
		}
		
		function populateLLMSelector(llmsData) {
			const llmSelect = $('#llmSelect');
			llmSelect.empty().append($('<option>', { value: "", text: "{{__('default.Select an AI Engine')}}" })); // Add placeholder back
			
			if (!llmsData || !Array.isArray(llmsData)) {
				console.error('Invalid LLMs data received:', llmsData);
				return; // Exit if data is not as expected
			}
			
			llmsData.forEach(function (model) {
				let promptPricePerMillion = ((model.pricing?.prompt || 0) * 1000000).toFixed(2);
				let completionPricePerMillion = ((model.pricing?.completion || 0) * 1000000).toFixed(2);
				llmSelect.append($('<option>', {
					value: model.id,
					text: `${model.name} ($${promptPricePerMillion} / $${completionPricePerMillion})`,
					'data-description': model.description || '',
					'data-prompt-price': model.pricing?.prompt || 0,
					'data-completion-price': model.pricing?.completion || 0,
				}));
			});
			
			// Set the saved LLM if it exists and is in the list
			if (savedLlm && llmSelect.find(`option[value="${savedLlm}"]`).length > 0) {
				llmSelect.val(savedLlm);
			} else if (llmSelect.find('option').length > 1) {
				// If saved LLM not found, select the first available option (after placeholder)
				llmSelect.prop('selectedIndex', 1);
				savedLlm = llmSelect.val(); // Update savedLlm
				localStorage.setItem('image-gen-llm', savedLlm);
			}
			llmSelect.trigger('change'); // Trigger change to show initial info
		}
		
		function updateLLMInfo() {
			const selectedOption = $('#llmSelect').find('option:selected');
			const description = selectedOption.data('description');
			const promptPrice = selectedOption.data('prompt-price');
			const completionPrice = selectedOption.data('completion-price');
			const modelInfoDiv = $('#modelInfo');
			
			if ($('#llmSelect').val()) {
				$('#modelDescription').html(linkify(description || 'No description available.'));
				const promptPricePerMillion = (promptPrice * 1000000).toFixed(2);
				const completionPricePerMillion = (completionPrice * 1000000).toFixed(2);
				$('#modelPricing').html(`<strong>Pricing (per M tokens):</strong> Prompt: $${promptPricePerMillion} / Completion: $${completionPricePerMillion}`);
				modelInfoDiv.show(); // Use show() instead of removeClass('d-none')
			} else {
				modelInfoDiv.hide(); // Use hide()
			}
		}
		
		// --- Utility Functions ---
		function linkify(text) {
			if (!text) return '';
			const urlRegex = /(https?:\/\/[^\s]+)/g;
			return escapeHtml(text).replace(urlRegex, '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>');
		}
		
		function escapeHtml(unsafe) {
			if (!unsafe) return '';
			return unsafe
				.replace(/&/g, "&amp;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;")
				.replace(/"/g, "&quot;")
				.replace(/'/g, "&#039;");
		}
		
		// --- Event Handlers ---
		$(document).ready(function () {
			// Initial load
			loadImages(1);
			
			// Load LLMs and populate selector
			getLLMsData()
				.done(populateLLMSelector)
				.fail(function (xhr) {
					console.error('Error loading LLMs data:', xhr);
					showNotification('Could not load AI Engines for prompt enhancer.', 'warning');
				});
			
			// LLM Selector Change Handler
			$('#llmSelect').on('change', function () {
				savedLlm = $(this).val();
				localStorage.setItem('image-gen-llm', savedLlm);
				updateLLMInfo();
			});
			
			// Load saved image generator model/size preferences
			const savedModel = localStorage.getItem('image-gen-model');
			const savedSize = localStorage.getItem('image-gen-size');
			if (savedModel) $('#modelSelect').val(savedModel);
			if (savedSize) $('#sizeSelect').val(savedSize);
			
			// Save image generator model/size preferences on change
			$('#modelSelect').on('change', function () { localStorage.setItem('image-gen-model', $(this).val()); });
			$('#sizeSelect').on('change', function () { localStorage.setItem('image-gen-size', $(this).val()); });
			
			// Pagination Click Handler
			$(document).on('click', '.page-nav-link', function (e) {
				e.preventDefault();
				const page = $(this).data('page');
				if (page && page !== currentPage) {
					loadImages(page);
				}
			});
			
			// Image Preview Click Handler
			$(document).on('click', '.preview-image', function () {
				const imageUrl = $(this).data('original-url');
				const imageAlt = $(this).data('alt');
				$('#previewImage').attr('src', imageUrl).attr('alt', imageAlt);
				$('#previewImageDescription').text(imageAlt);
				$('#imagePreviewModal').modal('show');
			});
			
			// --- Upload Image Handlers ---
			$('#uploadImageBtn').on('click', function () {
				$('#uploadImageForm')[0].reset(); // Clear form
				$('#uploadImageModal').modal('show');
			});
			
			$('#uploadImageForm').submit(function (e) {
				e.preventDefault();
				const form = $(this);
				const formData = new FormData(this);
				const submitButton = form.find('button[type="submit"]');
				const spinner = submitButton.find('.spinner-border');
				
				spinner.removeClass('d-none');
				submitButton.prop('disabled', true);
				
				$.ajax({
					url: '/upload-images', // Make sure this route is defined for POST
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function (response) {
						$('#uploadImageModal').modal('hide');
						loadImages(1); // Reload images on page 1
						showNotification('Image uploaded successfully', 'success');
					},
					error: function (xhr) {
						console.error(xhr.responseText);
						showNotification(xhr.responseJSON?.message || 'Error uploading image. Check file size and type.', 'danger');
					},
					complete: function() {
						spinner.addClass('d-none');
						submitButton.prop('disabled', false);
					}
				});
			});
			
			// --- Edit Uploaded Image Handlers ---
			$(document).on('click', '.edit-image', function () {
				const id = $(this).data('id');
				const alt = $(this).data('alt');
				const form = $('#editImageForm');
				form.attr('action', `/upload-images/${id}`); // Set action URL
				form.find('#editAlt').val(alt);
				$('#editImageModal').modal('show');
			});
			
			$('#editImageForm').submit(function (e) {
				e.preventDefault();
				const form = $(this);
				const submitButton = form.find('button[type="submit"]');
				const spinner = submitButton.find('.spinner-border');
				
				spinner.removeClass('d-none');
				submitButton.prop('disabled', true);
				
				$.ajax({
					url: form.attr('action'),
					type: 'POST', // Using POST with _method=PUT
					data: form.serialize(), // No file upload, so serialize is fine
					success: function (response) {
						$('#editImageModal').modal('hide');
						loadImages(currentPage); // Reload current page
						showNotification('Image alt text updated successfully', 'success');
					},
					error: function (xhr) {
						console.error(xhr.responseText);
						showNotification(xhr.responseJSON?.message || 'Error updating image alt text.', 'danger');
					},
					complete: function() {
						spinner.addClass('d-none');
						submitButton.prop('disabled', false);
					}
				});
			});
			
			// --- Delete Uploaded Image Handler ---
			$(document).on('click', '.delete-upload-image', function () {
				if (confirm('Are you sure you want to delete this uploaded image? This cannot be undone.')) {
					const id = $(this).data('id');
					const button = $(this);
					button.prop('disabled', true);
					
					$.ajax({
						url: `/upload-images/${id}`,
						type: 'DELETE',
						data: {"_token": "{{ csrf_token() }}"},
						success: function () {
							button.closest('.col-6').fadeOut(300, function() { $(this).remove(); });
							showNotification('Image deleted successfully', 'success');
							// Optionally reload if pagination counts change significantly
							// loadImages(currentPage);
						},
						error: function (xhr) {
							showNotification(xhr.responseJSON?.message || 'Error deleting image', 'danger');
							button.prop('disabled', false);
						}
					});
				}
			});
			
			// --- AI Image Generation Handlers ---
			$('#generateImageBtn').on('click', function () {
				const button = $(this);
				const spinner = button.find('.spinner-border');
				const promptEnhancer = $('#promptEnhancer').val();
				const userPrompt = $('#userPrompt').val();
				const llm = $('#llmSelect').val();
				const model = $('#modelSelect').val();
				const size = $('#sizeSelect').val();
				
				if (!userPrompt) {
					showNotification('Please enter a User Prompt.', 'warning');
					return;
				}
				if (!llm && promptEnhancer !== '##UserPrompt##') {
					showNotification('Please select an AI Engine for the Prompt Enhancer.', 'warning');
					return;
				}
				
				spinner.removeClass('d-none');
				button.prop('disabled', true).contents().last().replaceWith(" Generating..."); // Change text
				
				$.ajax({
					url: '{{ route('send-image-gen-prompt') }}',
					method: 'POST',
					data: {
						prompt_enhancer: promptEnhancer,
						user_prompt: userPrompt,
						llm: llm,
						model: model,
						size: size,
						_token: "{{ csrf_token() }}"
					},
					dataType: 'json',
					success: function (result) {
						if (result.success) {
							$('#generatedImageArea').removeClass('d-none');
							// Use large or medium based on preference/availability
							$('#generatedImage').attr('src', `/storage/ai-images/large/${result.image_large_filename}?t=${Date.now()}`); // Add timestamp to bust cache
							$('#image_prompt').text(result.image_prompt || 'Prompt not returned.');
							$('#tokensDisplay').text(`Enhancer Tokens: ${result.prompt_tokens}/${result.completion_tokens}`);
							showNotification(result.message || 'Image generated successfully!', 'success');
							loadImages(1); // Refresh library on page 1
						} else {
							showNotification(result.message || 'Error generating image (server)', 'danger');
							$('#generatedImageArea').addClass('d-none'); // Hide preview on error
						}
					},
					error: function (xhr) {
						console.error(xhr.responseText);
						showNotification(xhr.responseJSON?.message || 'Error generating image (network/server)', 'danger');
						$('#generatedImageArea').addClass('d-none'); // Hide preview on error
					},
					complete: function() {
						spinner.addClass('d-none');
						button.prop('disabled', false).contents().last().replaceWith(" {{__('default.Generate Image')}}"); // Restore text
					}
				});
			});
			
			// --- Edit/Regenerate AI Image Prompt Handler ---
			$(document).on('click', '.edit-generated-image', function () {
				const userPrompt = $(this).data('user-prompt');
				const llmPrompt = $(this).data('llm-prompt'); // This is the enhancer template used
				
				// Show the image generation section if hidden
				$('#imageGenSection').collapse('show');
				
				// Scroll to the form smoothly
				$('html, body').animate({
					scrollTop: $('#imageGenSection').offset().top - 70 // Adjust offset for sticky navbar
				}, 500);
				
				// Set the values in the form
				$('#userPrompt').val(userPrompt);
				$('#promptEnhancer').val(llmPrompt || '##UserPrompt##'); // Use saved enhancer or default
				$('#userPrompt').focus(); // Focus the user prompt field
			});
			
			
			// --- Delete Generated Image Handler ---
			$(document).on('click', '.delete-generated-image', function () {
				if (confirm('Are you sure you want to delete this generated image? This cannot be undone.')) {
					const id = $(this).data('id'); // Should be image ID now
					const button = $(this);
					button.prop('disabled', true);
					
					$.ajax({
						// Use the same endpoint as uploaded images, controller handles type
						url: `/upload-images/${id}`, // Changed from /image-gen/{session_id}
						type: 'DELETE',
						data: {"_token": "{{ csrf_token() }}"},
						success: function () {
							button.closest('.col-6').fadeOut(300, function() { $(this).remove(); });
							showNotification('Generated image deleted successfully', 'success');
						},
						error: function (xhr) {
							showNotification(xhr.responseJSON?.message || 'Error deleting generated image', 'danger');
							button.prop('disabled', false);
						}
					});
				}
			});
			
		}); // End document ready
	</script>
@endpush
