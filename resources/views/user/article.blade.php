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
									<img src="{{ $article->featuredImage->getSmallUrl() }}" alt="Featured Image" class="img-thumbnail" style="max-width: 200px;">
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
							<textarea class="form-control" id="body" name="body" rows="10" required>{{ isset($article) ? $article->body : old('body') }}</textarea>
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
							<textarea class="form-control" id="short_description" name="short_description" rows="3">{{ isset($article) ? $article->short_description : old('short_description') }}</textarea>
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
	</style>
	<script>
		
		// Add this to your existing $(document).ready() function
		function loadModalImages(page = 1) {
			$.get('/upload-images', { page: page }, function(response) {
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
		
		$(document).ready(function() {
			
			// Load images when modal is shown
			$('#imageModal').on('show.bs.modal', function() {
				loadModalImages();
			});
			
			// Handle pagination clicks
			$(document).on('click', '.modal-page-link', function(e) {
				e.preventDefault();
				const page = $(this).data('page');
				loadModalImages(page);
			});
			
			// Handle image selection
			$(document).on('click', '.select-modal-image', function() {
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
