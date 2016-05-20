@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>select the events you want to set</h1>
            {{ Form::open(array('url' => URL::route('setEvents'), 'method' => 'Post')) }}
            @foreach($events as $key => $event)
                <div value="event">
                    {{ Form::checkbox("event[$key]", $event['data'], FALSE, ['id'=>'link' . $key]) }}
                    {{ Form::label("link$key" , $event['start'] . ' => ' .$event['summary']) }}
                    {{-- {{ Form::input('datetime-local', 'alarm['.$key.']',$event['start'] )}} --}}
                    {{ Form::time("alarm[$key]",$event['startTime']) }}
                    {{  Form::hidden("date[$key]", $event['startDate'], ['class' => 'time'])}}
                </div>
            @endforeach
            {{ Form::submit('Submit!') }}
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('js')
    <script type="text/javascript">
    $(document).ready(function () {

        $( "time" ).css("disabled","disabled");
    });
    </script>
@endsection
