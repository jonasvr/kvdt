@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                @include('profile.user')
                @include('profile.kot')
                @include('profile.device')
                @include('contacts.buttons')
            </div>
        </div>
    </div>
@endsection
