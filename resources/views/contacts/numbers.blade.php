@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>add your emergency wake up contacts</h1>
            @if(isset($edit))
                {{ Form::open(array('url' => URL::route('editNumber'), 'method' => 'Post')) }}
                {{ Form::hidden('id',$edit->id)}}
            @else
                {{ Form::open(array('url' => URL::route('addNumber'), 'method' => 'Post')) }}
            @endif
            {{Form::label('name', 'contact name')}}
            {{ Form::text('name',(isset($edit)?$edit->name:""))}}
            {{Form::label('nr', 'nr')}}
            {{ Form::text('nr',(isset($edit)?$edit->nr:"+32"),["autocomplete"=>"off"])}}
            {{ Form::submit('add!',['name' => 'action']) }}
            {{ Form::close() }}

            @foreach($nrs as $key => $nr)
                <div class="nr-info">
                    <p>
                        {{ $nr->name }}: {{ $nr->nr }}
                        <a href="{{ URL::route('deleteNumber', ['id'=>$nr->id]) }}"><span class="glyphicon glyphicon-remove"></span></a>
                        <a href="{{ URL::route('getEditNumber', ['id'=>$nr->id]) }}"><span class="glyphicon glyphicon-pencil"></span></a>

                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
