@extends('layouts.master') @section('title', lang('dashboard.index.title')) @section('header', lang('dashboard.index.title'))
@section('content')
<div class="dashboard-wrapper">
	<div class="col-xs-12 col-sm-6 column-master-wrapper">
		<div class="col-sm-12 col-lg-6 column-wrapper my-servers-wrapper">
			<div>
				<h2>Column 1</h2>
				1-a
			</div>
		</div>
		<div class="col-sm-12 col-lg-6 column-wrapper calendar-wrapper">
			<div>
				<h2>Column 2</h2>
				2-a
				<br>2-b
			</div>
		</div>
		<div class="gc-cleaner"></div>
	</div>
	<div class="col-xs-12 col-sm-6 column-master-wrapper">
		<div class="col-sm-12 col-lg-6 column-wrapper top-servers-wrapper">
			<div>
				<h2>Column 3</h2>
				3-a
				<br>3-b
			</div>
		</div>
		<div class="col-sm-12 col-lg-6 column-wrapper chat-wrapper">
			<div>
				<h2>Column 4</h2>
				4-a
			</div>
		</div>
		<div class="gc-cleaner"></div>
	</div>
	<div class="gc-cleaner"></div>
</div>
@endsection