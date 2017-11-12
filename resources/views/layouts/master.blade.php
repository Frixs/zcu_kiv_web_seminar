<html>

<head>
	@include('includes.head')
</head>

<body>
	@include('includes.initials')

	@include('includes.header-master')

	<!-- Section Container -->
	<section div class="master-section-container text-center">
		<h1>@yield('header')</h1>
	</section>
	<!-- Content Container -->
	<main class="container">
		@yield('content')
	</main>
</body>

</html>