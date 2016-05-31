@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">


            <h1>edit the emergency contact &amp; message</h1>

            @if($emergency)

            @endif

            {{ Form::open(array('url' => URL::route('updateEmerg'), 'method' => 'Post')) }}
            <div class="row">
                {{ Form::label("type" , "email") }}
                {{Form::radio('type', 'mail',['selected'=>'selected'])}}
                {{ Form::label("type" , "text message") }}
                {{Form::radio('type', 'sms')}}

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
{{-- {{ Form::open(array('url' => URL::route('updateAlarm'), 'method' => 'Post')) }}
@foreach($alarms as $key => $event)
    <div value="event">
        {{ Form::checkbox("event[$key]", $event['event_id'], FALSE, ['id'=>'link' . $key]) }}
        {{ Form::time("alarmTime[$key]",$event['alarmTime']) }}

        {{ Form::label("link$key" , $event['start'] . ' => ' .$event['summary']) }}
        <a href="{{ URL::route('deleteMess', ['id'=>$event->id]) }}"><span class="glyphicon glyphicon-cog"></span></a>
        <a href="{{ URL::route('deleteMess', ['id'=>$message->id]) }}"><span class="glyphicon glyphicon-remove"></span></a>
        {{  Form::hidden("alarmDate[$key]", $event['alarmDate'], ['class' => 'time'])}}
    </div>
@endforeach
{{ Form::submit('update!', ['name' => 'action']) }}
{{ Form::submit('remove!',['name' => 'action']) }}
{{ Form::close() }} --}}
