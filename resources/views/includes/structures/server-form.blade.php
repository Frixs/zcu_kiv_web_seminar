@section('server-form')
@php
	$inpName = instance('Request')->getInput('name');
	$inpAccessType = instance('Request')->getInput('access-type');
	$inpSubmit = lang('server.create.inp_sub');

	if (isset($loading_data)) {
		$inpName = $loading_data->name;
		$inpAccessType = $loading_data->access_type == 0 ? 'public' : ($loading_data->access_type == 1 ? 'protected' : 'private');
		$inpSubmit = lang('server.create.inp_sub_edit');
	}
@endphp

<form class="form-horizontal" action="{{ $form_action }}" method="post" enctype="multipart/form-data">
	<div class="form-feedback __success">{{ instance('Request')->messageSuccess() }} @if (!isset($loading_data) && instance('Request')->messageSuccess()) {{ lang('server.create.success_text_pre') }} <a href="../dashboard">{{ lang('server.create.success_text_link') }}</a>{{ lang('server.create.success_text_post') }} @endif</div>
	<div class="form-feedback __error">{{ instance('Request')->messageError() }}</div>
	{{-- NAME --}}
	<div class="form-group @if (instance('Request')->getInputError('name')) has-error @endif">
		<label class="col-xs-12" for="name">{{ lang('server.create.inp_01') }}:</label>
		<div class="col-xs-12">
			<input type="text" name="name" value="{{ $inpName }}" class="form-control __input-dark" id="name"
			 placeholder="{{ lang('server.create.inp_01_ph') }}" maxlength="50" tabindex="1" autocomplete="off" @if (!isset($loading_data)) autofocus @endif>
			<div class="form-feedback">@if (instance('Request')->getInputError('name')) {{ instance('Request')->getInputError('name') }} @endif</div>
		</div>
	</div>
	{{-- ACCESS-TYPE --}}
	<div class="form-group @if (instance('Request')->getInputError('access-type')) has-error @endif">
		<div class="col-xs-12">
			<label class="radio" for="radio1">
				<input type="radio" name="access-type" value="public" id="radio1" data-toggle="radio" tabindex="2" @if ($inpAccessType && ($inpAccessType == 'protected' || $inpAccessType
				== 'private')) @else checked @endif> {{ lang('server.create.public') }}.
				<div class="info">{{ lang('server.create.public_desc') }}</div>
			</label>
			<label class="radio" for="radio2">
				<input type="radio" name="access-type" value="protected" id="radio2" data-toggle="radio" tabindex="3" @if ($inpAccessType && $inpAccessType == 'protected') checked @endif> {{ lang('server.create.protected')
				}}.
				<div class="info">{{ lang('server.create.protected_desc') }}</div>
			</label>
			<label class="radio" for="radio3">
				<input type="radio" name="access-type" value="private" id="radio3" data-toggle="radio" tabindex="4" @if ($inpAccessType && $inpAccessType == 'private') checked @endif> {{ lang('server.create.private')
				}}.
				<div class="info">{{ lang('server.create.private_desc') }}</div>
			</label>
			<div class="form-feedback">@if (instance('Request')->getInputError('access-type')) {{ instance('Request')->getInputError('access-type') }} @endif</div>
			<script>
				$(':radio').radiocheck();
			</script>
		</div>
	</div>
	{{-- BACKGROUND-PLACEHOLDER --}}
	<div class="form-group @if (instance('Request')->getInputError('background-placeholder')) has-error @endif">
		<label class="col-xs-12" for="background-placeholder">{{ lang('server.create.inp_03') }}:</label>
		<div class="col-xs-12">
			<input type="file" name="background-placeholder" class="form-control __input-dark" id="background-placeholder" tabindex="5"
			 autocomplete="off" autocomplete="off">
			<div class="form-feedback">@if (instance('Request')->getInputError('background-placeholder')) {{ instance('Request')->getInputError('background-placeholder')
				}} @endif</div>
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
	<input type="hidden" name="{{ instance('Token')::createTokenInput() }}" value="{{ instance('Token')::get() }}" />
	@if (isset($loading_data))
		{{-- SERVER ID --}}
		<input type="hidden" name="serverid" value="{{ $loading_data->id }}" />
	@endif
	{{-- SUBMIT --}}
	<div class="form-group gc-no-margin-bottom">
		<div class="col-md-offset-3 col-md-9">
			<button type="submit" class="btn btn-primary gc-float-right" tabindex="7">{{ $inpSubmit }}</button>
		</div>
	</div>
</form>
@endsection