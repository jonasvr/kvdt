@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>Add your emergency wake up contacts</h1>
            @if(isset($edit))
                {{ Form::open(array('url' => URL::route('editNumber'), 'method' => 'Post')) }}
                {{ Form::hidden('id',$edit->id)}}
            @else
                {{ Form::open(array('url' => URL::route('addNumber'), 'method' => 'Post')) }}
            @endif
            <div class="form-group">
                {{Form::label('name', 'Contact name')}}
                {{ Form::text('name',(isset($edit)?$edit->name:""), ['class' => 'form-control'])}}
            </div>
            <div class="form-group">
                {{Form::label('nr', 'Number')}}
                {{ Form::text('nr',(isset($edit)?$edit->nr:"+32"),["autocomplete"=>"off",'class' => 'form-control'])}}
            </div>
            {{ Form::submit('add!',['name' => 'action','class'=> 'btn btn-default']) }}
            {{ Form::close() }}
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    @include('contacts.buttons')
                </div>
            </div>
            <div class="row">
                <ul class="list-unstyled">
                @foreach($nrs as $key => $nr)
                    <div class="nr-info">
                        <li>
                            {{ $nr->name }}: {{ $nr->nr }}
                            <a href="{{ URL::route('deleteNumber', ['id'=>$nr->id]) }}"><span class="glyphicon glyphicon-remove"></span></a>
                            <a href="{{ URL::route('getEditNumber', ['id'=>$nr->id]) }}"><span class="glyphicon glyphicon-pencil"></span></a>
                        </li>
                    </div>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
