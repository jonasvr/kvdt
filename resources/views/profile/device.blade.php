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
        @if($devices > 0)
            <div class="panel-footer">
                <div class="row">
                    @include('home.chair')
                </div>
            </div>
        @endif
    </div>
</div>
