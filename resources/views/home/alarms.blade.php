<div class="panel-heading">alarms</div>
<div class="panel-body row">
    @foreach($alarms as $key => $alarm)
        <div class="col-md-3 text-center margin-25 border-alarm height-200">
            <p>{{ $alarm->summary }}</p>
            <p>{{ $alarm->alarmDate }}</p>
            <p>{{ $alarm->alarmTime }}</p>
        </div>
    @endforeach
</div>