@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>add to your emergency mail list</h1>
            @if(isset($edit))
                {{ Form::open(array('url' => URL::route('editMail'), 'method' => 'Post')) }}
                {{ Form::hidden('id',$edit->id)}}
            @else
                {{ Form::open(array('url' => URL::route('addMail'), 'method' => 'Post')) }}
            @endif
            {{Form::label('name', 'contact name')}}
            {{ Form::text('name',(isset($edit)?$edit->name:""))}}
            {{Form::label('mail', 'mail')}}
            {{ Form::text('mail',(isset($edit)?$edit->mail:""),["autocomplete"=>"off"])}}
            @if(isset($edit))
                {{ Form::submit('edit',['name' => 'edit']) }}
            @else
                {{ Form::submit('add!',['name' => 'action']) }}
            @endif
            {{ Form::close() }}

            @foreach($mails as $key => $mail)
                <div class="nr-info">
                    <p>
                        {{ $mail->name }}: {{ $mail->mail }}
                        <a href="{{ URL::route('deleteMail', ['id'=>$mail->id]) }}"><span class="glyphicon glyphicon-remove"></span></a>
                        <a href="{{ URL::route('getEditMail', ['id'=>$mail->id]) }}"><span class="glyphicon glyphicon-pencil"></span></a>

                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
