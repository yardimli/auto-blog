@extends('user.pages.layout')
@section('title', $user->company_name . ' - ' . ($pageSettings->title ?? 'Change Logs'))

@section('user-content')
	<div class="container">
		<h2>{{ $pageSettings->title ?? 'Change Logs' }}</h2>
		@if($pageSettings && $pageSettings->description)
			<div class="page-description mb-4">
				{{ $pageSettings->description ?? '' }}
			</div>
		@endif
		{{-- Rest of the page content --}}
		@foreach($logsGroupByDate as $date => $logs)
		<div class="row">
			<div class="col-2" style="width: 14%">
				<span style="color: #6e6e6e; font-size: 14px; font-weight: 500;">{{$date}}</span>
			</div>
			<div class="col-10" style="border-left: 1px solid #d0d0d0;">
			@foreach($logs as $key => $log)
				<div class="row">
					<div class="col-12" style="padding-left: 20px;">
						<h5 style="display: inline-block;">{{ $log->released_at->format('F jS, Y') }}</h5>
						<span style="color: #6e6e6e; font-size: 14px; font-weight: 500;">{{$log->released_at->format('H:i')}}</span>
						<h5 style="font-size: 24px;">{{$log->title}}</h5>
						<div id="changelog-{{$log->id}}" class="editormd">
							<textarea name="body" style="display:none;">
{{ $log->body }}
							</textarea>
						</div>
					</div>
				</div>
			@endforeach
			</div>
		</div>
		@endforeach
	</div>
@endsection

<script src="/js/jquery-3.7.0.min.js"></script>
<script src="/editormd/editormd.min.js"></script>
<script src="/editormd/languages/en.js"></script>
<script src="/editormd/lib/marked.min.js"></script>
<script src="/editormd/lib/prettify.min.js"></script>

<link rel="stylesheet" href="/editormd/css/editormd.css" />

@push('scripts')

	<script>

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
