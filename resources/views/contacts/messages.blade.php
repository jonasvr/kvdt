@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h1>Add to your emergency message</h1>
                </div>
                <div class="panel-body row">
                    @if(isset($edit))
                        {{ Form::open(array('url' => URL::route('editMess'), 'method' => 'Post')) }}
                        {{ Form::hidden('id',$edit->id)}}
                    @else
                        {{ Form::open(array('url' => URL::route('addMess'), 'method' => 'Post')) }}
                    @endif
                    <div class="col-md-10 col-md-offset-1 form-group">
                    {{Form::label('title', 'Subject')}}
                    {{ Form::text('title',(isset($edit)?$edit->title:""),["autocomplete"=>"off", 'class'=>'form-control'])}}
                    </div>
                    <div class="col-md-10 col-md-offset-1 form-group">
                        {{ Form::textarea('message',(isset($edit)?$edit->message:""))}}
                    </div>
                    <div class="col-md-4 col-md-offset-1 margin-tb-10">
                        @if(isset($edit))
                            {{ Form::submit('edit',['name' => 'edit','class'=> 'btn btn-default']) }}
                        @else
                            {{ Form::submit('add!',['name' => 'action','class'=> 'btn btn-default']) }}
                        @endif
                    </div>
                    {{ Form::close() }}
                        <div class="row">
                            <ul class="col-md-10 col-md-offset-1 list-unstyled">
                                @foreach($messages as $key => $message)
                                    <div class="col-lg-6 col-md-6">
                                        <div class="panel panel-green">
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <i class="fa fa-envelope fa-5x"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-right">
                                                        <strong class="">{{ $message->title }}</strong>
                                                        <p>{!! substr($message->message, 0, 100)!!}</p>

                                                        <a href="{{ URL::route('deleteNumber', ['id'=>$message->id]) }}">
                                                            <span class="fa fa-remove clr-white"></span>

                                                        </a>
                                                        <a href="{{ URL::route('getEditNumber', ['id'=>$message->id]) }}">
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
            @include('contacts.buttons')
            </div>
        </div>
    </div>
</div>
@endsection



@section('js')
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
    <script>
        $('textarea').ckeditor();
    </script>


@endsection
