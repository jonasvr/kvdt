<div class="panel panel-primary">
    <div class="panel-heading">
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
    </div>
</div>