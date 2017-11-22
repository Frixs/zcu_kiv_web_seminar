@extends('layouts.plain-form')

@section('title', lang('server.create.title'))

@section('content')
	<div class="server-create-page">
		<div class="form-wrapper col-xs-12">
			<div class="head-spacer"></div>
			<h2 class="text-center">{{ lang('server.create.title') }}</h2>
			<form class="form-horizontal" action="_request/server-create" method="post">
				<div class="form-feedback __success">{{ instance('Request')->messageSuccess() }}</div>
				<div class="form-feedback __error">{{ instance('Request')->messageError() }}</div>
				{{-- NAME --}}
				<div class="form-group @if (instance('Request')->getInputError('name')) has-error @endif">
					<label class="col-xs-12" for="name">{{ lang('server.create.inp_01') }}:</label>
					<div class="col-xs-12">
						<input type="text" name="name" value="{{ instance('Request')->getInput('name') }}" class="form-control __input-dark" id="name" placeholder="{{ lang('server.create.inp_01_ph') }}" maxlength="20" tabindex="2" autocomplete="off" autofocus>
						<div class="form-feedback">@if (instance('Request')->getInputError('name')) {{ instance('Request')->getInputError('name') }} @endif</div>
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
						<button type="submit" class="btn btn-primary gc-float-right" tabindex="7">{{ lang('server.create.inp_sub') }}</button>
					</div>
				</div>
			</form>
		</div>
		<div class="gc-cleaner"></div>
	</div>
@endsection