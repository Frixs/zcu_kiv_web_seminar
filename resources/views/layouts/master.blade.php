<html>

<head>
	@include('includes.head')
</head>

<body>
	@include('includes.initials')

	<header class="master-header">
		<!-- Navbar -->
		<nav class="navbar navbar-inverse" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#master-navbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="navbar-logo">
				<a href="home">
					<img class="logo img-responsive" src="{{ instance('Config')::get('app.root_images_rel') }}logo/app_logo_color.png" alt="logo"
					 draggable="false">
				</a>
			</div>
			<div class="collapse navbar-collapse" id="master-navbar">
				<ul class="nav navbar-nav navbar-right">

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<span class="glyphicon glyphicon-user"></span>
							<strong>USERNAME</strong>
							<span class="glyphicon glyphicon-chevron-down"></span>
						</a>
						<ul class="dropdown-menu user-wrapper">
							<li>
								<div class="navbar-user">
									<div class="col-lg-4">
										<p class="text-center">
											<span class="glyphicon glyphicon-user icon-size"></span>
										</p>
									</div>
									<div class="col-lg-8">
										<p class="text-left">
											<strong>TEXT</strong>
										</p>
										<p class="text-left small">email@email.com</p>
										<p class="text-left">
											<a href="#" class="btn btn-primary btn-block btn-sm">XXX</a>
										</p>
									</div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="navbar-login navbar-user-session">
									<div class="col-lg-12">
										<p>
											<a href="#" class="btn btn-danger btn-block">LOGOUT TEXT</a>
										</p>
									</div>
								</div>
							</li>
						</ul>
					</li>

				</ul>
			</div>
		</nav>
	</header>
	<!-- Section Container -->
	<section div class="master-section-container text-center">
		<h1>@yield('header')</h1>
	</section>
	<!-- Content Container -->
	<main class="container">
		@yield('content')
	</main>

	{{--
	<header class="row">
		@include('includes.header')
	</header>

	@section('sidebar') @show

	<div class="container">
		@yield('content')
	</div>

	<footer class="row">
		@include('includes.footer')
	</footer>
	--}}
</body>

</html>