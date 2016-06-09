@if(Auth::user()->koten_id > 0)
    <div>
        <h1>KOT</h1>
    </div>
    @if($showers->count())
        @foreach($showers as $key => $shower )
            {{ $shower->device_id }} => {{ $shower->name }}
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