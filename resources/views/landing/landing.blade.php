@extends('layouts.app')

@section('title', 'All-in-One SaaS Infrastructure')

@section('content')
	<style>
    .bg-light {
				background-color: #e8e9ea !important;
		}
    .img-fluid {
		    height: 200px;
		    max-height: 200px;
    }
</style>

	<main>
	<!-- Hero Section -->
	<section class="pt-5 mt-5 pb-4 position-relative bg-light">
		<div class="container">
			<div class="row text-center position-relative z-index-1">
				<div class="col-lg-8 col-12 mx-auto">
					<h1 class="display-5">Elevate Your Company's Online Presence with Contentero</h1>
					<p class="lead">All-in-One Hosted Solutions for Blogs, Support, Roadmaps, User Feedback, and More</p>
					<div class="d-sm-flex justify-content-center">
						<a href="{{ route('register') }}" class="btn btn-primary me-2">Get Started</a>
						<a href="#features" class="btn btn-outline-primary">Learn More</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Blog Platform Section -->
	<section class="py-5">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<img src="/images/blog.png" class="img-fluid" alt="Blog Platform">
				</div>
				<div class="col-lg-6 mt-4 mt-lg-0">
					<h2>Hosted Blog Platform with AI-Powered Content</h2>
					<ul class="list-unstyled">
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Access extensive article archives across topics</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Customize content with AI for your products</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Generate AI images for engaging posts</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Knowledge Base Section -->
	<section class="py-5 bg-light">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6 order-lg-2">
					<img src="/images/help.png" class="img-fluid" alt="Knowledge Base">
				</div>
				<div class="col-lg-6 order-lg-1  mt-4 mt-lg-0">
					<h2>Integrated Knowledge Base and FAQ System</h2>
					<ul class="list-unstyled">
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Seamless support content management</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Easy-to-navigate assistance</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Improve customer satisfaction</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Roadmap Section -->
	<section class="py-5">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<img src="/images/roadmap.png" class="img-fluid" alt="Product Roadmap">
				</div>
				<div class="col-lg-6 mt-4 mt-lg-0">
					<h2>Interactive Product Roadmap</h2>
					<ul class="list-unstyled">
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>User-friendly Kanban board</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Track development progress</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Keep users informed of updates</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	
	<!-- User Feedback Section -->
	<section class="py-5 bg-light">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6 order-lg-2">
					<img src="/images/uservoice.png" class="img-fluid" alt="User Feedback">
				</div>
				<div class="col-lg-6 order-lg-1 mt-4 mt-lg-0">
					<h2>User Feedback and Feature Requests</h2>
					<ul class="list-unstyled">
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Collect and vote on feature requests</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Manage visibility and priorities</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Engage with user community</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Changelog Section -->
	<section class="py-5">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<img src="/images/changelog.png" class="img-fluid" alt="Changelog">
				</div>
				<div class="col-lg-6 mt-4 mt-lg-0">
					<h2>Dynamic Changelog Page</h2>
					<ul class="list-unstyled">
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Track version history</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Document release dates</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Maintain transparency</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Legal Pages Section -->
	<section class="py-5 bg-light">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6 order-lg-2">
					<img src="/images/legal.png" class="img-fluid" alt="Legal Pages">
				</div>
				<div class="col-lg-6 order-lg-1 mt-4 mt-lg-0">
					<h2>Ready-Made Legal Pages</h2>
					<ul class="list-unstyled">
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Customizable Terms of Service</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Privacy Policy templates</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>GDPR compliance support</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Cookie Consent Section -->
	<section class="py-5">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<img src="/images/cookie.png" class="img-fluid" alt="Cookie Consent">
				</div>
				<div class="col-lg-6 mt-4 mt-lg-0">
					<h2>Cookie Acceptance System</h2>
					<ul class="list-unstyled">
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Compliant consent banners</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Easy implementation</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Customizable appearance</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Domain Integration Section -->
	<section class="py-5 bg-light">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6 order-lg-2">
					<img src="/images/domain.png" class="img-fluid" alt="Domain Integration">
				</div>
				<div class="col-lg-6 order-lg-1 mt-4 mt-lg-0">
					<h2>Seamless Domain Integration</h2>
					<ul class="list-unstyled">
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Flexible hosting options</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Custom subdomain support</li>
						<li class="mb-3"><i class="bi bi-check-circle-fill text-primary me-2"></i>Simple DNS configuration</li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	
	<!-- CTA Section -->
	<section class="py-5 bg-primary text-white">
		<div class="container text-center">
			<h2>Ready to Transform Your Online Presence?</h2>
			<p class="lead mb-4">Join thousands of companies already using Contentero</p>
			<a href="{{ route('register') }}" class="btn btn-light btn-lg">Get Started Today</a>
		</div>
	</section>
</main>
<!-- **************** MAIN CONTENT END **************** -->
	
	@include('layouts.footer')

@endsection

@push('scripts')
	<!-- Inline JavaScript code -->
	<script>
		var current_page = 'my.landing';
		$(document).ready(function () {
		});
	</script>

@endpush
