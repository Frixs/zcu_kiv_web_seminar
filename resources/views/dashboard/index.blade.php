@extends('layouts.master') @section('title', lang('dashboard.index.title')) @section('header', lang('dashboard.index.title'))
@section('content')
<div class="dashboard-wrapper">
	<div class="col-xs-12 col-sm-6 column-master-wrapper">
		<div class="col-sm-12 col-lg-6 column-wrapper my-servers-wrapper">
			<div>
				<h2>{{ lang('dashboard.index.title_box_01') }}</h2>
				<hr>
				MY SERVERS
			</div>
		</div>
		<div class="col-sm-12 col-lg-6 column-wrapper calendar-wrapper">
			<div class="text-center">
				<h2>{{ lang('dashboard.index.title_box_02') }}</h2>
				<hr>
				@include('includes.structures.calendar', ['type' => 'all'])
			</div>
		</div>
		<div class="gc-cleaner"></div>
	</div>
	<div class="col-xs-12 col-sm-6 column-master-wrapper">
		<div class="col-sm-12 col-lg-6 column-wrapper top-servers-wrapper">
			<div>
				<h2>{{ lang('dashboard.index.title_box_03') }}</h2>
				<hr>
				TOP SERVERS + JOIN
			</div>
		</div>
		<div class="col-sm-12 col-lg-6 column-wrapper chat-wrapper">
			<div class="text-center">
				<h2>{{ lang('dashboard.index.title_box_04') }}</h2>
				<hr>
				<i>Available soon!</i>
			</div>
		</div>
		<div class="gc-cleaner"></div>
	</div>
	<div class="gc-cleaner"></div>
</div>
@endsection