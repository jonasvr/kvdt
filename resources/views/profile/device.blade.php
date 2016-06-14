<div class="panel panel-primary">
    <div class="panel-heading"><h1>Your devices</h1>
        {{ Form::open(array('url' => URL::route('addDevice'), 'method' => 'Post','class'=>'')) }}
        <div class="form-group">
            {{ Form::Label('device_id', 'Device ID')}}
            {{ Form::text('device_id','',['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            {{ Form::Label('name', 'Name')}}
            {{ Form::text('name','',['class' => 'form-control']) }}
        </div>
        <div class="form-group">
            {{ Form::submit('Submit!',['class'=>'btn btn-default']) }}
        </div>
        {{ Form::close() }}

        @if(count($devices) > 0)
            <div class="panel-footer">
                <div class="row">
                @foreach($devices as $key => $device)
                        @if(!($device['device_type']=='shower'))
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-yellow">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-offset-2 col-xs-10 text-right">
                                            <div class="huge text-capitalize">{{$device->device_type}}</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#">
                                    <div class="panel-footer">
                                        <span class="pull-left">{{$device->name}}</span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endif



                    {{--@if(!($device['device_type']=='shower'))--}}
                        {{--<p>{{$device->device_type}} =>  {{$device->name}}</p>--}}
                    {{--@endif--}}
                @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
