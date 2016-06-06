@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>Add to your emergency mail list</h1>
            @if(isset($edit))
                {{ Form::open(array('url' => URL::route('editMail'), 'method' => 'Post')) }}
                {{ Form::hidden('id',$edit->id)}}
            @else
                {{ Form::open(array('url' => URL::route('addMail'), 'method' => 'Post')) }}
            @endif
            <div class="form-group">
                {{Form::label('name', 'contact name')}}
                {{ Form::text('name',(isset($edit)?$edit->name:""),['class'=>'form-control'])}}
            </div>
            <div class="form-group">
                {{Form::label('mail', 'mail')}}
                {{ Form::text('mail',(isset($edit)?$edit->mail:""),["autocomplete"=>"off",'class'=>'form-control'])}}
            </div>
        @if(isset($edit))
                {{ Form::submit('edit',['name' => 'edit','class'=> 'btn btn-default']) }}
            @else
                {{ Form::submit('add!',['name' => 'action','class'=> 'btn btn-default']) }}
            @endif
            {{ Form::close() }}
            <ul class="list-unstyled">
            @foreach($mails as $key => $mail)
                {{--<div class="nr-info">--}}
                    <li>
                        {{ $mail->name }}: {{ $mail->mail }}
                        <a href="{{ URL::route('deleteMail', ['id'=>$mail->id]) }}"><span class="glyphicon glyphicon-remove"></span></a>
                        <a href="{{ URL::route('getEditMail', ['id'=>$mail->id]) }}"><span class="glyphicon glyphicon-pencil"></span></a>
                    </li>
                {{--</div>--}}
            @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
