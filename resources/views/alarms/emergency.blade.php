@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @if($info)
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h1>Edit the emergency contact &amp; message</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-1">
                            <h2>
                                Setted emergency
                                <a href="{{ URL::route('deleteEmerg', ['id'=>$alarm_id]) }}">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </h2>
                            {{--{{ dd($info) }}--}}
                            <p><span class="text-capitalize"><u>contact-name:</u></span> <br> {{ $info['name']  }}</p>
                            <p><span class="text-capitalize"><u>send-to: </u></span> <br>{{$info['contact'] }}</p>
                            <p><span class="text-capitalize"><u>subject: </u></span> <br>{{ $info['message']->title }}</p>
                            <p><span class="text-capitalize"><u>message: </u></span><br></p>{!! $info['message']->message !!}
                        </div>
                    </div>
                </div>
            @endif
            <div class="panel panel-primary">
                <div class="panel-heading">
            <h2>Choose a new emergency setting</h2>
                    </div>
            {{ Form::open(array('url' => URL::route('updateEmerg'), 'method' => 'Post')) }}
            <div class="row">
                <div class="col-md-offset-1 margin-25">
                {{  Form::label("type" , "email") }}
                {{  Form::radio('type', 'mail',['selected'=>'selected'])}}
                {{  Form::label("type" , "text message") }}
                {{  Form::radio('type', 'sms')}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" id="selectNumber">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                    @foreach($numbers as $key => $contact)
                        <p>
                            {{ Form::label('contact_id' , $contact->name. " : " . $contact->nr ) }}
                            {{Form::radio('contact_id', $contact->id)}}
                        </p>
                    @endforeach
                            </div>
                        </div>
                </div>
                <div class="col-md-6" id="selectMail">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                    @foreach($mails as $key => $contact)
                        <p>
                            {{ Form::label('contact_id' , $contact->name. " : " . $contact->mail ) }}
                            {{Form::radio('contact_id', $contact->id)}}
                        </p>
                    @endforeach
                            </div>
                        </div>
                </div>
                @foreach($messages as $key => $message)
                    <div class="col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                            <div class="{{ count($message->message)>140? 'mail':'sms' }}" >
                                <h4>{{ Form::label("message_id" , $message->title) }}
                                    {{Form::radio('message_id', $message->id)}}
                                </h4>
                                <p>
                                    {!! substr($message->message,0,100) !!}...
                                </p>
                            </div>
                        </div>
                    </div>
                    </div>
                @endforeach
            </div>
                <div class="row">
                    <div class="margin-25">
                        {{ Form::hidden('alarm_id',$alarm_id)}}
                        {{ Form::submit('update!', ['name' => 'action','class'=>'btn btn-default']) }}
                    </div>
                </div>
            </div>
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
        }).filter(':checked').trigger("change");
    </script>
@endsection