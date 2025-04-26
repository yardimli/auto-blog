@extends('layouts.app')
@section('title', $user->company_name . ' - ' . ($pageSettings->title ?? 'Help Page'))

@section('title', 'Help')

@section('content')
	
	<!-- **************** MAIN CONTENT START **************** -->
	<main>
		
		<!-- Container START -->
		<div class="container">
			<!-- Main content START -->
			
			<!-- Help search START -->
			<div class="row align-items-center pt-5 pb-5 pb-lg-3">
				<div class="col-md-3">
					@include('layouts.svg3-image')
				</div>
				<!-- Card START -->
				<div class="col-md-6 text-center">
					<!-- Title -->
					<h1>Hi Cer, we're here to help.</h1>
					<p class="mb-4">Search here to get answers to your questions.</p>
				</div>
				<div class="col-md-3">
					@include('layouts.svg4-image')
				</div>
			</div>
				
			<!-- Article Single  -->
			<div class="row">
				<div class="col-12 vstack gap-4">
					
					<div class="card border p-sm-4">
						<!-- Article title -->
						<div class="card-header border-0 py-0">
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb breadcrumb-dots mb-2">
									<li class="breadcrumb-item"><a href="/"><i class="bi bi-house me-1"></i> Home</a></li>
									<li class="breadcrumb-item"><a href="/help"><i class="bi bi-info-circle me-1"></i> Help</a></li>
									<li class="breadcrumb-item active">{{$topic}}</li>
								</ol>
							</nav>
							<h2>{{$topic}}</h2>
							<!-- Update and author -->
						</div>
						<!-- Article Info -->

						{{-- Rest of the page content --}}
						<div class="card-body">
							@if (isset($helpArticles[$topic]))
								@foreach($helpArticles[$topic] as $article)
									<h3 class="mt-1" style="font-size: 24px;">{{$article['title']}}</h3>
									<div id="article-{{$article['id']}}" class="editormd">
										<textarea name="body" style="display:none;">
{{$article['body']}}
										</textarea>
									</div>
								@endforeach
							@endif
						</div>
					
					</div>
				</div>
			</div>
			<!-- Article Single  -->
		</div> <!-- Row END -->
		<!-- Container END -->
	
	</main>
	
	<!-- **************** MAIN CONTENT END **************** -->
	
	@include('layouts.footer')

@endsection

<script src="/js/jquery-3.7.0.min.js"></script>
<script src="/editormd/editormd.min.js"></script>
<script src="/editormd/languages/en.js"></script>
<script src="/editormd/lib/marked.min.js"></script>
<script src="/editormd/lib/prettify.min.js"></script>

<link rel="stylesheet" href="/editormd/css/editormd.css" />

<style>
	.editormd-preview-container, .editormd-html-preview {
		padding: 10px;
		border: 0px;
	}
</style>

@push('scripts')

	<script>
		var current_page = 'help.details';
		$(document).ready(function () {
			$('.editormd').each((index, editor) => {
				editormd.markdownToHTML(editor.id, {
					// markdown : "[TOC]\n### Hello world!\n## Heading 2", // Also, you can dynamic set Markdown text
					// htmlDecode : true,  // Enable / disable HTML tag encode.
					// htmlDecode : "style,script,iframe",  // Note: If enabled, you should filter some dangerous HTML tags for website security.
				});
			})
		});

	</script>

@endpush
