@extends('layouts.app')
@section('title', isset($article) ? __('default.Edit Article') : __('default.Create Article'))
@section('content')
	<!-- Main content START -->
	<main>
		<div class="container mb-5" style="min-height: calc(88vh);">
			<div class="row mt-3">
				<!-- Main content START -->
				<div class="col-12 col-xl-8 col-lg-8 mx-auto">
					<h5>{{ isset($article) ? __('default.Edit Article') : __('default.Create Article') }}</h5>
					
					<form id="articleForm" method="POST"
					      action="{{ isset($article) ? route('articles.update', $article->id) : route('articles.store') }}">
						@csrf
						@if(isset($article))
							@method('PUT')
						@endif
						
						<!-- Language Selection -->
						<div class="mb-3">
							<label for="language_id" class="form-label">{{ __('default.Language') }}</label>
							<select class="form-select" id="language_id" name="language_id" required>
								<option value="">{{ __('default.Select Language') }}</option>
								@foreach($languages as $language)
									<option value="{{ $language->id }}"
										{{ (isset($article) && $article->language_id == $language->id) ? 'selected' : '' }}>
										{{ $language->language_name }}
									</option>
								@endforeach
							</select>
						</div>
						
						<!-- Title -->
						<div class="mb-3">
							<label for="title" class="form-label">{{ __('default.Title') }}</label>
							<input type="text" class="form-control" id="title" name="title"
							       value="{{ isset($article) ? $article->title : old('title') }}" required>
						</div>
						
						<!-- Subtitle -->
						<div class="mb-3">
							<label for="subtitle" class="form-label">{{ __('default.Subtitle') }}</label>
							<input type="text" class="form-control" id="subtitle" name="subtitle"
							       value="{{ isset($article) ? $article->subtitle : old('subtitle') }}">
						</div>
						
						<!-- Featured Image -->
						<div class="mb-3">
							<label class="form-label">{{ __('default.Featured Image') }}</label>
							<input type="hidden" name="featured_image_id" id="featured_image_id"
							       value="{{ isset($article) ? $article->featured_image_id : '' }}">
							<div id="selectedImagePreview" class="mt-2">
								@if(isset($article) && $article->featuredImage)
									<img src="{{ $article->featuredImage->getSmallUrl() }}" alt="Featured Image" class="img-thumbnail"
									     style="max-width: 200px;">
								@endif
							</div>
							<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#imageModal">
								{{ __('default.Select Featured Image') }}
							</button>
						</div>
						
						<!-- Categories -->
						<div class="mb-3">
							<label class="form-label">{{ __('default.Categories') }}</label>
							<div class="row">
								@foreach($categories as $category)
									<div class="col-md-4 mb-2">
										<div class="form-check">
											<input class="form-check-input category-checkbox"
											       type="checkbox"
											       name="categories[]"
											       value="{{ $category->id }}"
											       id="category{{ $category->id }}"
												{{ isset($article) && $article->categories->contains($category->id) ? 'checked' : '' }}>
											<label class="form-check-label" for="category{{ $category->id }}">
												{{ $category->category_name }}
											</label>
										</div>
									</div>
								@endforeach
							</div>
						</div>
						
						<!-- Body Content -->
						<div class="mb-3">
							<label for="body" class="form-label">{{ __('default.Content') }}</label>
							<textarea class="form-control" id="body" name="body" rows="10"
							          required>{{ isset($article) ? $article->body : old('body') }}</textarea>
						</div>
						
						<!-- Meta Description -->
						<div class="mb-3">
							<label for="meta_description" class="form-label">{{ __('default.Meta Description') }}</label>
							<input type="text" class="form-control" id="meta_description" name="meta_description"
							       value="{{ isset($article) ? $article->meta_description : old('meta_description') }}">
						</div>
						
						<!-- Short Description -->
						<div class="mb-3">
							<label for="short_description" class="form-label">{{ __('default.Short Description') }}</label>
							<textarea class="form-control" id="short_description" name="short_description"
							          rows="3">{{ isset($article) ? $article->short_description : old('short_description') }}</textarea>
						</div>
						
						<!-- Publication Status -->
						<div class="mb-3">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1"
									{{ isset($article) && $article->is_published ? 'checked' : '' }}>
								<label class="form-check-label" for="is_published">
									{{ __('default.Publish Article') }}
								</label>
							</div>
						</div>
						
						<!-- Posted At -->
						<div class="mb-3">
							<label for="posted_at" class="form-label">{{ __('default.Publication Date') }}</label>
							<input type="datetime-local" class="form-control" id="posted_at" name="posted_at"
							       value="{{ isset($article) ? $article->posted_at->format('Y-m-d\TH:i') : old('posted_at') }}">
						</div>
						
						<button type="submit" class="btn btn-primary">
							{{ isset($article) ? __('default.Update Article') : __('default.Create Article') }}
						</button>
					</form>
				</div>
				
				<div class="col-12 col-xl-8 col-lg-8 mx-auto mt-5">
					
					<h5>{{__('default.Chat with AI')}}</h5>
					
					
					<div class="chat-window" id="chatWindow"
					     style="border: 1px solid #ccc; height: 400px; overflow-y: scroll; padding: 10px;">
						<!-- Chat messages will be appended here -->
					</div>
					<div class="mb-3">
						<textarea class="form-control" id="userPrompt" rows="3"></textarea>
					</div>
					<button type="button" class="btn btn-primary" id="sendPromptBtn">{{ __('default.Send Prompt') }}</button>
					
					
					<div class="mt-5 mb-2">
						
						<span for="llmSelect" class="form-label">{{__('default.AI Engines:')}}
							@if (Auth::user() && Auth::user()->isAdmin())
								<label class="badge bg-danger">Admin</label>
							@endif
						
						</span>
						<select id="llmSelect" class="form-select mx-auto">
							<option value="">{{__('default.Select an AI Engine')}}</option>
						</select>
					</div>
					
					<div class="mb-5" id="modelInfo">
						<div class="mt-1 small" style="border: 1px solid #ccc; border-radius: 5px; padding: 5px;">
							<div id="modelDescription"></div>
							<div id="modelPricing"></div>
						</div>
					</div>
				
				</div> <!-- Row END -->
			</div>
		</div>
	</main>
	
	<!-- Image Selection Modal -->
	<div class="modal fade" id="imageModal" tabindex="-1">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ __('default.Select Featured Image') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="row g-3" id="modalImageGrid">
						<!-- Images will be loaded here -->
					</div>
					<!-- Pagination container -->
					<div id="modalPaginationContainer" class="mt-4">
						<!-- Pagination will be added here -->
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	@include('layouts.footer')
@endsection

@push('scripts')
	<style>
      .preview-image {
          cursor: pointer;
          transition: opacity 0.3s;
      }

      .preview-image:hover {
          opacity: 0.8;
      }

      #modalImageGrid .card {
          height: 100%;
      }

      #modalImageGrid .card-img-top {
          object-fit: cover;
          height: 200px;
      }

      .chat-message {
          padding: 10px;
          border-radius: 5px;
          margin-bottom: 10px;
      }

      .user-message {
          background-color: #321c24;
          color: #fff;
      }

      .assistant-message {
          background-color: #421c24;
          color: #fff;
      }

      .error-message {
          background-color: #f8d7da;
          color: #721c24;
      }

      .message-content {
          margin-top: 5px;
          white-space: pre-wrap;
      }

      #chatWindow::-webkit-scrollbar {
          width: 8px;
      }

      #chatWindow::-webkit-scrollbar-track {
          background: #f1f1f1;
      }

      #chatWindow::-webkit-scrollbar-thumb {
          background: #888;
          border-radius: 4px;
      }

      #chatWindow::-webkit-scrollbar-thumb:hover {
          background: #555;
      }
	
	</style>
	<script>
		let savedLlm = localStorage.getItem('chat-llm') || 'anthropic-sonet';
		let sessionId = '{{ isset($chatSession) ? $chatSession->session_id : "" }}';
		
		// Add this to your existing $(document).ready() function
		function loadModalImages(page = 1) {
			$.get('/upload-images', {page: page}, function (response) {
				const grid = $('#modalImageGrid');
				grid.empty();
				
				response.images.data.forEach(image => {
					grid.append(createModalImageCard(image));
				});
				
				updateModalPagination(response.pagination);
			});
		}
		
		function createModalImageCard(image) {
			let image_url = '';
			let image_original_url = '';
			let image_alt = '';
			
			if (image.image_type === 'upload') {
				image_url = '/storage/upload-images/small/' + image.image_small_filename;
				image_original_url = '/storage/upload-images/original/' + image.image_original_filename;
				image_alt = image.image_alt;
			} else {
				image_url = '/storage/ai-images/small/' + image.image_small_filename;
				image_original_url = '/storage/ai-images/original/' + image.image_original_filename;
				image_alt = image.user_prompt;
			}
			
			return `
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card h-100">
                <img src="${image_url}"
                     class="card-img-top preview-image"
                     alt="${image_alt}"
                     style="cursor: pointer;">
                <div class="card-body">
                    <p class="card-text small">${image_alt}</p>
                    <button class="btn btn-sm btn-primary select-modal-image"
                            data-image-id="${image.id}"
                            data-image-url="${image_url}">
                        Select
                    </button>
                </div>
            </div>
        </div>
    `;
		}
		
		function updateModalPagination(pagination) {
			const container = $('#modalPaginationContainer');
			container.empty();
			
			const paginationHtml = `
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link modal-page-link" href="#" data-page="${pagination.current_page - 1}">Previous</a>
                </li>
                ${generateModalPaginationItems(pagination)}
                <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                    <a class="page-link modal-page-link" href="#" data-page="${pagination.current_page + 1}">Next</a>
                </li>
            </ul>
        </nav>
    `;
			
			container.html(paginationHtml);
		}
		
		function generateModalPaginationItems(pagination) {
			let items = '';
			for (let i = 1; i <= pagination.last_page; i++) {
				items += `
            <li class="page-item ${pagination.current_page === i ? 'active' : ''}">
                <a class="page-link modal-page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `;
			}
			return items;
		}
		
		//-------------------------------------------------------------------------
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
		
		function sendMessage() {
			const userPrompt = $('#userPrompt').val().trim();
			const llm = $('#llmSelect').val();
			
			if (!userPrompt || !llm) {
				return;
			}
			
			$('#userPrompt').val('');
			appendMessage('user', userPrompt);
			
			$('#sendPromptBtn')
				.prop('disabled', true)
				.html('<span class="spinner-border spinner-border-sm"></span> Sending...');
			
			// Get current article content
			// Get current article content
			const articleContent = {
				title: $('#title').val(),
				subtitle: $('#subtitle').val(),
				body: $('#body').val(),
				categories: $('.category-checkbox:checked').map(function () {
					return $(this).val();
				}).get()
			};
			
			$.ajax({
				url: '{{ route('send-llm-prompt') }}',
				method: 'POST',
				data: {
					user_prompt: userPrompt,
					session_id: sessionId,
					llm: llm,
					context: JSON.stringify(articleContent) // Send article content as context
				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function (response) {
					if (response.success) {
						appendMessage('assistant', response.result.content, {
							promptTokens: response.result.prompt_tokens,
							completionTokens: response.result.completion_tokens
						});
					} else {
						appendMessage('error', 'Error: ' + response.message);
					}
				},
				error: function (xhr) {
					appendMessage('error', 'Error: Unable to get response from server');
				},
				complete: function () {
					$('#sendPromptBtn')
						.prop('disabled', false)
						.text('Send');
				}
			});
		}
		
		function appendMessage(role, content, tokens = null) {
			let messageHtml = `<div class="chat-message ${role}-message mb-3">`;
			messageHtml += `<strong class="text-capitalize">${role}:</strong> `;
			messageHtml += `<div class="message-content">${linkify(content)}</div>`;
			
			if (tokens && role === 'assistant') {
				messageHtml += `<small class="text-muted">(Tokens: ${tokens.promptTokens}/${tokens.completionTokens})</small>`;
			}
			
			messageHtml += '</div>';
			
			$('#chatWindow').append(messageHtml);
			$('#chatWindow').scrollTop($('#chatWindow')[0].scrollHeight);
		}
		
		function loadChatMessages(sessionId) {
			$.ajax({
				url: `/chat/messages/${sessionId}`,
				type: 'GET',
				success: function (response) {
					$('#chatWindow').empty();
					response.forEach(message => {
						appendMessage(message.role, message.message, {
							promptTokens: message.prompt_tokens,
							completionTokens: message.completion_tokens
						});
					});
				}
			});
		}
		
		//-------------------------------------------------------------------------
		
		$(document).ready(function () {
			
			// Load existing chat messages if session exists
			if (sessionId) {
				loadChatMessages(sessionId);
			}
			
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
				localStorage.setItem('chat-llm', $(this).val());
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
			
			
			// Send message handler
			$('#sendPromptBtn').on('click', function () {
				sendMessage();
			});
			
			// Allow sending with Enter key (Shift+Enter for new line)
			$('#userPrompt').on('keydown', function (e) {
				if (e.key === 'Enter' && !e.shiftKey) {
					e.preventDefault();
					sendMessage();
				}
			});
			
			// Load images when modal is shown
			$('#imageModal').on('show.bs.modal', function () {
				loadModalImages();
			});
			
			// Handle pagination clicks
			$(document).on('click', '.modal-page-link', function (e) {
				e.preventDefault();
				const page = $(this).data('page');
				loadModalImages(page);
			});
			
			// Handle image selection
			$(document).on('click', '.select-modal-image', function () {
				const imageId = $(this).data('image-id');
				const imageUrl = $(this).data('image-url');
				
				// Update the hidden input and preview
				$('#featured_image_id').val(imageId);
				$('#selectedImagePreview').html(`
            <img src="${imageUrl}" alt="Selected Image" class="img-thumbnail" style="max-width: 200px;">
        `);
				
				// Close the modal
				$('#imageModal').modal('hide');
			});
			
		});
	</script>
@endpush
