@extends('layouts.master') @section('title', $thisserver->name) @section('header', $thisserver->name)

@include('includes.structures.event-list', ['thisserver' => $thisserver])

@section('content')
<div class="server-index-wrapper">
    <div class="col-xs-12 col-sm-3 column-wrapper">
        <div class="event-list-box">
			<h2>{{ lang('server.index.title_box_01') }}</h2>
			<hr class="color">
            <div class="event-menu-box pill-box">
                <ul class="nav nav-pills">
                    <li class="--w100 __button"><a href="#">add new</a></li>
                    <li class="--w50 active"><a data-toggle="pill" href="#list">List</a></li>
                    <li class="--w50"><a data-toggle="pill" href="#history">History</a></li>
                </ul>
                <div class="gc-cleaner"></div>
            </div>
            <div class="tab-content">
                <div id="list" class="tab-pane fade in active">
                    @yield('event-list')
                </div>
                <div id="history" class="tab-pane fade">
                    <h3>Menu 1</h3>
                    <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
            </div>
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