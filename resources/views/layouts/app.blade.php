<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div id="fb-root"></div>
<div id="app">
    @if(Auth::guest())
        <div class="wrapper wrapper-full-page">
            <div class="full-page login-page" data-color="black" data-image="/img/full-screen-tech.jpg">
                @yield('content')
            </div>
        </div>
    @else
        <div class="wrapper">
            <div class="sidebar" data-color="black" data-image="/img/full-screen-tech.jpg">
                {{--Sidebar Navigation--}}
                @include('partials._sidebar_nav')
            </div>
            <div class="main-panel">
                {{--Top Naviation--}}
                @include('partials._top_nav')
                {{--Main Content--}}
                @yield('content')
            </div>
        </div>
    @endif
</div>

<!-- Scripts -->
<script src="{{ mix('/js/app.js') }}"></script>
@include('partials._notifications')
</body>
</html>