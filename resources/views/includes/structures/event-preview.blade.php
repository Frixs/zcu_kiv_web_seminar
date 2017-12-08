@section('event-preview')
@php
    $event = instance('CalendarEvent')::getEvent($eid);
@endphp

<div class="event-preview-wrapper">
    @if ($event && $event->server_id === $thisserver->id)

    <div class="title-box">
        <strong><span>GvG</span>{{ $event->title }}</strong>
    </div>
    <div class="info-box">
        <div class="timing">
            <span><span>{{ lang('structures.event-preview.date_ph') }}</span> {{ lang('date.day_'. date("N", $event->time_from) .'_tag') }} {{ date("j.n. Y", $event->time_from) }}</span>
            <span><span>{{ lang('structures.event-preview.from_ph') }}</span> {{ date("H:i", $event->time_from) }} <b>{{ date("T", $event->time_from) }}</b></span>
            <span><span>{{ lang('structures.event-preview.to_ph') }}</span> {{ date("H:i", $event->time_to) }} <b>{{ date("T", $event->time_to) }}</b></span>
            <span><span>{{ lang('structures.event-preview.estimated_hours_ph') }}</span> {{ $event->time_estimated_hours }} <b>h</b></span>
        </div>
        <div class="details">
            <span><span>{{ lang('structures.event-preview.founder_ph') }}</span> {{ $event->founder_name }}</span>
            <span><span>{{ lang('structures.event-preview.count_ph') }}</span> {{ $event->user_count }}</span>
            <span><span class="__cred">{{ lang('structures.event-preview.edited_ph') }}</span> {{ date("j.n. H:i", $event->edited_time) }}</span>
            <form action="#" method="post" class="delete-form">
                <input type="hidden" name="'.$tokenFlash_delete.'" value="'.$tokenGenerate.'" />
                <button type="submit" class="delete-btn"><i class="fa fa-window-close" aria-hidden="true"></i></button>
                <div></div>
            </form>
            <a href="#" class="edit-btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
        </div>
        <div class="gc-cleaner"></div>
    </div>
    <div class="desc-box">
        <span class="title">{{ lang('structures.event-preview.description_ph') }}</span>
        <p>Something here.</p>
    </div>

    <div class="button-box">
        <form action="#" method="post">
            {{-- SERVER ID --}}
            <input type="hidden" name="serverid" value="{{ $thisserver->id }}" />
            {{-- EVENT ID --}}
            <input type="hidden" name="eventid" value="{{ $event->id }}" />
            {{-- TOKEN --}}
            <input type="hidden" name="{{ instance('Token')::createTokenInput() }}" value="{{ instance('Token')::get() }}" />
            {{-- LEAVE BTN --}}
            {{--<input type="submit" name="leave_event" value="{{ lang('structures.event-preview.leave_btn') }}" class="btn btn-danger" />--}}
            {{-- JOIN BTN --}}
            <input type="submit" name="join_event" value="{{ lang('structures.event-preview.join_btn') }}" class="btn btn-success" />
            <div class="overlay-group-name">
                <span>section name</span>
            </div>
        </form>
    </div>

    @else

    <div class="text-center small">
        <i>{{ lang('structures.event-preview.no_event_ph') }}</i>
    </div>

    @endif
</div>

@endsection