<html>

<head>
	@include('includes.head')
</head>

<body>
	@include('includes.initials')

	@include('includes.header-master')

	<!-- Section Container -->
	<section class="master-section-container text-center">
		<h1>@yield('header')</h1>
		<div class="toolbar">
			@if (isset($thisserver))
				<a href="#"><i class="fa fa-bell" aria-hidden="true"></i></a>
				<a href="#"><i class="fa fa-cogs" aria-hidden="true"></i></a>
			@else
				<i>{{ lang('calls.'. rand(0, count(lang('calls')) - 1)) }}</i>
			@endif
		</div>
	</section>
	<!-- Content Container -->
	<main class="container">
		@yield('content')
	</main>

	@include('includes.footer-master')
</body>

</html>