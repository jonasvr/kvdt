@extends('layouts.mylayout')

@section('content')
<div class="wrapper background-color">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="col-md-3 bgclr-red ">
            @if(count($agenda))
                @include('home.agenda')
            @endif
            </div>
            <div class="col-md-6">
                @include('home.clock')
                @if($showers->count())
                    @include('home.shower')
                @endif
            </div>
            <div class="col-md-3 bgclr-red ">
                @if($alarms->count())
                    @include('home.alarms')
                @endif
            </div>




        </div>
    </div>
</div>
@endsection
