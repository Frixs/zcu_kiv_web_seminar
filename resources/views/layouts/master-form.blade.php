@extends('layouts.master')

@section('content')
<div class="master-form-layout">
    <div class="col-xs-11 col-sm-8 col-lg-5 col-lg-form-middle-max form-middle-wrapper">
        <div class="jumbotron col-xs-12">
            @yield('content-form')
        </div>
        <div class="gc-cleaner"></div>
    </div>
    <div class="gc-cleaner"></div>
</div>
@endsection