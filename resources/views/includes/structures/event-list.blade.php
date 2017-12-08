@section('event-list')
@php
    $eventList = instance('CalendarEvent')::getServerEvents($thisserver->id);
    $eventCount = count($eventList);
@endphp

<div class="event-list-wrapper">

	@foreach ($eventList as $event)
	<a href="?event:{{ $event->id }}" class="event-box @if ($event->time_from + instance('Config')::get('event.time_in_progress') < time()) __passed @elseif ($event->time_from <= time() && $event->time_from + instance('Config')::get('event.time_in_progress') >= time()) __in-progress @endif">
		<div class="passed-box gc-hidden">
            <span>{{ lang('structures.event-list.passed_ph') }}</span>
		</div>
		<div class="type-box">
            <span></span>
		</div>
		<div class="date-start-box">
            <span><span>{{ lang('date.day_'. date("N", $event->time_from) .'_tag') }}</span> {{ date("j.n. Y", $event->time_from) }}</span>
		</div>
		<div class="info-box">
			<div class="time-box">
                {{ date("H:i", $event->time_from) }}
			</div>
			<div class="user-count-box">
                @if ($event->participation)
                <span><i class="fa fa-user" aria-hidden="true"></i></span>
                @endif
                {{ $event->user_count }}
			</div>
		</div>
        <div class="gc-cleaner"></div>
		<div class="title-box">
			<strong title="{{ $event->title }}">{{ $event->title }}</strong>
		</div>
	</a>
	@endforeach

    @if (!$eventCount)
    <div class="text-center small">
        <i>{{ lang('structures.event-list.no_events_ph') }}</i>
    </div>
    @endif

</div>

@endsection