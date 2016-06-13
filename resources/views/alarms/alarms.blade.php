@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
                @if($alarms->count())
                <div class="panel panel-primary">
                    <div class="panel-heading"><h1>Edit the alarms you want to set</h1></div>
                    <div class="panel-body row">
                        {{ Form::open(array('url' => URL::route('updateAlarm'), 'method' => 'Post')) }}
                        @foreach($alarms as $key => $event)
                            <div class="col-lg-4 col-md-6">
                                <div class="panel panel-green">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-2">
                                                {{ Form::checkbox("event[$key]", $event['event_id'], FALSE, ['id'=>'link' . $key]) }}
                                            </div>
                                            <div class="col-xs-10 text-right">
                                                {{ Form::label("link$key" ,$event['summary']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#">
                                        <div class="panel-footer">
                                            <span class="pull-left">{{Form::time("alarmTime[$key]",$event['alarmTime'])}}</span>
                                            <span class="pull-right">
                                                <p>emergency:
                                                <a href="{{ URL::route('emergency', ['id'=>$event->id]) }}"><span class="glyphicon glyphicon-cog"></span></a>
                                                <a href="{{ URL::route('deleteAlarm', ['id'=>$event->id]) }}"><span class="glyphicon glyphicon-trash"></span></a>
                                                </p>

                                                {{  Form::hidden("alarmDate[$key]", $event['alarmDate'], ['class' => 'time'])}}
                                            </span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{ Form::submit('update', ['name' => 'action','class'=>'btn btn-default margin-25']) }}
                    {{ Form::close() }}
                    <div class="col-md-10 col-md-offset-1">
                        @include('contacts.buttons')
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

{{--<label for="link{{$key}}">--}}
{{--<p>{{$event['summary']}} </p>--}}
{{--<p>{{$event['alarmDate']}}</p>--}}
{{--<p>{{Form::time("alarmTime[$key]",$event['alarmTime'])}}--}}
{{--</p>--}}
{{--</label>--}}
{{--<p> emergency:--}}
{{--<a href="{{ URL::route('emergency', ['id'=>$event->id]) }}"><span class="glyphicon glyphicon-cog"></span></a>--}}
{{--<a href="{{ URL::route('deleteAlarm', ['id'=>$event->id]) }}"><span class="glyphicon glyphicon-trash"></span></a>--}}
{{--{{  Form::hidden("alarmDate[$key]", $event['alarmDate'], ['class' => 'time'])}}--}}
{{--</p>--}}