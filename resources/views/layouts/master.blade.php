@section('header', "")
@section('sub-header', "")

<html>

<head>
	@include('includes.head')
</head>

<body>
	@include('includes.initials')

	@include('includes.header-master')

	<!-- Section Container -->
	<section class="master-section-container text-center">
		<div class="titlebar">
		@if (isset($thisserver))
			<a href="{{ instance('Config')::get('app.root_rel') }}/server/server:{{ $thisserver->id }}" class="title">
				<h1>@yield('header')</h1>
			</a>
		@else
			<h1>@yield('header')</h1>
		@endif
			<div class="sub-titlebar">
				<i>@yield('sub-header')</i>
			</div>
		</div>
		<div class="toolbar">
			@if (isset($thisserver))
				<a href="#"><i class="fa fa-bell" aria-hidden="true"></i></a>
				<a href="settings/server:{{ $thisserver->id }}"><i class="fa fa-cogs" aria-hidden="true"></i></a>
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