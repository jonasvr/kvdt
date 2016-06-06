@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                @if($alarms->count())
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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
