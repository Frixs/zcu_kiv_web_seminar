@section('server-form')
<form class="form-horizontal" action="{{ $form_action }}" method="post" enctype="multipart/form-data">
	<div class="form-feedback __success">{{ instance('Request')->messageSuccess() }}</div>
	<div class="form-feedback __error">{{ instance('Request')->messageError() }}</div>
	{{-- NAME --}}
	<div class="form-group @if (instance('Request')->getInputError('name')) has-error @endif">
		<label class="col-xs-12" for="name">{{ lang('server.create.inp_01') }}:</label>
		<div class="col-xs-12">
			<input type="text" name="name" value="{{ instance('Request')->getInput('name') }}" class="form-control __input-dark" id="name"
			 placeholder="{{ lang('server.create.inp_01_ph') }}" maxlength="50" tabindex="1" autocomplete="off" autofocus>
			<div class="form-feedback">@if (instance('Request')->getInputError('name')) {{ instance('Request')->getInputError('name') }} @endif</div>
		</div>
	</div>
	{{-- ACCESS-TYPE --}}
	<div class="form-group @if (instance('Request')->getInputError('access-type')) has-error @endif">
		<div class="col-xs-12">
			<label class="radio" for="radio1">
				<input type="radio" name="access-type" value="public" id="radio1" data-toggle="radio" tabindex="2" @if (instance(
				 'Request')->getInput('access-type') && (instance( 'Request')->getInput('access-type') == 'radio2' || instance( 'Request')->getInput('access-type')
				== 'radio3')) @else checked @endif> {{ lang('server.create.public') }}.
				<div class="info">{{ lang('server.create.public_desc') }}</div>
			</label>
			<label class="radio" for="radio2">
				<input type="radio" name="access-type" value="protected" id="radio2" data-toggle="radio" tabindex="3" @if (instance(
				 'Request')->getInput('access-type') && instance( 'Request')->getInput('access-type') == 'radio2') checked @endif> {{ lang('server.create.protected')
				}}.
				<div class="info">{{ lang('server.create.protected_desc') }}</div>
			</label>
			<label class="radio" for="radio3">
				<input type="radio" name="access-type" value="private" id="radio3" data-toggle="radio" tabindex="4" @if (instance(
				 'Request')->getInput('access-type') && instance( 'Request')->getInput('access-type') == 'radio3') checked @endif> {{ lang('server.create.private')
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
			<input type="file" name="background-placeholder" value="" class="form-control __input-dark" id="background-placeholder" tabindex="5"
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
	<input type="hidden" name="{{ instance('Token')::createTokenInput() }}" value="{{ instance('Token')::get() }}" /> {{-- SUBMIT --}}
	<div class="form-group">
		<div class="col-md-offset-3 col-md-9">
			<button type="submit" class="btn btn-primary gc-float-right" tabindex="7">{{ lang('server.create.inp_sub') }}</button>
		</div>
	</div>
</form>
@endsection