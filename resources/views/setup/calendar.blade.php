@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-6">

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2> Select Calendars to follow</h2>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="list-group">
                    {{ Form::open(array('url' => URL::route('setCalendars'), 'method' => 'Post')) }}

                    @foreach($calendarList as $key => $value)
                            @if(!$value['follow'] )
                                <div href="#" class="list-group-item">
                                    {{ Form::checkbox('calendar[]', $value['id'], false, ['id'=>"link$key"]) }}

                                    {{ Form::label("link$key", $value['title'], [ "class" =>($value['follow']) ? 'green':'']) }} <br \>

                                </div>
                            @endif
                        @endforeach
                    </div>
                    <!-- /.list-group -->
                    {{--<a href="#" class="btn btn-default btn-block">View All Events</a>--}}
                    {{ Form::submit('follow!', ['name' => 'action', 'class' => 'btn btn-default btn-block']) }}
                    {{ Form::close() }}
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
        <div class="col-md-6">

            <div class="panel panel-green">
                <div class="panel-heading">
                    <h2> Calendars you follow</h2>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="list-group">
                {{ Form::open(array('url' => URL::route('setCalendars'), 'method' => 'Post')) }}
                @foreach($calendarList as $key => $value)
                    @if($value['follow'])
                        <div href="#" class="list-group-item">

                            {{ Form::checkbox('calendar[]', $value['id'], false, ['id'=>"link$key"]) }}
                            {{ Form::label("link$key", $value['title']) }} <br \>
                        </div>
                    @endif
                @endforeach
                    </div>

                    {{ Form::submit('unfollow!', ['name' => 'action', 'class' => 'btn btn-default btn-block']) }}
                {{ Form::close() }}
            {{--@endif--}}

        </div>
    </div>
</div>
@endsection
