@extends('layouts.plain-form')

@include('includes.structures.server-form', ['form_action' => instance('Config')::get('app.root_rel').'/_request/server-create'])

@section('title', lang('server.create.title'))

@section('content')
<div class="server-create-page">
	<div class="form-wrapper col-xs-12">
		<div class="head-spacer"></div>
		<h2 class="text-center">{{ lang('server.create.title') }}</h2>
		@yield('server-form')
	</div>
	<div class="gc-cleaner"></div>
</div>
@endsection