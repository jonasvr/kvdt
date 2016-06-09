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

                @if(Auth::user()->koten_id > 0)
                    <div>
                       <h1>KOT</h1>
                    </div>
                    @if($showers->count())
                        @foreach($showers as $key => $shower )
                            {{ $shower->device_id }}
                        @endforeach
                    @endif
                    @if(count($applies))
                        @foreach($applies as $key => $apply)
                            {{ $apply['name'] }}
                            <a href="{{ URL::route('acceptApply', ['userApply_id'=>$apply['user_id'],'Apply_id'=>$apply['apply_id']]) }}">
                                <span class="glyphicon glyphicon-ok"></span>
                            </a>
                            <a href="{{ URL::route('removeApply', ['Apply_id'=>$apply['apply_id']]) }}">
                                <span class="glyphicon glyphicon-remove"></span>
                            </a>
                        @endforeach
                    @endif
                @else
                    {{ Form::open(array('url' => URL::route('addKot'), 'method' => 'Post','class'=>'')) }}
                    <div class="form-group">
                        {{ Form::Label('kot_id', 'Kot ID')}}
                        {{ Form::text('kot_id','',['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::Label('pass', 'In case of ownership, give password')}}
                        {{ Form::password('pass',['class' => 'form-control']) }}
                    </div>
                    {{ Form::submit('Submit!',['class'=>'btn btn-default','class'=>'btn btn-default']) }}
                    {{ Form::close() }}
                @endif

                <h1>Your devices</h1>
                @if(isset($devices))
                    @foreach($devices as $key => $device)
                        @if(!$device['device_type']='shower')
                            <p>{{$device->device_type}} =>  {{$device->device_id}}</p>
                        @endif
                    @endforeach
                @endif
                {{ Form::open(array('url' => URL::route('addDevice'), 'method' => 'Post','class'=>'')) }}
                <div class="form-group">
                    {{ Form::Label('device_id', 'Device ID')}}
                    {{ Form::text('device_id','',['class' => 'form-control']) }}
                </div>
                {{ Form::submit('Submit!',['class'=>'btn btn-default']) }}
                {{ Form::close() }}

                <h1>Wekker</h1>   {{--<button class="col-md-3">numbers</button>--}}
                @include('contacts.buttons')
            </div>
        </div>
    </div>
@endsection
