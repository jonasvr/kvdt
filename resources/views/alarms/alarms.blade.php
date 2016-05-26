{{-- {{ dd($alarms) }} --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>edit the alarms you want to set</h1>
            {{ Form::open(array('url' => URL::route('updateAlarm'), 'method' => 'Post')) }}
            @foreach($alarms as $key => $event)
                <div value="event">
                    {{ Form::checkbox("event[$key]", $event['event_id'], FALSE, ['id'=>'link' . $key]) }}
                    {{ Form::time("alarmTime[$key]",$event['alarmTime']) }}

                    {{ Form::label("link$key" , $event['start'] . ' => ' .$event['summary']) }}
                    {{-- {{ Form::input('datetime-local', 'alarm['.$key.']',$event['start'] )}} --}}
                    {{  Form::hidden("alarmDate[$key]", $event['alarmDate'], ['class' => 'time'])}}
                </div>
            @endforeach
            {{ Form::submit('update!', ['name' => 'action']) }}
            {{ Form::submit('remove!',['name' => 'action']) }}
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
