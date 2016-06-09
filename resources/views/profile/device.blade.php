<h1>Your devices</h1>
@if(isset($devices))
    @foreach($devices as $key => $device)
        @if(!($device['device_type']=='shower'))
            <p>{{$device->device_type}} =>  {{$device->name}}</p>
        @endif
    @endforeach
@endif
{{ Form::open(array('url' => URL::route('addDevice'), 'method' => 'Post','class'=>'')) }}
<div class="form-group">
    {{ Form::Label('device_id', 'Device ID')}}
    {{ Form::text('device_id','',['class' => 'form-control']) }}
</div>
<div class="form-group">
    {{ Form::Label('name', 'Name')}}
    {{ Form::text('name','',['class' => 'form-control']) }}
</div>
{{ Form::submit('Submit!',['class'=>'btn btn-default']) }}
{{ Form::close() }}

<h1>Wekker</h1>   {{--<button class="col-md-3">numbers</button>--}}