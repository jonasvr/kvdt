
    <h3>Agenda
        <a href="{{ URL::route('calendars') }}">
            <span class="glyphicon glyphicon-pencil "></span>
        </a>
    </h3><br>
@foreach($agenda as $key => $event)
    <h5 class="underline capitalize">{{$event->summary}}</h5>
    <p>start: {{$event->start}}</p>
    <p>end: {{$event->end}}</p>
    <br>
@endforeach
