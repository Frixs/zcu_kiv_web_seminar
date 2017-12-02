@extends('layouts.master')

@include('includes.structures.server-form', ['form_action' => instance('Config')::get('app.root_rel').'/__request/server-edit', 'loading_data' => $thisserver])

@section('title', $thisserver->name .' | '. lang('server.settings.title'))
@section('header', $thisserver->name) @section('sub-header', lang('server.settings.title'))

@php
	$guardSettingsGroups = instance('Guard')::has('server.settings.groups');
	$guardSettingsNotifications = instance('Guard')::has('server.settings.notifications');
	$guardSettingsDiscord = instance('Guard')::has('server.settings.discord');
	$guardSettingsBasics  = instance('Guard')::has('server.settings.basics');
@endphp

@section('content')
<div class="server-wrapper dashboard-layout">
	<div class="col-xs-12 col-sm-6 column-master-wrapper">
		{{-- GROUPS --}}
		<div class="col-sm-12 col-lg-6 column-wrapper">
			@if ($guardSettingsGroups)
			<div>
				<h2>{{ lang('server.settings.title_box_01') }}</h2>
				<hr class="color">
			</div>
			@else
			<div class="text-center">
				<h2>{{ lang('server.settings.title_box_01') }}</h2>
				<hr class="color">
                <i>{{ lang('auth.no_permissions') }}</i>
			</div>
			@endif
		</div>
		{{-- NOTIFICATIONS --}}
		<div class="col-sm-12 col-lg-6 column-wrapper">
			@if ($guardSettingsNotifications)
			<div class="text-center">
				<h2>{{ lang('server.settings.title_box_02') }}</h2>
				<hr class="color">
                <i>Available soon!</i>
			</div>
			@else
			<div class="text-center">
				<h2>{{ lang('server.settings.title_box_02') }}</h2>
				<hr class="color">
                <i>{{ lang('auth.no_permissions') }}</i>
			</div>
			@endif
		</div>
		<div class="gc-cleaner"></div>
	</div>
	<div class="col-xs-12 col-sm-6 column-master-wrapper">
		{{-- DISCORD --}}
		<div class="col-sm-12 col-lg-6 column-wrapper">
			@if ($guardSettingsDiscord)
			<div class="text-center">
				<h2>{{ lang('server.settings.title_box_03') }}</h2>
				<hr class="color">
                <i>Available soon!</i>
			</div>
			@else
			<div class="text-center">
				<h2>{{ lang('server.settings.title_box_03') }}</h2>
				<hr class="color">
                <i>{{ lang('auth.no_permissions') }}</i>
			</div>
			@endif
		</div>
		{{-- BASICS --}}
		<div class="col-sm-12 col-lg-6 column-wrapper">
			@if ($guardSettingsBasics)
			<div>
				<h2>{{ lang('server.settings.title_box_04') }}</h2>
				<hr class="color">
                @yield('server-form')
			</div>
			@else
			<div class="text-center">
				<h2>{{ lang('server.settings.title_box_04') }}</h2>
				<hr class="color">
                <i>{{ lang('auth.no_permissions') }}</i>
			</div>
			@endif
		</div>
		<div class="gc-cleaner"></div>
	</div>
	<div class="gc-cleaner"></div>
</div>
@endsection