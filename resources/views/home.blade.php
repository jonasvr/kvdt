@extends('layouts.app')

@section('content')
    <div class="row margin-top-20">
        <div class="row">
            @include('home.alarms')
            @include('home.clock')
            @include('home.agenda')
        </div>
        <div class="row">
            @include('home.shower')
            @include('home.chair')
        </div>

 </div>
@endsection
