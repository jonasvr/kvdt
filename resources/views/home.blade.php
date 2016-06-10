@extends('layouts.mylayout')

@section('content')
<div class="wrapper background-color">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                @if($alarms->count())
                @include('home.alarms')
                @endif

                @if($showers->count())
                    @include('home.shower')
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
