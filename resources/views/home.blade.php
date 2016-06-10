@extends('layouts.mylayout')

@section('content')
<div class="wrapper background-color">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="col-md-3 bgclr-red height-100prc">
            @if(count($agenda))
                @include('home.agenda')
            @endif
            </div>
            <div class="col-md-6">
                @include('home.clock')
                <div class="row">
                    <div class="fnt-size-70 bgclr-gray padding-40">
                        Kot van de Toekomst
                    </div>
                </div>
                @if($showers->count())
                    @include('home.shower')
                @endif
                @include('home.chair')
            </div>
            <div class="col-md-3 bgclr-blue ">
                @if($alarms->count())
                    @include('home.alarms')
                @endif
            </div>




        </div>
    </div>
</div>
@endsection
