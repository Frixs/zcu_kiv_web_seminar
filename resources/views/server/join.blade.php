@extends('layouts.plain-form')

@section('title', lang('server.join.title'))

@section('content')
<div class="server-join-page">
	<div class="form-wrapper col-xs-12">
		<div class="head-spacer"></div>
		<h2 class="text-center">{{ lang('server.join.title') }}</h2>
        <strong class="server-name">{{ $thisserver->name }}</strong>
		<form class="form-horizontal" action="{{ instance('Config')::get('app.root_rel') }}/__request/server-join" method="post">
            {{-- CAPTCHA --}}
            <div class="form-group @if (instance('Request')->getInputError('g-recaptcha-response')) has-error @endif">
                <div class="col-xs-12" style="height: 62px;">
                    <div class="g-recaptcha" data-sitekey="{{ instance('Config')::get('captcha.g_recaptcha.site_key') }}" data-theme="dark" tabindex="6"
                    style="margin: auto;"></div>
                </div>
                <div class="col-xs-12">
                    <div class="form-feedback text-center">@if (instance('Request')->getInputError('g-recaptcha-response')) {{ instance('Request')->getInputError('g-recaptcha-response') }} @endif</div>
                </div>
            </div>
            {{-- TOKEN --}}
            <input type="hidden" name="{{ instance('Token')::createTokenInput() }}" value="{{ instance('Token')::get() }}" />
            {{-- SERVER ID --}}
            <input type="hidden" name="serverid" value="{{ $thisserver->id }}" />
            <div class="gc-cleaner"></div>
            {{-- SUBMIT --}}
            <div class="form-group gc-margin-top">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary gc-float-right" tabindex="7">{{ lang('server.join.inp_sub') }}</button>
                </div>
            </div>
            <div class="gc-cleaner"></div>
        </form>
	</div>
	<div class="gc-cleaner"></div>
</div>
@endsection