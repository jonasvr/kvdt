@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                @if($events)
                    <div class="panel-heading"><h1>Select the events you want to set</h1></div>
                    <div class="panel-body row">
                        {{ Form::open(array('url' => URL::route('setEvents'), 'method' => 'Post')) }}
                        @foreach($events as $key => $event)
                            <div class="col-md-3 text-center margin-25 border-alarm height-150">
                                <p>
                                    {{ Form::checkbox("event[$key]", $event['data'], FALSE, ['id'=>'link' . $key]) }}
                                    {{ Form::label("link$key" ,$event['summary']) }}
                                </p>
                                <p>{{$event['startTime']}}</p>
                                <p>{{$event['startDate']}}</p>
                                {{ Form::hidden("alarm[$key]",$event['startTime']) }}
                                {{ Form::hidden("date[$key]", $event['startDate'], ['class' => 'time'])}}
                            </div>
                        @endforeach
                    </div>
                    {{ Form::submit('Submit!',['class'=>'btn btn-default margin-25']) }}
                    {{ Form::close() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
