@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>Add to your emergency message</h1>
            @if(isset($edit))
                {{ Form::open(array('url' => URL::route('editMess'), 'method' => 'Post')) }}
                {{ Form::hidden('id',$edit->id)}}
            @else
                {{ Form::open(array('url' => URL::route('addMess'), 'method' => 'Post')) }}
            @endif
            {{Form::label('title', 'Subject')}} <br>
            {{ Form::text('title',(isset($edit)?$edit->title:""),["autocomplete"=>"off", 'class'=>'form-control'])}}
            <div class="counter">
                {{ Form::textarea('message',(isset($edit)?$edit->message:""))}}
            </div>
            @if(isset($edit))
                {{ Form::submit('edit',['name' => 'edit','class'=> 'btn btn-default']) }}
            @else
                {{ Form::submit('add!',['name' => 'action','class'=> 'btn btn-default']) }}
            @endif
            <div id="count">

            </div>
            {{ Form::close() }}

            @foreach($messages as $key => $message)
                <div class="nr-info">
                    <p class="message">
                        {{ $message->title }}
                        <a href="{{ URL::route('deleteMess', ['id'=>$message->id]) }}"><span class="glyphicon glyphicon-remove"></span></a>
                        <a href="{{ URL::route('getEditMess', ['id'=>$message->id]) }}"><span class="glyphicon glyphicon-pencil"></span></a>
                        <br \>{!! substr($message->message, 0, 100)!!}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection



@section('js')
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
    <script>

        $('textarea').ckeditor();
        // $('.textarea').ckeditor(); // if class is prefered.

        //count amount of characters
        // $('textarea').keyup(function(){
        //     console.log('in');
        //     $("#count").text($(this).val().length);
        // });

        // $( document ).ready(function() {
        //
        //     $.each( $('.message'), function (index,value){
        //         console.log(value);
        //     }
        // );
        // });
    </script>


@endsection
