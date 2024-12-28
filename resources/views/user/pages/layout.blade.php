@extends('layouts.company-app')

@section('content')
	<div class="container mt-4 pt-5">
		
		@yield('user-content')
		
		<div class="row mt-5">
			<div class="col-md-12">
				<hr>
				<nav class="nav justify-content-center">
					<a class="nav-link" href="{{ route('user.terms', $user->username) }}">Terms</a>
					<a class="nav-link" href="{{ route('user.privacy', $user->username) }}">Privacy</a>
					<a class="nav-link" href="{{ route('user.cookie-consent', $user->username) }}">Cookie Policy</a>
				</nav>
			</div>
		</div>
	</div>
@endsection
