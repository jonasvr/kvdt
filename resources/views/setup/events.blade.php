@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-primary panel-">
                @if($events)
                    <div class="panel-heading"><h1>Select the events you want to set</h1></div>
                    <div class="panel-body row">
                        {{ Form::open(array('url' => URL::route('setEvents'), 'method' => 'Post')) }}
                        @foreach($events as $key => $event)
                            <div class="col-lg-4 col-md-6">
                                <div class="panel panel-green">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-2">
                                                {{ Form::checkbox("event[$key]", $event['data'], FALSE, ['id'=>'link' . $key]) }}

                                            </div>
                                            <div class="col-xs-10 text-right">
                                                {{ Form::label("link$key" ,$event['summary']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#">
                                        <div class="panel-footer">
                                            <span class="pull-left">{{$event['startDate']}} {{$event['startTime']}}</span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                        @endforeach
                        {{ Form::hidden("alarm[$key]",$event['startTime']) }}
                        {{ Form::hidden("date[$key]", $event['startDate'], ['class' => 'time'])}}

                    </div>
                    <div class="col-lg-3 col-md-6">
                        {{ Form::submit('Submit!',['class'=>'btn btn-default btn-block margin-tb-10']) }}
                    </div>
                    {{ Form::close() }}
                @endif
            </div>
        </div>
    </div>
</div>

@endsection


{{--<div class="col-md-3 text-center margin-25 border-alarm height-150">
                                <p>
                                    {{ Form::label("link$key" ,$event['summary']) }}
                                </p>
                                <p>{{$event['startTime']}}</p>
                                <p>{{$event['startDate']}}</p>
                                {{ Form::hidden("alarm[$key]",$event['startTime']) }}
                                {{ Form::hidden("date[$key]", $event['startDate'], ['class' => 'time'])}}
                            </div>--}}

