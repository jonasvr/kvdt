<h3>Alarms
    <a href="{{ URL::route('alarms') }}">
        <span class="glyphicon glyphicon-pencil"></span>
    </a>
</h3>
<br>
@foreach($alarms as $key => $alarm)
    <h5 class="underline capitalize">{{$alarm->summary}}</h5>
        <p>{{ $alarm->alarmDate }}</p>
        <p>{{ $alarm->alarmTime }}</p>
    <br>
@endforeach
