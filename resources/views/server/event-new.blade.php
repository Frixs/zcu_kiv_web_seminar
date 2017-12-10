@extends('layouts.master-form')

@section('title', $thisserver->name .' | '. lang('server.event-new.title'))
@section('header', $thisserver->name) @section('sub-header', lang('server.event-new.title'))

@section('content-form')

@php
@endphp
<script>
    $(document).ready(function () {
        $("#add-section-btn").click(function () {
            let sectionBoxHTML = ' \
                <div class="section-box gc-hidden"> \
                    <button class="section-remove" type="button"><i class="fa fa-times" aria-hidden="true"></i></button> \
                    <div class="form-group"> \
                        <label class="col-xs-12">{{ lang("server.event-new.inp_06") }}:</label> \
                        <div class="col-xs-12"> \
                            <input type="text" name="section-name[]" value="" class="form-control __input-dark" \
                            placeholder="{{ lang("server.event-new.inp_06_ph") }}" maxlength="15" tabindex="6" autocomplete="off"> \
                        </div> \
                    </div> \
                    <div class="form-group"> \
                        <div class="col-xs-12"> \
                            <input type="number" name="section-limit[]" value="" class="form-control __input-dark" \
                            placeholder="{{ lang("server.event-new.inp_06_ph_2") }}" min="0" max="999" tabindex="7" autocomplete="off"> \
                        </div> \
                    </div> \
                </div> \
            ';

            $(".section-wrapper").find(".section-box").last().after(sectionBoxHTML);
            $(".section-wrapper").find(".section-box.gc-hidden").slideDown(300, function () {
                $(this).removeClass("gc-hidden");
            });
            
            if ($(".section-wrapper").find(".section-box").length >= {{ instance('Config')::get('event.section_limit') }}) {
                $(this).slideUp();
            }
        });
        
        $(".section-wrapper").on("click", ".section-remove", function () {
            $(this).parent().slideUp(300, function () {
                $(this).remove();
                
                if ($(".section-wrapper").find(".section-box").length < {{ instance('Config')::get('event.section_limit') }}) {
                    $("#add-section-btn").slideDown();
                }
            });
        });
    });
</script>

<form class="event-new-form form-horizontal" action="{{ instance('Config')::get('app.root_rel') }}/__request/event-create" method="post">
	<div class="form-feedback __success">{{ instance('Request')->messageSuccess() }}</div>
	<div class="form-feedback __error">{{ instance('Request')->messageError() }}</div>
    {{-- DETAILS --}}
    <div class="col-xs-12 col-sm-6 gc-col-nosp-leftm-xs">
		<h2>{{ lang('server.event-new.title_box_01') }}</h2>
		<hr class="color">
        {{-- TITLE --}}
        <div class="form-group @if (instance('Request')->getInputError('title')) has-error @endif">
            <label class="col-xs-12" for="title">{{ lang('server.event-new.inp_01') }}:</label>
            <div class="col-xs-12">
                <input type="text" name="title" value="{{ instance('Request')->getInput('title') }}" class="form-control __input-dark" id="title"
                placeholder="{{ lang('server.event-new.inp_01_ph') }}" maxlength="30" tabindex="1" autocomplete="off" autofocus>
                <div class="form-feedback">@if (instance('Request')->getInputError('title')) {{ instance('Request')->getInputError('title') }} @endif</div>
            </div>
        </div>
        {{-- DESCRIPTION --}}
        <div class="form-group @if (instance('Request')->getInputError('description')) has-error @endif">
            <label class="col-xs-12" for="description">{{ lang('server.event-new.inp_02') }}:</label>
            <div class="col-xs-12">
                <textarea id="description" class="__full-width __input-dark" name="description" placeholder="{{ lang('server.event-new.inp_02_ph') }}" maxlength="1000" tabindex="2" autocomplete="off">{{ instance('Request')->getInputError('description') }}</textarea>
                <div class="form-feedback">@if (instance('Request')->getInputError('description')) {{ instance('Request')->getInputError('description') }} @endif</div>
            </div>
        </div>
        {{-- DATE START --}}
        <div class="form-group @if (instance('Request')->getInputError('date-from')) has-error @endif">
            <label class="col-xs-12" for="date-from">{{ lang('server.event-new.inp_03') }}:</label>
            <div class="col-xs-12">
                <input type="datetime-local" name="date-from" value="{{ instance('Request')->getInput('date-from') }}" class="form-control __input-dark" id="date-from"
                placeholder="{{ lang('server.event-new.inp_03_ph') }}" tabindex="3" autocomplete="off">
                <div class="form-feedback">@if (instance('Request')->getInputError('date-from')) {{ instance('Request')->getInputError('date-from') }} @endif</div>
            </div>
        </div>
        {{-- DATE END --}}
        <div class="form-group @if (instance('Request')->getInputError('date-to')) has-error @endif">
            <label class="col-xs-12" for="date-to">{{ lang('server.event-new.inp_04') }}:</label>
            <div class="col-xs-12">
                <input type="datetime-local" name="date-to" value="{{ instance('Request')->getInput('date-to') }}" class="form-control __input-dark" id="date-to"
                placeholder="{{ lang('server.event-new.inp_04_ph') }}" tabindex="4" autocomplete="off">
                <div class="form-feedback">@if (instance('Request')->getInputError('date-to')) {{ instance('Request')->getInputError('date-to') }} @endif</div>
            </div>
        </div>
        {{-- ESTIMATED HOURS --}}
        <div class="form-group @if (instance('Request')->getInputError('estimated-hours')) has-error @endif">
            <label class="col-xs-12" for="estimated-hours">{{ lang('server.event-new.inp_05') }}:</label>
            <div class="col-xs-12">
                <input type="number" name="estimated-hours" value="{{ instance('Request')->getInput('estimated-hours') }}" class="form-control __input-dark" id="estimated-hours"
                placeholder="{{ lang('server.event-new.inp_05_ph') }}" min="1" max="999" tabindex="5" autocomplete="off">
                <div class="form-feedback">@if (instance('Request')->getInputError('estimated-hours')) {{ instance('Request')->getInputError('estimated-hours') }} @endif</div>
            </div>
        </div>
    </div>
    {{-- SECTIONS --}}
    <div class="col-xs-12 col-sm-6 gc-col-nosp-rightm-xs section-wrapper">
		<h2>{{ lang('server.event-new.title_box_02') }}</h2>
		<hr class="color">
        {{-- SECTION BLOCK --}}
        @if (!instance('Request')->getInput('section-name'))
        <div class="section-box">
            {{-- NAME --}}
            <div class="form-group">
                <label class="col-xs-12">{{ lang('server.event-new.inp_06') }}:</label>
                <div class="col-xs-12">
                    <input type="text" name="section-name[]" value="" class="form-control __input-dark"
                    placeholder="{{ lang('server.event-new.inp_06_ph') }}" maxlength="15" tabindex="6" autocomplete="off">
                </div>
            </div>
            {{-- LIMIT --}}
            <div class="form-group">
                <div class="col-xs-12">
                    <input type="number" name="section-limit[]" value="" class="form-control __input-dark"
                    placeholder="{{ lang('server.event-new.inp_06_ph_2') }}" min="0" max="999" tabindex="7" autocomplete="off">
                </div>
            </div>
        </div>
        @else
        @foreach (instance('Request')->getInput('section-name') as $key => $section)
        <div class="section-box">
            <div class="form-feedback">@if (instance('Request')->getInputError($key)) {{ instance('Request')->getInputError($key) }} @endif</div>
            @if (!$loop->first)
            <button class="section-remove" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
            @endif
            {{-- NAME --}}
            <div class="form-group">
                <label class="col-xs-12">{{ lang('server.event-new.inp_06') }}:</label>
                <div class="col-xs-12">
                    <input type="text" name="section-name[]" value="{{ $section }}" class="form-control __input-dark"
                    placeholder="{{ lang('server.event-new.inp_06_ph') }}" maxlength="15" tabindex="6" autocomplete="off">
                </div>
            </div>
            {{-- LIMIT --}}
            <div class="form-group">
                <div class="col-xs-12">
                    <input type="number" name="section-limit[]" value="{{ instance('Request')->getInput('section-limit')[$key] }}" class="form-control __input-dark"
                    placeholder="{{ lang('server.event-new.inp_06_ph_2') }}" min="0" max="999" tabindex="7" autocomplete="off">
                </div>
            </div>
        </div>
        @endforeach
        @endif
        <button id="add-section-btn" class="btn btn-primary __w100 gc-margin-top" type="button">{{ lang('server.event-new.section_add_btn') }}</button>
    </div>
    <div class="gc-cleaner"></div>
    <hr>
	{{-- TOKEN --}}
	<input type="hidden" name="{{ instance('Token')::createTokenInput() }}" value="{{ instance('Token')::get() }}" />
	{{-- SERVER ID --}}
	<input type="hidden" name="serverid" value="{{ $thisserver->id }}" />
	{{-- SUBMIT --}}
	<div class="form-group gc-no-margin-bottom">
		<div class="col-md-offset-3 col-md-9">
			<button type="submit" class="btn btn-primary gc-float-right" tabindex="9">{{ lang('server.event-new.inp_sub') }}</button>
		</div>
	</div>
</form>

@endsection