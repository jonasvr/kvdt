@extends('layouts.app')

@section('content')
    <div class="row margin-top-20">
        <div class="row">
            @include('home.alarms')
            @include('home.clock')
            @include('home.agenda')
        </div>
        <div class="row">
            <div class="changing">
                @include('home.shower')
                @include('home.chair')
            </div>
        </div>

 </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.6/socket.io.min.js"></script>
    <script src="/js/shower.js"></script>
@endsection
