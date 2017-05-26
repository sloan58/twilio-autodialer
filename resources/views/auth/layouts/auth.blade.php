@extends('layouts.app')

@section('content')
    <nav class="navbar navbar-transparent navbar-absolute">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">{{ config('app.name') }}</a>
            </div>
            <div class="collapse navbar-collapse">

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="{{ url('/login') }}">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/register') }}">
                            Register
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/password/reset') }}">
                            Password Reset
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="wrapper wrapper-full-page">
        <div class="full-page login-page" data-color="black" data-image="../../assets/img/full-screen-image-1.jpg">
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-md-offset-3 col-sm-offset-3">
                            @stack('auth_content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
