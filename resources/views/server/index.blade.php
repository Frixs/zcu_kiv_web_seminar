@extends('layouts.master')

@section('title', $thisserver->name) @section('header', $thisserver->name)

@include('includes.structures.event-list', ['thisserver' => $thisserver])
@include('includes.structures.event-preview', ['thisserver' => $thisserver, 'eid' => $eid])

@php
$guardCADisplay = instance('Guard')::has('server.calendar_events._display');
$guardCAAddNew = instance('Guard')::has('server.calendar_events.add_new');
$guardCAViewHistory = instance('Guard')::has('server.calendar_events.view_history');
@endphp

@section('content')
<div class="server-index-wrapper">
	<div class="col-xs-12 col-sm-3 column-wrapper">
		<div class="event-list-box">
			<h2>{{ lang('server.index.title_box_01') }}</h2>
			<hr class="color">
            @if ($guardCADisplay)
            @if ($guardCAAddNew || $guardCAViewHistory)
			<div class="event-menu-box pill-box">
				<ul class="nav nav-pills">
					@if ($guardCAAddNew)
					<li class="--w100 __button">
						<a href="#">add new</a>
					</li>
					@endif @if ($guardCAViewHistory)
					<li class="--w50 active">
						<a data-toggle="pill" href="#list">List</a>
					</li>
					<li class="--w50">
						<a data-toggle="pill" href="#history">History</a>
					</li>
					@endif
				</ul>
				<div class="gc-cleaner"></div>
			</div>
			@endif
			<div class="tab-content">
				<div id="list" class="tab-pane fade in active">
					@yield('event-list')
				</div>
				@if ($guardCAViewHistory)
				<div id="history" class="tab-pane fade text-center">
					<i>Available soon!</i>
				</div>
				@endif
			</div>
			@else
			<div class="event-menu-box pill-box text-center small">
				<i>{{ lang('server.index.no_permissions') }}</i>
			</div>
			@endif
		</div>
	</div>
	<div class="col-xs-12 col-sm-9 column-wrapper">
		<div class="event-preview-box">
			<h2>{{ lang('server.index.title_box_02') }}</h2>
			<hr class="color">
			@yield('event-preview')
		</div>
	</div>
	<div class="gc-cleaner"></div>
</div>
@endsection