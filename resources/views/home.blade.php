@extends('layouts.app')

@section('content')
    <div class="row">
        @include('home.alarms')
        @include('home.clock')
        @include('home.agenda')
        @include('home.shower')
        @include('home.chair')
 </div>
@endsection
