@extends('layouts.app')

@section('content')
    <welcome-screen :user="{{ Auth::user() }}"></welcome-screen>
@stop


