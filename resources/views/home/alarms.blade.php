<h3>Alarms</h3> <br>
@foreach($alarms as $key => $alarm)
    <h5 class="underline capitalize">{{$alarm->summary}}</h5>
        <p>{{ $alarm->alarmDate }}</p>
        <p>{{ $alarm->alarmTime }}</p>
    <br>
@endforeach
