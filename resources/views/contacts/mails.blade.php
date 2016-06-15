@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h1>Add to your emergency mail list</h1>
                </div>
                <div class="panel-body row">
                    @if(isset($edit))
                        {{ Form::open(array('url' => URL::route('editMail'), 'method' => 'Post')) }}
                        {{ Form::hidden('id',$edit->id)}}
                    @else
                        {{ Form::open(array('url' => URL::route('addMail'), 'method' => 'Post')) }}
                    @endif
                    <div class="col-md-10 col-md-offset-1 form-group">
                        {{Form::label('name', 'Contact name')}}
                        {{ Form::text('name',(isset($edit)?$edit->name:""),['class'=>'form-control'])}}
                    </div>
                    <div class="col-md-10 col-md-offset-1 form-group">
                        {{Form::label('mail', 'Mail')}}
                        {{ Form::text('mail',(isset($edit)?$edit->mail:""),["autocomplete"=>"off",'class'=>'form-control'])}}
                    </div>
                    <div class="col-md-4 col-md-offset-1 margin-tb-10">
                        @if(isset($edit))
                            {{ Form::submit('edit',['name' => 'edit','class'=> 'btn btn-default ']) }}
                        @else
                            {{ Form::submit('add!',['name' => 'action','class'=> 'btn btn-default']) }}
                        @endif
                    </div>

                    {{ Form::close() }}

                    <div class="row">
                        <ul class="col-md-10 col-md-offset-1 list-unstyled">
                            @foreach($mails as $key => $mail)
                                <div class="col-lg-4 col-md-6">
                                    <div class="panel panel-green">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-mobile fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <strong class="">{{ $mail->name }}</strong>
                                                    <p>{{ $mail->mail }}</p>

                                                    <a href="{{ URL::route('deleteNumber', ['id'=>$mail->id]) }}">
                                                        <span class="fa fa-remove clr-white"></span>

                                                    </a>
                                                    <a href="{{ URL::route('getEditNumber', ['id'=>$mail->id]) }}">
                                                        <span class="fa fa-pencil clr-white"></span>
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    @include('contacts.buttons')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
