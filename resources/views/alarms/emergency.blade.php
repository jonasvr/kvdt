@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">


            <h1>Edit the emergency contact &amp; message</h1>

            @if($info)
                <h2>
                    Setted emergency
                    <a href="{{ URL::route('deleteEmerg', ['id'=>$alarm_id]) }}">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                </h2>
                {{--{{ dd($info) }}--}}
                <p><strong>contact-name:</strong> {{ $info['name']  }}</p>
               <p><strong>send-to: </strong>{{$info['contact'] }}</p>
                <p><strong>subject: </strong>{{ $info['message']->title }}</p>
                <p><strong>message: </strong></p>{!! $info['message']->message !!}


            @endif
            <h2>Choose a new emergency setting</h2>
            {{ Form::open(array('url' => URL::route('updateEmerg'), 'method' => 'Post')) }}
            <div class="row">
                {{  Form::label("type" , "email") }}
                {{  Form::radio('type', 'mail',['selected'=>'selected'])}}
                {{  Form::label("type" , "text message") }}
                {{  Form::radio('type', 'sms')}}

                <br><br><br>
                <div class="col-md-6">
                    @foreach($messages as $key => $message)
                        <div class="{{ count($message->message)>140? 'mail':'sms' }}" >
                            <h4>{{ Form::label("message_id" , $message->title) }}
                                {{Form::radio('message_id', $message->id)}}
                            </h4>
                            <p>
                                {!! substr($message->message,0,200) !!}...
                            </p>
                        </div>

                    @endforeach
                </div>
                <div class="col-md-6" id="selectNumber">
                    @foreach($numbers as $key => $contact)
                        <p>
                            {{ Form::label('contact_id' , $contact->name. " : " . $contact->nr ) }}
                            {{Form::radio('contact_id', $contact->id)}}
                        </p>
                    @endforeach
                </div>
                <div class="col-md-6" id="selectMail">

                    @foreach($mails as $key => $contact)
                        <p>
                            {{ Form::label('contact_id' , $contact->name. " : " . $contact->mail ) }}
                            {{Form::radio('contact_id', $contact->id)}}
                        </p>
                    @endforeach
                </div>

            </div>
            {{ Form::hidden('alarm_id',$alarm_id)}}
            {{ Form::submit('update!', ['name' => 'action']) }}
        </div>
        <div class="col-md-10 col-md-offset-1">
            @include('contacts.buttons')
        </div>
    </div>
</div>
@endsection
@section('js')
    <script type="text/javascript">
        $("input[name='type']").change(function(){
            console.log($(this).val());
            if ($(this).val()=='mail') {
                $("#selectMail").show();
                $("#selectNumber").hide();
            }else if($(this).val()=='sms')
            {
                $("#selectMail").hide();
                $("#selectNumber").show();
            }
        }).trigger("change");
    </script>
@endsection
<h2>Choose a new emergency setting</h2>
{{ Form::open(array('url' => URL::route('updateEmerg'), 'method' => 'Post')) }}
<div class="row">
    <div class="col-offset-1 col-md-4">
        {{  Form::label("type" , "email") }}
        {{  Form::radio('type', 'mail',['selected'=>'selected'])}}
        {{  Form::label("type" , "text message") }}
        {{  Form::radio('type', 'sms')}}
    </div>
    <br><br><br>
    <div class="col-md-12">
        @foreach($messages as $key => $message)
            <div class="{{ count($message->message)>140? 'mail':'sms' }}" >
                <div class="col-lg-6 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    {{Form::radio('message_id', $message->id)}}
                                    <i class="fa fa-envelope fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <strong class="">{{ $message->title }}</strong>
                                    <p>{!! substr($message->message,0,200) !!}...</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--<h4>{{ Form::label("message_id" , $message->title) }}--}}
                {{--{{Form::radio('message_id', $message->id)}}--}}
                {{--</h4>--}}
                {{--<p>--}}
                {{--{!! substr($message->message,0,200) !!}...--}}
                {{--</p>--}}
            </div>

        @endforeach
    </div>