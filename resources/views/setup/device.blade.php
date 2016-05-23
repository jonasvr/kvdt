@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>add your device</h1>
            {{ Form::open(array('url' => URL::route('addDevice'), 'method' => 'Post')) }}
            {{ Form::text('device_id') }}
            {{ Form::submit('Submit!') }}
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
