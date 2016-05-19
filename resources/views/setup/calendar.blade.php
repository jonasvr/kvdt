@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>select the calendars you want to follow</h1>
            {{ Form::open(array('url' => URL::route('setCalendars'), 'method' => 'Post')) }}
            @foreach($calendarList as $key => $value)
                {{-- {{ ($value['checked']) ? $class => '.green'}} --}}

                {{ Form::checkbox('calendar[]', $value['id'], false, ['id'=>"link$key"]) }}
                {{ Form::label("link$key", $value['title'], [ "class" =>($value['checked']) ? 'green':'']) }} <br \>
            @endforeach
            {{ Form::submit('follow!', ['name' => 'action']) }}
            {{ Form::submit('unfollow!',['name' => 'action']) }}
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
