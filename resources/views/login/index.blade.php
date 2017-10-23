@extends('layouts.plain')

@section('title', 'Login')

@section('sidebar')
@endsection

@section('content')
	<div class="login-wrapper jumbotron position-middle col-xs-11 col-sm-8 col-lg-5 col-lg-login">
		<div class="login-form-wrapper col-xs-12 col-sm-6">
			<h2 class="gc-text-center">{{ lang('login.index.title_normal') }}</h2>
			<p class="gc-text-center">{{ $variable_name }}</p>
			<form class="form-horizontal" action="_request/login-form" method="post">
				<div class="form-group">
					<label class="control-label col-md-3" for="email">{{ lang('login.index.norm_inp_01') }}:</label>
					<div class="col-md-9">
						<input type="email" class="form-control __input-dark" id="email" placeholder="{{ lang('login.index.norm_inp_01_ph') }}" name="email" tabindex="1" autofocus>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="pwd">{{ lang('login.index.norm_inp_02') }}:</label>
					<div class="col-md-9">          
						<input type="password" class="form-control __input-dark" id="pwd" placeholder="{{ lang('login.index.norm_inp_02_ph') }}" name="pwd" tabindex="2">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-3 col-md-9">
						<label class="checkbox" for="checkbox1">
							<input type="checkbox" name="remember" value="" id="checkbox1" data-toggle="checkbox" tabindex="3">
							{{ lang('login.index.norm_inp_03') }}
						</label>
						<div class="login-supplement-wrapper">
							<a href="#" tabindex="5">{{ lang('login.index.norm_link_01') }}</a><br>
							{{ lang('login.index.norm_link_02_desc_01') }} <a href="#" tabindex="6">{{ lang('login.index.norm_link_02') }}</a>{{ lang('login.index.norm_link_02_desc_02') }}
						</div>
						<script>
							$(':checkbox').radiocheck();
						</script>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-3 col-md-9">
						<button type="submit" class="btn btn-primary gc-float-right" tabindex="4">{{ lang('login.index.norm_inp_sub') }}</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-xs-12 col-sm-6 fast-login-wrapper">
			<h2 class="gc-text-center">{{ lang('login.index.title_quick') }}</h2>
			<p class="gc-text-center">Available soon!</p>
			<form class="form-horizontal" action="#" method="post">
				<div class="form-group">
					<label class="col-lg-12" for="nickname">{{ lang('login.index.quick_inp_01') }}:</label>
					<div class="col-lg-12">
						<input type="text" class="form-control" id="nickname" placeholder="{{ lang('login.index.quick_inp_01_ph') }}" name="nickname">
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-12">
						<button type="submit" class="btn btn-primary gc-float-right">{{ lang('login.index.quick_inp_sub') }}</button>
					</div>
				</div>
			</form>
		</div>
		<div class="gc-cleaner"></div>
	</div>
@endsection