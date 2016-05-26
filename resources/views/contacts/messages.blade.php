@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>add to your emergency message</h1>
            @if(isset($edit))
                {{ Form::open(array('url' => URL::route('editMess'), 'method' => 'Post')) }}
                {{ Form::hidden('id',$edit->id)}}
            @else
                {{ Form::open(array('url' => URL::route('addMess'), 'method' => 'Post')) }}
            @endif
            {{Form::label('title', 'subject')}}
            {{ Form::text('title',(isset($edit)?$edit->title:""),["autocomplete"=>"off"])}}
            {{ Form::textarea('message',(isset($edit)?$edit->message:""))}}
            @if(isset($edit))
                {{ Form::submit('edit',['name' => 'edit']) }}
            @else
                {{ Form::submit('add!',['name' => 'action']) }}
            @endif
            {{ Form::close() }}

            @foreach($messages as $key => $message)
                <div class="nr-info">
                    <p>
                        {{ $message->title }}
                        <a href="{{ URL::route('deleteMess', ['id'=>$message->id]) }}"><span class="glyphicon glyphicon-remove"></span></a>
                        <a href="{{ URL::route('getEditMess', ['id'=>$message->id]) }}"><span class="glyphicon glyphicon-pencil"></span></a>
                        <br \>{!! substr($message->message, 0, 100) !!}
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
    </script>
@endsection
