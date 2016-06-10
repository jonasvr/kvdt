
    <h4>Agenda</h4>
@foreach($agenda as $key => $event)
    <h5>{{$event->summary}}</h5>
    <p>start: {{$event->start}}</p>
    <p>end: {{$event->end}}</p>
        <br>
@endforeach


{{--j/m/y h:i--}}