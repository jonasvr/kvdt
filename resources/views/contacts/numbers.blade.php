@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h1>Add your emergency wake up contacts</h1>
                </div>
                <div class="panel-body row">

                    @if(isset($edit))
                        {{ Form::open(array('url' => URL::route('editNumber'), 'method' => 'Post')) }}
                        {{ Form::hidden('id',$edit->id)}}
                    @else
                        {{ Form::open(array('url' => URL::route('addNumber'), 'method' => 'Post')) }}
                    @endif
                    <div class=" col-md-10 col-md-offset-1 form-group">
                        {{Form::label('name', 'Contact name')}}
                        {{ Form::text('name',(isset($edit)?$edit->name:""), ['class' => 'form-control'])}}
                    </div>
                    <div class="col-md-10 col-md-offset-1 form-group">
                        {{Form::label('nr', 'Number')}}
                        {{ Form::text('nr',(isset($edit)?$edit->nr:"+32"),["autocomplete"=>"off",'class' => 'form-control'])}}
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
                            @foreach($nrs as $key => $nr)
                            <div class="col-lg-3 col-md-6">
                                <div class="panel panel-green">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-mobile fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <strong class="">{{ $nr->name }}</strong>
                                                <p>{{ $nr->nr }}</p>

                                                    <a href="{{ URL::route('deleteNumber', ['id'=>$nr->id]) }}">
                                                        <span class="fa fa-remove clr-white"></span>

                                                    </a>
                                                    <a href="{{ URL::route('getEditNumber', ['id'=>$nr->id]) }}">
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
