@extends('layouts.plain-form') @section('title', lang('server.create.title')) @section('content')

<div class="server-create-page">
	<div class="form-wrapper col-xs-12">
		<div class="head-spacer"></div>
		<h2 class="text-center">{{ lang('server.create.title') }}</h2>
		<form class="form-horizontal" action="_request/server-create" method="post" enctype="multipart/form-data">
			<div class="form-feedback __success">{{ instance('Request')->messageSuccess() }}</div>
			<div class="form-feedback __error">{{ instance('Request')->messageError() }}</div>
			{{-- NAME --}}
			<div class="form-group @if (instance('Request')->getInputError('name')) has-error @endif">
				<label class="col-xs-12" for="name">{{ lang('server.create.inp_01') }}:</label>
				<div class="col-xs-12">
					<input type="text" name="name" value="{{ instance('Request')->getInput('name') }}" class="form-control __input-dark" id="name"
					 placeholder="{{ lang('server.create.inp_01_ph') }}" maxlength="20" tabindex="1" autocomplete="off" autofocus>
					<div class="form-feedback">@if (instance('Request')->getInputError('name')) {{ instance('Request')->getInputError('name') }} @endif</div>
				</div>
			</div>
			{{-- ACCESS-TYPE --}}
			<div class="form-group @if (instance('Request')->getInputError('access-type')) has-error @endif">
				<label class="col-xs-12" for="access-type">{{ lang('server.create.inp_02') }}:</label>
				<div class="col-xs-12">
					<select class="form-control" id="access-type" placeholder="{{ lang('server.create.inp_02_ph') }}" tabindex="2" autocomplete="off">
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
					</select>
					<div class="form-feedback">@if (instance('Request')->getInputError('access-type')) {{ instance('Request')->getInputError('access-type') }} @endif</div>
				</div>
			</div>
			{{-- BACKGROUND-PLACEHOLDER --}}
			<div class="form-group @if (instance('Request')->getInputError('background-placeholder')) has-error @endif">
				<label class="col-xs-12" for="background-placeholder">{{ lang('server.create.inp_03') }}:</label>
				<div class="col-xs-12">
					<input type="file" name="background-placeholder" value="" class="form-control __input-dark" id="background-placeholder" tabindex="3" autocomplete="off" autocomplete="off">
					<div class="form-feedback">@if (instance('Request')->getInputError('background-placeholder')) {{ instance('Request')->getInputError('background-placeholder') }} @endif</div>
				</div>
			</div>
			{{-- CAPTCHA --}}
			<div class="form-group @if (instance('Request')->getInputError('g-recaptcha-response')) has-error @endif">
				<div class="col-xs-12" style="height: 62px;">
					<div class="g-recaptcha" data-sitekey="{{ instance('Config')::get('captcha.g_recaptcha.site_key') }}" data-theme="dark" tabindex="6"
					 style="margin: 0 0 0 auto;"></div>
				</div>
				<div class="col-xs-12">
					<div class="form-feedback text-right">@if (instance('Request')->getInputError('g-recaptcha-response')) {{ instance('Request')->getInputError('g-recaptcha-response')
						}} @endif</div>
				</div>
			</div>
			{{-- TOKEN --}}
			<input type="hidden" name="{{ instance('Token')::createTokenInput() }}" value="{{ instance('Token')::get() }}" /> {{-- SUBMIT --}}
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