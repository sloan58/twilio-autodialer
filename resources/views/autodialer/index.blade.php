@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="card">
                        <div class="header">
                            <nav class="navbar navbar-default">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="#">Place a single outbound voice call or text</a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <div class="content">
                            {!! Form::open( ['route' => 'autodialer.store', 'id' => 'app'] ) !!}
                            <div class="form-group{{ $errors->has('caller_id') ? ' has-error' : '' }}">
                                {!! Form::label('Caller ID','Your Caller ID') !!}
                                {!! Form::select('caller_id', $verifiedPhoneNumbers, null, ['class' => 'selectpicker form-control', 'data-style' => 'btn-default btn-block']) !!}
                                @if ($errors->has('caller_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('caller_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                                {!! Form::label('Phone Number','Phone Number to Dial (NANP Only)') !!}
                                {!! Form::text('number', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('number') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                {!! Form::label('Call Type','Call Type') !!}
                                @if(\Auth::user()->audioMessages()->count())
                                    {!! Form::select('type', ['voice' => 'Voice', 'text' => 'Text Message', 'audio' => 'Audio File'], 'S', ['v-model' => 'selected', 'class' => 'selectpicker form-control', 'data-style' => 'btn-default btn-block']) !!}
                                @else
                                    {!! Form::select('type', ['voice' => 'Voice', 'text' => 'Text Message'], 'S', ['v-model' => 'selected', 'class' => 'selectpicker form-control', 'data-style' => 'btn-default btn-block']) !!}
                                @endif
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('say') ? ' has-error' : '' }}" v-if="selected === 'audio'">
                                {!! Form::label('audio_file','Audio Messages') !!}
                                {!! Form::select('say', $audioMessages, 'S', ['class' => 'selectpicker form-control', 'data-style' => 'btn-default btn-block']) !!}
                                @if ($errors->has('say'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('say') }}</strong>
                                            </span>
                                @endif
                                {!! Form::hidden('audioMessage', true) !!}
                            </div>
                            <div class="form-group{{ $errors->has('say') ? ' has-error' : '' }}" v-else>
                                {!! Form::label('What Should We Say?','What would you like to say?') !!}
                                {!! Form::textarea('say', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('say'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('say') }}</strong>
                                            </span>
                                @endif
                            </div>
                            {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-fill form-control']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <!-- Include Modal scripts -->
    @stack('modal_scripts')
@stop