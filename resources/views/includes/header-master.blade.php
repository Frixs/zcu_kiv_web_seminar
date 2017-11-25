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
			<a href="{{ instance('Config')::get('app.root_rel') }}/home">
				<img class="logo img-responsive" src="{{ instance('Config')::get('app.root_images_rel') }}/logo/app_logo_color.png" alt="logo"
				 draggable="false">
			</a>
		</div>
		<div class="collapse navbar-collapse" id="master-navbar">
			<ul class="nav navbar-nav navbar-right">

				<li class="dropdown">
					<a href="#" class="dropdown-toggle user-wrapper-toggle" data-toggle="dropdown">
						<span class="fa fa-user-circle-o" aria-hidden="true"></span>
					</a>
					<ul class="dropdown-menu user-wrapper">
						<li>
							<div class="navbar-user">
								<div class="col-lg-4">
									<p class="text-center">
										<span class="glyphicon glyphicon-user icon-size"></span>
									</p>
								</div>
								<div class="col-lg-8 gc-col-nosp user-info">
									<p class="text-left">
										<strong>{{ instance('Auth')::user()->username }}</strong>
									</p>
									<p class="text-left small">{{ instance('Auth')::user()->email }}</p>
								</div>
								<div class="gc-cleaner"></div>
							</div>
						</li>
						<li class="divider"></li>
						<li>
							<p class="navbar-user-session">
								<a href="logout" class="btn btn-danger btn-block">{{ lang('auth.logout') }}</a>
							</p>
						</li>
					</ul>
				</li>

			</ul>
		</div>
	</nav>
</header>