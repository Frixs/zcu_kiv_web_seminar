@extends('layouts.plain-form')

@section('title', lang('register.index.title'))

@section('sidebar')
@endsection

@section('content')
	<div class="register-page">
		<div class="form-wrapper col-xs-12">
			<div class="head-spacer"></div>
			<h2 class="text-center">{{ lang('register.index.title') }}</h2>
			<form class="form-horizontal" action="__request/register" method="post">
				<div class="form-feedback __success">{{ instance('Request')->messageSuccess() }} @if (instance('Request')->messageSuccess()) {{ lang('register.index.success_text_pre') }} <a href="login">{{ lang('register.index.success_text_link') }}</a>{{ lang('register.index.success_text_post') }} @endif</div>
				<div class="form-feedback __error">{{ instance('Request')->messageError() }}</div>
				{{-- USERNAME --}}
				<div class="form-group @if (instance('Request')->getInputError('username')) has-error @endif">
					<label class="col-xs-12" for="username">{{ lang('register.index.inp_01') }}:</label>
					<div class="col-xs-12">
						<input type="text" name="username" value="{{ instance('Request')->getInput('username') }}" class="form-control __input-dark" id="username" placeholder="{{ lang('register.index.inp_01_ph') }}" maxlength="20" tabindex="2" autocomplete="off" autofocus>
						<div class="form-feedback">@if (instance('Request')->getInputError('username')) {{ instance('Request')->getInputError('username') }} @endif</div>
					</div>
				</div>
				{{-- EMAIL --}}
				<div class="form-group @if (instance('Request')->getInputError('email')) has-error @endif">
					<label class="col-xs-12" for="email">{{ lang('register.index.inp_02') }}:</label>
					<div class="col-xs-12">
						<input type="email" name="email" value="{{ instance('Request')->getInput('email') }}" class="form-control __input-dark" id="email" placeholder="{{ lang('register.index.inp_02_ph') }}" maxlength="150" tabindex="2" autocomplete="off">
						<div class="form-feedback">@if (instance('Request')->getInputError('email')) {{ instance('Request')->getInputError('email') }} @endif</div>
					</div>
				</div>
				{{-- PASSWORD --}}
				<div class="form-group @if (instance('Request')->getInputError('password')) has-error @endif">
					<label class="col-xs-12" for="pwd">{{ lang('register.index.inp_03') }}:</label>
					<div class="col-xs-12">
						<input type="password" name="password" value="" class="form-control __input-dark" id="pwd" placeholder="{{ lang('register.index.inp_03_ph') }}" maxlength="64" tabindex="3" autocomplete="off">
						<div class="form-feedback">@if (instance('Request')->getInputError('password')) {{ instance('Request')->getInputError('password') }} @endif</div>
					</div>
				</div>
				{{-- PASSWORD REPEAT --}}
				<div class="form-group @if (instance('Request')->getInputError('password-check')) has-error @endif">
					<div class="col-xs-12">
						<input type="password" name="password-check" value="" class="form-control __input-dark" id="pwd" placeholder="{{ lang('register.index.inp_04_ph') }}" maxlength="64" tabindex="4" autocomplete="off">
						<div class="form-feedback">@if (instance('Request')->getInputError('password-check')) {{ instance('Request')->getInputError('password-check') }} @endif</div>
					</div>
				</div>
				{{-- TERMS --}}
				<div class="form-group @if (instance('Request')->getInputError('terms')) has-error @endif">
					<div class="col-xs-12">
						<label class="checkbox" for="checkbox1">
							<input type="checkbox" name="terms" value="terms" id="checkbox1" data-toggle="checkbox" tabindex="5">
							{{ lang('register.index.inp_05') }} <a href="#">{{ lang('register.index.inp_05b') }}</a>.
						</label>
						<div class="form-feedback">@if (instance('Request')->getInputError('terms')) {{ instance('Request')->getInputError('terms') }} @endif</div>
						<script>
							$(':checkbox').radiocheck();
						</script>
					</div>
				</div>
				{{-- CAPTCHA --}}
				<div class="form-group @if (instance('Request')->getInputError('g-recaptcha-response')) has-error @endif">
					<div class="col-xs-12" style="height: 62px;">
						<div class="g-recaptcha" data-sitekey="{{ instance('Config')::get('captcha.g_recaptcha.site_key') }}" data-theme="dark" tabindex="6" style="margin: 0 0 0 auto;"></div>
					</div>
					<div class="col-xs-12">
						<div class="form-feedback text-right">@if (instance('Request')->getInputError('g-recaptcha-response')) {{ instance('Request')->getInputError('g-recaptcha-response') }} @endif</div>
					</div>
				</div>
				{{-- TOKEN --}}
				<input type="hidden" name="{{ instance('Token')::createTokenInput() }}" value="{{ instance('Token')::get() }}" />
				{{-- SUBMIT --}}
				<div class="form-group">
					<div class="col-md-offset-3 col-md-9">
						<button type="submit" class="btn btn-primary gc-float-right" tabindex="7">{{ lang('register.index.inp_sub') }}</button>
					</div>
				</div>
			</form>
		</div>
		<div class="gc-cleaner"></div>
	</div>
@endsection