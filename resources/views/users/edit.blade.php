@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div>
                        <div class="card card-user">
                            <div class="image" >
                                <img src="/img/full-screen-tech.jpg" alt="...">
                            </div>
                            <div class="content">
                                <div class="author">
                                    <a href="#">
                                        <img class="avatar border-gray" src="{{ Gravatar::src( $user->email ) }}" alt="...">

                                        <h4 class="title">
                                            {{ Auth::user()->name }}<br>
                                        </h4>
                                    </a>
                                </div>
                                    {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PUT'] ) !!}
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        {!! Form::label('name','Name') !!}
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        {!! Form::label('email','Email') !!}
                                        {!! Form::email('email', null, ['class' => 'form-control', 'disabled']) !!}
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('twilio_sid') ? ' has-error' : '' }}">
                                        {!! Form::label('twilio_sid','Twilio SID') !!}
                                        {!! Form::text('twilio_sid', null, ['class' => 'form-control']) !!}
                                        @if ($errors->has('twilio_sid'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('twilio_sid') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('twilio_token') ? ' has-error' : '' }}">
                                        <label for="twilio_token">Twilio Token</label>
                                            <input id="password" type="password" class="form-control" name="twilio_token" type="password" value="{{$user->twilio_token}}" id="twilio_token">
                                        @if ($errors->has('twilio_token'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('twilio_token') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    @if(Auth::user()->verifiedPhoneNumbers->count())
                                    <div class="form-group">
                                        <label>Twilio Verified Phone Numbers</label>
                                        <ul class="list-group">
                                            @foreach(Auth::user()->verifiedPhoneNumbers as $verifiedPhoneNumber)
                                            <li class="list-group-item">{{ $verifiedPhoneNumber->phone_number }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    {!! Form::submit('Submit', ['class' => 'btn btn-primary btn-fill form-control']) !!}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop