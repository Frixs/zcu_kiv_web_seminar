@extends('layouts.master') @section('title', $thisserver->name) @section('header', $thisserver->name)

@include('includes.structures.event-list', ['thisserver' => $thisserver])

@section('content')
<div class="server-index-wrapper">
    <div class="col-xs-12 col-sm-3 column-wrapper">
        <div class="event-list-box">
			<h2>{{ lang('server.index.title_box_01') }}</h2>
			<hr class="color">
            @yield('event-list')
        </div>
    </div>
    <div class="col-xs-12 col-sm-9 column-wrapper">
        <div class="event-preview-box">
			<h2>{{ lang('server.index.title_box_02') }}</h2>
			<hr class="color">
        </div>
    </div>
    <div class="gc-cleaner"></div>
</div>
@endsection