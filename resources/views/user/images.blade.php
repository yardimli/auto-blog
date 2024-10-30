@extends('layouts.settings')

@section('settings-content')
	
	<!-- Images tab START -->
	<div class="tab-pane" id="nav-setting-tab-4">
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
	
	<style>
      .preview-image {
          cursor: pointer;
          transition: opacity 0.3s;
      }

      .preview-image:hover {
          opacity: 0.8;
      }
	</style>

@endsection

@push('scripts')
	<script>
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
				image_url = '/storage/upload-images/small/' + image.image_small_filename;
				image_original_url = '/storage/upload-images/original/' + image.image_filename;
				image_alt = image.image_alt;
			} else
			{
				image_url = '/storage/ai-images/small/' + image.image_small_filename;
				image_original_url = '/storage/ai-images/original/' + image.image_filename;
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
			
		});
	</script>
@endpush
