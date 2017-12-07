@section('event-list')

<div class="event-list-wrapper">

@foreach (instance('CalendarEvent')::getServerEvents($thisserver->id) as $event)
    <div class="event-box">
        {{ $event->title }}
    </div>
@endforeach

</div>

@endsection