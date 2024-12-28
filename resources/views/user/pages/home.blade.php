@extends('user.pages.layout')

@section('user-content')
	<div class="row">
		<div class="col-md-12">
			<h1>{{ $user->company_name }}</h1>
			<p class="lead">{{ $user->company_description }}</p>
		</div>
	</div>
@endsection
