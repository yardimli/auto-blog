@extends('layouts.admin')

@section('title', isset($article) ? __('default.Edit Article') : __('default.Create Article'))
@section('page-title')
	{{ isset($article) ? __('default.Edit Article') : __('default.Create Article') }}
@endsection

@section('content')
	<form id="articleForm" method="POST" action="{{ isset($article) ? route('articles.update', $article->id) : route('articles.store') }}">
		@csrf
		@if(isset($article))
			@method('PUT')
		@endif
		
		<div class="row">
			{{-- Main Content Column --}}
			<div class="col-lg-8">
				<div class="card mb-4">
					<div class="card-body">
						<!-- Title -->
						<div class="mb-3">
							<label for="title" class="form-label">{{ __('default.Title') }} <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="title" name="title" value="{{ isset($article) ? $article->title : old('title') }}" required>
						</div>
						
						<!-- Subtitle -->
						<div class="mb-3">
							<label for="subtitle" class="form-label">{{ __('default.Subtitle') }}</label>
							<input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ isset($article) ? $article->subtitle : old('subtitle') }}">
						</div>
						
						<!-- Body Content -->
						<div class="mb-3">
							<label for="body" class="form-label">{{ __('default.Content') }} <span class="text-danger">*</span></label>
							{{-- Consider using a more advanced editor like TinyMCE or Editor.js if needed --}}
							<textarea class="form-control" id="body" name="body" rows="15" required>{{ isset($article) ? $article->body : old('body') }}</textarea>
						</div>
						
						<!-- Short Description -->
						<div class="mb-3">
							<label for="short_description" class="form-label">{{ __('default.Short Description') }}</label>
							<textarea class="form-control" id="short_description" name="short_description" rows="3" placeholder="A brief summary for previews (optional)">{{ isset($article) ? $article->short_description : old('short_description') }}</textarea>
						</div>
					
					</div>
				</div>
				
				{{-- SEO Meta Section --}}
				<div class="card mb-4">
					<div class="card-header">
						<h5 class="mb-0">SEO Settings</h5>
					</div>
					<div class="card-body">
						<!-- Meta Description -->
						<div class="mb-3">
							<label for="meta_description" class="form-label">{{ __('default.Meta Description') }}</label>
							<input type="text" class="form-control" id="meta_description" name="meta_description" value="{{ isset($article) ? $article->meta_description : old('meta_description') }}" placeholder="Description for search engines (~160 chars)">
						</div>
						{{-- Add other SEO fields if needed (e.g., Meta Keywords - though less important now) --}}
					</div>
				</div>
				
				{{-- AI Chat Section --}}
				@if(isset($chatSession)) {{-- Only show if editing and chat session exists --}}
				<div class="card mb-4">
					<div class="card-header">
						<h5 class="mb-0">{{__('default.Chat with AI')}}</h5>
					</div>
					<div class="card-body">
						<div class="chat-window mb-3" id="chatWindow" style="border: 1px solid var(--bs-border-color); height: 400px; overflow-y: scroll; padding: 10px; border-radius: var(--bs-border-radius);">
							<!-- Chat messages will be appended here -->
							<div class="text-center text-muted p-3">Loading chat history...</div>
						</div>
						<div class="mb-2">
							<textarea class="form-control" id="userPrompt" rows="3" placeholder="Enter your prompt here... (Shift+Enter for new line)"></textarea>
						</div>
						<div class="d-flex justify-content-between align-items-center">
							<div class="flex-grow-1 me-3">
								<select id="llmSelect" class="form-select form-select-sm">
									<option value="">{{__('default.Select an AI Engine')}}</option>
									{{-- Loaded via JS --}}
								</select>
								<div id="modelInfo" class="mt-2" style="display: none;">
									<div class="border rounded p-2 bg-body-tertiary small">
										<div id="modelDescription" class="mb-1"></div>
										<div id="modelPricing" class="text-muted"></div>
									</div>
								</div>
							</div>
							<button type="button" class="btn btn-primary" id="sendPromptBtn">
								<span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
								{{ __('default.Send Prompt') }}
							</button>
						</div>
					</div>
				</div>
				@endif
			
			</div>
			
			{{-- Sidebar Column --}}
			<div class="col-lg-4">
				{{-- Publish Card --}}
				<div class="card mb-4">
					<div class="card-header">
						<h5 class="mb-0">{{ __('default.Publish') }}</h5>
					</div>
					<div class="card-body">
						<!-- Language Selection -->
						<div class="mb-3">
							<label for="language_id" class="form-label">{{ __('default.Language') }} <span class="text-danger">*</span></label>
							<select class="form-select" id="language_id" name="language_id" required>
								<option value="" disabled {{ !isset($article) ? 'selected' : '' }}>{{ __('default.Select Language') }}</option>
								@foreach($languages as $language)
									@if($language->active)
										<option value="{{ $language->id }}" {{ (isset($article) && $article->language_id == $language->id) || old('language_id') == $language->id ? 'selected' : '' }}>
											{{ $language->language_name }}
										</option>
									@endif
								@endforeach
							</select>
						</div>
						<!-- Publication Status -->
						<div class="mb-3">
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ (isset($article) && $article->is_published) || old('is_published') ? 'checked' : '' }}>
								<label class="form-check-label" for="is_published">
									{{ __('default.Publish Article') }}
								</label>
							</div>
						</div>
						<!-- Posted At -->
						<div class="mb-3">
							<label for="posted_at" class="form-label">{{ __('default.Publication Date') }}</label>
							<input type="datetime-local" class="form-control" id="posted_at" name="posted_at" value="{{ isset($article) ? $article->posted_at->format('Y-m-d\TH:i') : old('posted_at', now()->format('Y-m-d\TH:i')) }}">
							<small class="form-text text-muted">Leave blank to publish immediately when checked.</small>
						</div>
					</div>
					<div class="card-footer text-end">
						<a href="{{ route('articles.index') }}" class="btn btn-secondary me-2">Cancel</a>
						<button type="submit" class="btn btn-primary">
							{{ isset($article) ? __('default.Update Article') : __('default.Create Article') }}
						</button>
					</div>
				</div>
				
				{{-- Categories Card --}}
				<div class="card mb-4">
					<div class="card-header">
						<h5 class="mb-0">{{ __('default.Categories') }}</h5>
					</div>
					<div class="card-body" style="max-height: 300px; overflow-y: auto;">
						@if($categories->isEmpty())
							<p class="text-muted small">No categories available. <a href="{{ route('settings.categories') }}">Add categories</a> in settings.</p>
						@else
							@foreach($categories as $category)
								<div class="form-check mb-2">
									<input class="form-check-input category-checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}"
										{{ (isset($article) && $article->categories->contains($category->id)) || (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'checked' : '' }}>
									<label class="form-check-label" for="category{{ $category->id }}">
										{{ $category->category_name }} <small class="text-muted">({{ $category->language->language_name }})</small>
									</label>
								</div>
							@endforeach
						@endif
					</div>
				</div>
				
				{{-- Featured Image Card --}}
				<div class="card mb-4">
					<div class="card-header">
						<h5 class="mb-0">{{ __('default.Featured Image') }}</h5>
					</div>
					<div class="card-body text-center">
						<input type="hidden" name="featured_image_id" id="featured_image_id" value="{{ isset($article) ? $article->featured_image_id : old('featured_image_id', '') }}">
						<div id="selectedImagePreview" class="mb-3">
							@if(isset($article) && $article->featuredImage)
								<img src="{{ $article->featuredImage->getSmallUrl() }}" alt="Featured Image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
							@else
								{{-- Placeholder --}}
								<div class="border rounded bg-body-tertiary d-flex align-items-center justify-content-center" style="height: 150px;">
									<i class="bi bi-image text-muted display-6"></i>
								</div>
							@endif
						</div>
						<button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#imageModal">
							{{ __('default.Select Featured Image') }}
						</button>
						<button type="button" class="btn btn-sm btn-outline-secondary" id="removeFeaturedImage" style="{{ (isset($article) && $article->featuredImage) ? '' : 'display: none;' }}">
							Remove
						</button>
					</div>
				</div>
			
			</div>
		</div>
	</form>
	
	<!-- Image Selection Modal -->
	<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-scrollable"> {{-- Scrollable --}}
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="imageModalLabel">{{ __('default.Select Featured Image') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					{{-- Add search/filter for modal images later if needed --}}
					<div class="row g-3" id="modalImageGrid">
						<!-- Images will be loaded here -->
						<div class="col-12 text-center py-5">
							<span class="spinner-border text-primary" role="status"></span>
							<p class="mt-2">Loading images...</p>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-center">
					<!-- Pagination container -->
					<div id="modalPaginationContainer">
						<!-- Pagination will be added here -->
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<style>
      /* Modal Image Grid Styles */
      #modalImageGrid .card {
          cursor: pointer;
          transition: all 0.2s ease-in-out;
          border-width: 2px;
          border-color: transparent;
      }
      #modalImageGrid .card:hover {
          transform: translateY(-3px);
          box-shadow: var(--bs-box-shadow);
      }
      #modalImageGrid .card.selected {
          border-color: var(--bs-primary);
          box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.5);
      }
      #modalImageGrid .card-img-top {
          aspect-ratio: 1 / 1;
          object-fit: cover;
      }
      #modalImageGrid .card-body {
          padding: 0.5rem;
          text-align: center;
      }
      #modalImageGrid .card-text {
          font-size: 0.8rem;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          margin-bottom: 0;
      }

      /* Chat Window Styles */
      .chat-window { background-color: var(--bs-tertiary-bg); }
      .chat-message { padding: 8px 12px; border-radius: var(--bs-border-radius); margin-bottom: 10px; max-width: 85%; word-wrap: break-word; }
      .user-message { background-color: var(--bs-primary-bg-subtle); margin-left: auto; border: 1px solid var(--bs-primary-border-subtle); }
      .assistant-message { background-color: var(--bs-secondary-bg-subtle); margin-right: auto; border: 1px solid var(--bs-secondary-border-subtle); }
      .error-message { background-color: var(--bs-danger-bg-subtle); color: var(--bs-danger-text-emphasis); border: 1px solid var(--bs-danger-border-subtle); }
      .message-content { margin-top: 5px; white-space: pre-wrap; font-size: 0.95rem; }
      .message-content p:last-child { margin-bottom: 0; } /* Remove extra space from markdown */
      .message-meta { font-size: 0.75rem; color: var(--bs-secondary-color); margin-top: 4px; }
      #chatWindow::-webkit-scrollbar { width: 6px; }
      #chatWindow::-webkit-scrollbar-track { background: transparent; }
      #chatWindow::-webkit-scrollbar-thumb { background: var(--bs-secondary-bg); border-radius: 3px; }
      #chatWindow::-webkit-scrollbar-thumb:hover { background: var(--bs-secondary-color); }
	</style>
	
	<script>
		// --- Global Vars ---
		let savedLlm = localStorage.getItem('chat-llm') || 'anthropic/claude-3-haiku:beta'; // Default chat LLM
		let sessionId = '{{ isset($chatSession) ? $chatSession->session_id : "" }}';
		let modalCurrentPage = 1;
		let selectedImageId = $('#featured_image_id').val(); // Track selected image in modal
		
		// --- LLM & Chat Functions ---
		function getLLMsData() { return $.getJSON('/check-llms-json'); } // Simplified
		
		function linkify(text) {
			if (!text) return '';
			const urlRegex = /(https?:\/\/[^\s]+)/g;
			// Basic markdown link format: [text](url) - needs more robust parsing for real use
			const mdLinkRegex = /\[([^\]]+)\]\((https?:\/\/[^\s\)]+)\)/g;
			
			let html = escapeHtml(text)
				.replace(urlRegex, '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>')
				.replace(mdLinkRegex, '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>');
			return html;
		}
		
		function escapeHtml(unsafe) {
			if (!unsafe) return '';
			return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
		}
		
		function sendMessage() {
			const userPrompt = $('#userPrompt').val().trim();
			const llm = $('#llmSelect').val();
			const sendButton = $('#sendPromptBtn');
			const spinner = sendButton.find('.spinner-border');
			
			if (!userPrompt || !llm || !sessionId) {
				showNotification('Please enter a prompt and select an AI engine.', 'warning');
				return;
			}
			
			$('#userPrompt').val(''); // Clear input
			appendMessage('user', userPrompt);
			spinner.removeClass('d-none');
			sendButton.prop('disabled', true).contents().last().replaceWith(" Sending...");
			
			// Get current article content for context
			const articleContent = {
				title: $('#title').val(),
				subtitle: $('#subtitle').val(),
				body: $('#body').val(), // Maybe limit length?
				categories: $('.category-checkbox:checked').map(function () { return $(this).next('label').text().trim(); }).get() // Send category names
			};
			
			$.ajax({
				url: '{{ route('send-llm-prompt') }}',
				method: 'POST',
				data: {
					user_prompt: userPrompt,
					session_id: sessionId,
					llm: llm,
					context: JSON.stringify(articleContent), // Send article content as context
					_token: $('meta[name="csrf-token"]').attr('content')
				},
				dataType: 'json',
				success: function (response) {
					if (response.success && response.result) {
						appendMessage('assistant', response.result.content, {
							promptTokens: response.result.prompt_tokens,
							completionTokens: response.result.completion_tokens
						});
					} else {
						appendMessage('error', 'Error: ' + (response.message || 'Unknown error from server'));
					}
				},
				error: function (xhr) {
					appendMessage('error', 'Error: Unable to get response from server. ' + (xhr.responseJSON?.message || ''));
				},
				complete: function () {
					spinner.addClass('d-none');
					sendButton.prop('disabled', false).contents().last().replaceWith(" {{ __('default.Send Prompt') }}");
				}
			});
		}
		
		function appendMessage(role, content, tokens = null) {
			const chatWindow = $('#chatWindow');
			let messageHtml = `<div class="chat-message ${role}-message">`;
			messageHtml += `<strong class="text-capitalize d-block mb-1">${role === 'assistant' ? 'AI Assistant' : 'You'}:</strong> `;
			// Basic markdown rendering (replace with a library like Showdown or Marked.js for full support)
			let renderedContent = linkify(content).replace(/\n/g, '<br>'); // Simple newline to <br>
			messageHtml += `<div class="message-content">${renderedContent}</div>`;
			
			if (tokens && role === 'assistant') {
				messageHtml += `<div class="message-meta">Tokens: ${tokens.promptTokens || 0}/${tokens.completionTokens || 0}</div>`;
			}
			messageHtml += '</div>';
			
			chatWindow.append(messageHtml);
			// Scroll to bottom
			chatWindow.scrollTop(chatWindow[0].scrollHeight);
		}
		
		function loadChatMessages(sessionId) {
			const chatWindow = $('#chatWindow');
			chatWindow.html('<div class="text-center text-muted p-3">Loading chat history...</div>'); // Show loading state
			
			$.ajax({
				url: `/chat/messages/${sessionId}`, // Ensure this route exists
				type: 'GET',
				dataType: 'json',
				success: function (response) {
					chatWindow.empty(); // Clear loading/previous messages
					if (response && response.length > 0) {
						response.forEach(message => {
							appendMessage(message.role, message.message, {
								promptTokens: message.prompt_tokens,
								completionTokens: message.completion_tokens
							});
						});
					} else {
						chatWindow.html('<div class="text-center text-muted p-3">No chat history found. Start chatting!</div>');
					}
				},
				error: function(xhr) {
					console.error("Error loading chat messages:", xhr);
					chatWindow.html('<div class="text-center text-danger p-3">Could not load chat history.</div>');
				}
			});
		}
		
		function populateLLMSelector(llmsData) {
			const llmSelect = $('#llmSelect');
			llmSelect.empty().append($('<option>', { value: "", text: "{{__('default.Select an AI Engine')}}" }));
			
			if (!llmsData || !Array.isArray(llmsData)) {
				console.error('Invalid LLMs data received:', llmsData); return;
			}
			
			llmsData.forEach(function (model) {
				let promptPricePerMillion = ((model.pricing?.prompt || 0) * 1000000).toFixed(2);
				let completionPricePerMillion = ((model.pricing?.completion || 0) * 1000000).toFixed(2);
				llmSelect.append($('<option>', {
					value: model.id,
					text: `${model.name} ($${promptPricePerMillion}/${completionPricePerMillion})`,
					'data-description': model.description || '',
					'data-prompt-price': model.pricing?.prompt || 0,
					'data-completion-price': model.pricing?.completion || 0,
				}));
			});
			
			if (savedLlm && llmSelect.find(`option[value="${savedLlm}"]`).length > 0) {
				llmSelect.val(savedLlm);
			} else if (llmSelect.find('option').length > 1) {
				llmSelect.prop('selectedIndex', 1);
				savedLlm = llmSelect.val();
				localStorage.setItem('chat-llm', savedLlm);
			}
			updateLLMInfo(); // Update display based on selection
		}
		
		function updateLLMInfo() {
			const selectedOption = $('#llmSelect').find('option:selected');
			const description = selectedOption.data('description');
			const promptPrice = selectedOption.data('prompt-price');
			const completionPrice = selectedOption.data('completion-price');
			const modelInfoDiv = $('#modelInfo');
			
			if ($('#llmSelect').val()) {
				$('#modelDescription').html(linkify(description || 'No description.'));
				const promptPricePerMillion = (promptPrice * 1000000).toFixed(2);
				const completionPricePerMillion = (completionPrice * 1000000).toFixed(2);
				$('#modelPricing').html(`<strong>Pricing (per M tokens):</strong> Prompt: $${promptPricePerMillion} / Completion: $${completionPricePerMillion}`);
				modelInfoDiv.show();
			} else {
				modelInfoDiv.hide();
			}
		}
		
		// --- Image Modal Functions ---
		function loadModalImages(page = 1) {
			modalCurrentPage = page;
			const grid = $('#modalImageGrid');
			const paginationContainer = $('#modalPaginationContainer');
			grid.html('<div class="col-12 text-center py-5"><span class="spinner-border text-primary"></span><p class="mt-2">Loading images...</p></div>');
			paginationContainer.empty();
			
			$.get('/upload-images', { page: page, perPage: 12 }, function (response) { // Request more images for modal
				grid.empty();
				if (response.images && response.images.data && response.images.data.length > 0) {
					response.images.data.forEach(image => {
						grid.append(createModalImageCard(image));
					});
					updateModalPagination(response.pagination);
					// Highlight already selected image
					if (selectedImageId) {
						$(`#modalImageGrid .card[data-image-id="${selectedImageId}"]`).addClass('selected');
					}
				} else {
					grid.html('<div class="col-12 text-center py-5"><p class="text-muted">No images found in library.</p></div>');
				}
			}).fail(function() {
				grid.html('<div class="col-12 text-center py-5 text-danger"><p>Failed to load images.</p></div>');
			});
		}
		
		function createModalImageCard(image) {
			let image_url = '';
			let image_alt = '';
			
			if (image.image_type === 'upload') {
				image_url = `/storage/upload-images/small/${image.image_small_filename}`;
				image_alt = image.image_alt || 'Uploaded Image';
			} else { // generated
				image_url = `/storage/ai-images/small/${image.image_small_filename}`;
				image_alt = image.user_prompt || 'AI Generated Image';
			}
			
			return `
        <div class="col-6 col-md-4 col-lg-3 mb-3">
            <div class="card h-100 modal-image-card" data-image-id="${image.id}" data-image-url="${image_url}" data-image-alt="${escapeHtml(image_alt)}">
                <img src="${image_url}" class="card-img-top" alt="${escapeHtml(image_alt)}">
                <div class="card-body">
                    <p class="card-text" title="${escapeHtml(image_alt)}">${escapeHtml(image_alt)}</p>
                </div>
            </div>
        </div>`;
		}
		
		function updateModalPagination(pagination) {
			const container = $('#modalPaginationContainer');
			container.empty();
			if (!pagination || pagination.last_page <= 1) return;
			
			const paginationHtml = `
        <nav aria-label="Image selection navigation">
            <ul class="pagination pagination-sm justify-content-center">
                <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link modal-page-link" href="#" data-page="${pagination.current_page - 1}">&laquo;</a>
                </li>
                ${generateModalPaginationItems(pagination)}
                <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                    <a class="page-link modal-page-link" href="#" data-page="${pagination.current_page + 1}">&raquo;</a>
                </li>
            </ul>
        </nav>`;
			container.html(paginationHtml);
		}
		
		function generateModalPaginationItems(pagination) {
			// Simplified pagination for modal - show fewer pages
			let items = '';
			const maxPagesToShow = 5;
			let startPage = Math.max(1, pagination.current_page - Math.floor(maxPagesToShow / 2));
			let endPage = Math.min(pagination.last_page, startPage + maxPagesToShow - 1);
			if (endPage === pagination.last_page) startPage = Math.max(1, endPage - maxPagesToShow + 1);
			
			if (startPage > 1) items += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
			for (let i = startPage; i <= endPage; i++) {
				items += `<li class="page-item ${pagination.current_page === i ? 'active' : ''}"><a class="page-link modal-page-link" href="#" data-page="${i}">${i}</a></li>`;
			}
			if (endPage < pagination.last_page) items += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
			return items;
		}
		
		
		// --- Document Ready ---
		$(document).ready(function () {
			// Load LLMs for Chat
			if(sessionId) { // Only load if chat is enabled (editing)
				getLLMsData()
					.done(populateLLMSelector)
					.fail(function() { console.error('Failed to load LLMs for chat.'); });
				
				// Load initial chat messages
				loadChatMessages(sessionId);
				
				// LLM Selector Change (Chat)
				$('#llmSelect').on('change', function () {
					savedLlm = $(this).val();
					localStorage.setItem('chat-llm', savedLlm);
					updateLLMInfo();
				});
				
				// Send Message Handler (Chat)
				$('#sendPromptBtn').on('click', sendMessage);
				$('#userPrompt').on('keydown', function (e) {
					if (e.key === 'Enter' && !e.shiftKey) {
						e.preventDefault();
						sendMessage();
					}
				});
			}
			
			// Load images when modal is shown
			$('#imageModal').on('show.bs.modal', function () {
				loadModalImages(1); // Start on page 1
			});
			
			// Handle modal pagination clicks
			$(document).on('click', '.modal-page-link', function (e) {
				e.preventDefault();
				const page = $(this).data('page');
				if (page) {
					loadModalImages(page);
				}
			});
			
			// Handle image selection in modal
			$(document).on('click', '.modal-image-card', function () {
				const card = $(this);
				const imageId = card.data('image-id');
				const imageUrl = card.data('image-url');
				const imageAlt = card.data('image-alt');
				
				// Update hidden input and preview
				$('#featured_image_id').val(imageId);
				$('#selectedImagePreview').html(`<img src="${imageUrl}" alt="${imageAlt}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">`);
				$('#removeFeaturedImage').show(); // Show remove button
				
				// Update selection visual in modal
				$('#modalImageGrid .card').removeClass('selected');
				card.addClass('selected');
				selectedImageId = imageId; // Update tracked ID
				
				// Close the modal
				$('#imageModal').modal('hide');
			});
			
			// Handle remove featured image
			$('#removeFeaturedImage').on('click', function() {
				$('#featured_image_id').val('');
				$('#selectedImagePreview').html(`
            <div class="border rounded bg-body-tertiary d-flex align-items-center justify-content-center" style="height: 150px;">
                <i class="bi bi-image text-muted display-6"></i>
            </div>`);
				$(this).hide();
				selectedImageId = null;
				$('#modalImageGrid .card').removeClass('selected'); // Clear selection in modal too
			});
			
		});
	</script>
@endpush
