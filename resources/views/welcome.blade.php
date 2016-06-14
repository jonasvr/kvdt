@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/welcom.css">
@endsection
@section('content')
<div class="wrapper background-color">

    <div class="col-lg-offset-2 col-lg-8 margin-tb-20">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-2">
                        <i class="fa fa-google fa-5x"></i>
                    </div>
                    <div class="col-xs-10 text-right clr-white">
                        <div class="huge">
                            Welcom @ <br>
                            Het Kot van de Toekomst
                            <a href="{{ route('social.login', ['google']) }}" class="btn btn-default">
                                <div class=""><i class="fa fa-user fa-1x">
                                    </i> login</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        {{--<div class="title-titlepage">--}}
            {{--<h1>--}}
                {{--Welcom @ <br>--}}
                {{--Het Kot van de Toekomst--}}
            {{--</h1>--}}
            {{--<a href="{{ route('social.login', ['google']) }}"><div class="loginbutton">login</div></a>--}}

        {{--</div>--}}

</div>
@endsection
