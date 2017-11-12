@extends('layouts.master')

@section('title', lang('dashboard.index.title'))
@section('header', lang('dashboard.index.title'))

@section('content')
    <div class="dashboard-wrapper">
        <div class="col-xs-12 col-sm-6 col-lg-3 column-wrapper my-servers-wrapper">
            <h2>Column 1</h2>
            1-a<br>1-b
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-3 column-wrapper calendar-wrapper">
            <h2>Column 2</h2>
            2-a
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-3 column-wrapper top-servers-wrapper">
            <h2>Column 3</h2>
            3-a<br>3-b
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-3 column-wrapper chat-wrapper">
            <h2>Column 4</h2>
            4-a
        </div>
        <div class="gc-cleaner"></div>
    </div>
@endsection