@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/welcom.css">
@endsection
@section('content')
<div class="wrapper background-color">
        <div class="title-titlepage">
            <h1>
                Welcom @ <br>
                Het Kot van de Toekomst
            </h1>
            <a href="{{ route('social.login', ['google']) }}"><div class="loginbutton">login</div></a>

        </div>

</div>
@endsection
