@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>Select the calendars you want to follow</h1>
            {{ Form::open(array('url' => URL::route('setCalendars'), 'method' => 'Post')) }}
            @foreach($calendarList as $key => $value)
                {{-- {{ ($value['checked']) ? $class => '.green'}} --}}
                @if(!$value['follow'] && $key!='following')
                {{ Form::checkbox('calendar[]', $value['id'], false, ['id'=>"link$key"]) }}
                {{ Form::label("link$key", $value['title'], [ "class" =>($value['follow']) ? 'green':'']) }} <br \>
                @endif
            @endforeach
            {{ Form::submit('follow!', ['name' => 'action']) }}
            {{ Form::close() }}
            @if($calendarList['following'])
                <h1>The calendars you follow</h1>
                {{ Form::open(array('url' => URL::route('setCalendars'), 'method' => 'Post')) }}
                @foreach($calendarList as $key => $value)
                    {{-- {{ ($value['checked']) ? $class => '.green'}} --}}
                    @if($value['follow'])
                        {{ Form::checkbox('calendar[]', $value['id'], false, ['id'=>"link$key"]) }}
                        {{ Form::label("link$key", $value['title'], [ "class" =>($value['follow']) ? 'green':'']) }} <br \>
                    @endif
                @endforeach
                {{ Form::submit('unfollow!', ['name' => 'action']) }}
                {{ Form::close() }}
            @endif

        </div>
    </div>
</div>
@endsection
