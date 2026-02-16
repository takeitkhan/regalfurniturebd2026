@extends('layouts.app')

@section('title', 'Dashboard')
@section('sub_title', 'a quick start template of blade for this software')
@section('content')
    @if (Route::has('login'))
        <div class="top-right links">
            @if (Auth::check())
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ url('/login') }}">Login</a>
                <a href="{{ url('/register') }}">Register</a>
            @endif
        </div>
    @endif
@endsection
