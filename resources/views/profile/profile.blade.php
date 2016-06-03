@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h1>Your Profile</h1>
                {{ Form::open(array('url' => URL::route('updateProfile'), 'method' => 'Post', 'class'=>'')) }}

                    <div class="form-group">
                        {{ Form::Label('name', 'name')}}
                        {{ Form::Text('name',Auth::user()->name,['class' => 'form-control','readonly' => 'true'] ) }}
                    </div>
                    <div class="form-group">
                        {{ Form::Label('email', 'email')}}
                        {{ Form::Text('email',Auth::user()->email,['class' => 'form-control','readonly' => 'true'] ) }}
                    </div>
                    <div class="form-group">
                        {{ Form::Label('mailAlias', 'email alias')}}
                        {{ Form::Text('mailAlias',Auth::user()->mailAlias,['class' => 'form-control'] ) }}
                    </div>
                    {{ Form::submit('update', ['class'=>'btn btn-default']) }}
                {{ Form::close() }}

                @if(isset($kot))
                    <div>
                       <h1>KOT</h1>
                    </div>
                @else
                    {{ Form::open(array('url' => URL::route('addKot'), 'method' => 'Post','class'=>'')) }}
                    <div class="form-group">
                        {{ Form::Label('kot_id', 'Kot ID')}}
                        {{ Form::text('kot_id','',['class' => 'form-control']) }}
                    </div>
                    {{ Form::submit('Submit!',['class'=>'btn btn-default']) }}

                    {{ Form::close() }}
                @endif

                <h1>Your devices</h1>
                @if(isset($devices))
                    @foreach($devices as $key => $device)
                        <p>{{$device->device_type}} =>  {{$device->device_id}}</p>
                    @endforeach
                @endif
                {{ Form::open(array('url' => URL::route('addDevice'), 'method' => 'Post','class'=>'')) }}
                <div class="form-group">
                    {{ Form::Label('device_id', 'Device ID')}}
                    {{ Form::text('device_id','',['class' => 'form-control']) }}
                </div>
                {{ Form::submit('Submit!',['class'=>'btn btn-default']) }}
                {{ Form::close() }}
            </div>

        </div>
    </div>
@endsection
