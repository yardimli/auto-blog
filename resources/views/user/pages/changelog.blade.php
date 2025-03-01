@extends('user.pages.layout')

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
						<div>
							<github-md>
{{$log->body}}
							</github-md>
						<hr>
						</div>
					</div>
				</div>
			@endforeach
			</div>
		</div>
		@endforeach
	</div>
@endsection

<script src="https://cdn.jsdelivr.net/gh/MarketingPipeline/Markdown-Tag/markdown-tag.js"></script>
