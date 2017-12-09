@section('event-preview')

@php

$authUID = instance('Auth')::id();
$event = instance('CalendarEvent')::getEvent($eid);
$isEventValid = false;
if ($event && $event->server_id === $thisserver->id) {
	$isEventValid = true;
	$eventSections = instance('CalendarEventSection')::getEventSections($eid);
}

$guardCAEdit = instance('Guard')::has('server.calendar_events.edit');
$guardCADelete = instance('Guard')::has('server.calendar_events.delete');
$guardCAJoin = instance('Guard')::has('server.calendar_events.join');
$guardCALeave = instance('Guard')::has('server.calendar_events.leave');

@endphp
<script>
    $(document).ready(function () {
        $('#notice-btn').click(function () {
            if (!$('#pop-area').val()) {
				setTimeout(function(){
                	$('#pop-area').val($('#notice-inp').val());
				}, 100);
            } else {
                $('#notice-inp').val($('#pop-area').val());
            }
        });

		$('#nav-section > li').click(function () {
			let sectionID = $(this).find('a').attr('data-section');
			let sectionName = $(this).find('a').text();
			$('#sectionid').val(sectionID);
			$('#section-placeholder').text(sectionName);
		});
    });
</script>

<div class="event-preview-wrapper">
	@if ($isEventValid)
	{{-- TITLE --}}
	<div class="title-box">
		<strong>
			{{--<span>GvG</span>--}}
			{{ $event->title }}
		</strong>
	</div>
	{{-- INFO-BOX --}}
	<div class="info-box">
		<div class="timing">
			{{-- DATE FROM --}}
			<span>
				<span>{{ lang('structures.event-preview.date_ph') }}</span>
				<b>{{ lang('date.day_'. date("N", $event->time_from) .'_tag') }}</b> {{ date("j.n. Y", $event->time_from) }}</span>
			{{-- TIME FROM --}}
			<span>
				<span>{{ lang('structures.event-preview.from_ph') }}</span> {{ date("H:i", $event->time_from) }}
				<b>{{ date("T", $event->time_from) }}</b>
			</span>
			{{-- TIME TO --}}
			@if ($event->time_to > 0)
			<span>
				<span>{{ lang('structures.event-preview.to_ph') }}</span> {{ date("H:i", $event->time_to) }}
				<b>{{ date("T", $event->time_to) }}</b>
				@if (date("Y-m-d", $event->time_to) != date("Y-m-d", $event->time_from)) ({{ date("j.n. Y", $event->time_to) }}) @endif
			</span>
			@endif
			{{-- ESTIMATED HOURS --}}
			@if ($event->time_estimated_hours > 0)
			<span>
				<span>{{ lang('structures.event-preview.estimated_hours_ph') }}</span> {{ $event->time_estimated_hours }}
				<b>h</b>
			</span>
			@endif
		</div>
		<div class="details">
			<span>
				<span>{{ lang('structures.event-preview.founder_ph') }}</span> {{ $event->founder_name }}</span>
			<span>
				<span>{{ lang('structures.event-preview.count_ph') }}</span> {{ $event->user_count }}</span>
			@if ($event->edited_time > 0)
			<span>
				<span class="__cred">{{ lang('structures.event-preview.edited_ph') }}</span> {{ date("j.n. H:i", $event->edited_time) }}</span>
			@endif
			{{-- DELETE THE EVENT BTN --}}
			@if ($guardCADelete)
			<form action="#" method="post" class="delete-form">
				<input type="hidden" name="'.$tokenFlash_delete.'" value="'.$tokenGenerate.'" />
				<button type="submit" class="delete-btn">
					<i class="fa fa-window-close" aria-hidden="true"></i>
				</button>
				<div></div>
			</form>
			@endif
			{{-- EDIT THE EVENT BTN --}}
			@if ($guardCAEdit)
			<a href="#" class="edit-btn">
				<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
			</a>
			@endif
		</div>
		<div class="gc-cleaner"></div>
	</div>
	{{-- DESCRIPTION --}}
	@if (strlen($event->description))
	<div class="desc-box">
		<span class="title">{{ lang('structures.event-preview.description_ph') }}</span>
		<p>{{ $event->description }}</p>
	</div>
	@endif

	{{-- JOIN/LEAVE THE SECTION --}}
	@if ($guardCAJoin || $guardCALeave) 
	<div class="button-box">
		<form action="#" method="post" class="join-leave-form">
			<input type="hidden" name="serverid" value="{{ $thisserver->id }}" />
			<input type="hidden" name="eventid" value="{{ $event->id }}" />
			<input type="hidden" name="{{ instance('Token')::createTokenInput() }}" value="{{ instance('Token')::get() }}" />
            {{-- LEAVE BTN --}}
			@if ($guardCALeave && $event->participation && ($event->time_from - instance('Config')::get('event.leave_time_before_start')) > time())
            <input type="submit" name="leave_event" value="{{ lang('structures.event-preview.leave_btn') }}" class="btn btn-danger" />
			@endif
            {{-- JOIN BTN --}}
			@if ($guardCAJoin && !$event->participation && ($event->time_from + instance('Config')::get('event.join_time_after_start')) > time())
			<input id="notice-inp" type="hidden" name="notice" value="" />
			<input id="sectionid" type="hidden" name="sectionid" value="{{ $eventSections[0]->id }}" />
			<input type="submit" name="join_event" value="{{ lang('structures.event-preview.join_btn') }}" class="btn btn-success" />
			<div class="overlay-group-name">
				<span id="section-placeholder">{{ $eventSections[0]->name }}</span>
			</div>
            <div class="pop-box">
                <a id="notice-btn" class="pop-textarea-btn" href="#" title="{{ lang('structures.event-preview.notice_ph') }}" data-toggle="popover" data-placement="left auto" data-html="true" data-content="<textarea id='pop-area' class='__fixed __h150' maxlength='255'></textarea>"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
            </div>
			@endif
		</form>
	</div>
	@endif

	{{-- SECTIONS --}}
	<div class="event-table-box">
		{{-- SECTION MENU --}}
		<div class="col-xs-12 col-sm-4 col-lg-3 table-menu-wrapper pill-box col-no-spacing-left">
			<ul id="nav-section" class="nav nav-pills">
			@php $i = 0; @endphp
			@foreach ($eventSections as $section)
				<li class="--w100 @if (!$i) active @endif">
					<a data-toggle="pill" href="#section-{{ $i }}" data-section="{{ $section->id }}">{{ $section->name }} <b>{{$section->user_count}}@if ($section->is_limited)/{{ $section->limit_max }}@endif</b></a>
				</li>
				@php $i++; @endphp
			@endforeach
			</ul>
		</div>
		{{-- SECTION WRAPPER --}}
		<div class="tab-content table-wrapper col-xs-12 col-sm-8 col-lg-9 col-no-spacing-right">
			@php $i = 0; @endphp
			@foreach ($eventSections as $section)
			<div id="section-{{ $i }}" class="table-responsive tab-pane fade @if (!$i) in active @endif">
				<table class="table">
					<thead>
						<tr>
							<th>{{ lang('structures.event-preview.nickname_ph') }}</th>
							<th>{{ lang('structures.event-preview.participation_ph') }}</th>
							<th>{{ lang('structures.event-preview.notice_ph') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach (instance('CalendarEventSection')::getUsers($section->id) as $sUser)
                        <tr>
                            <td>
                                <span>{{ $sUser->username }}</span>
                            </td>
                            <td>
                                <i class="fa fa-circle event-participation-yes ttip --tt-darkest --tt-fat" data-toggle="tooltip" data-placement="left auto" title="Setting up this option will be available soon!" aria-hidden="true"></i>
                            </td>
                            <td>
								@if (strlen($sUser->notice))
                                <i class="fa fa-question-circle notice ttip --tt-darkest --tt-fat" data-toggle="tooltip" data-placement="left auto" title="{{ $sUser->notice }}" aria-hidden="true"></i>
								@endif
                            </td>
                        </tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@php $i++; @endphp
			@endforeach
		</div>
	</div>
	<div class="gc-cleaner"></div>

	@else

	<div class="text-center small">
		<i>{{ lang('structures.event-preview.no_event_ph') }}</i>
	</div>

	@endif
</div>

@endsection