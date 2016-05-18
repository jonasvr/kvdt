@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>select the calendars you want to follow</h1>
            {{ Form::open(array('url' => URL::route('setCalendars'), 'method' => 'Post')) }}
            @foreach($calendarList as $key => $value)
                {{ Form::checkbox('calendar[]', $key, FALSE, ['id'=>'link' . $value]) }} {{ Form::label('link' . $value, $value) }} <br \>
            @endforeach
            {{ Form::submit('Submit!') }}
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
