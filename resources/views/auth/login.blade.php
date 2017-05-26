@extends('auth.layouts.auth')

@push('auth_content')

        <form role="form" method="POST" action="{{ url('/login') }}">
          {{ csrf_field() }}
            <div class="card card-hidden">
                <div class="header text-center">{{ config('app.name') }}</div>
                <div class="text-center">Login</div>
                <div class="content">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label>Email Address</label>
                        <input id="email" type="email" placeholder="Enter Email" class="form-control" name="email" value="{{ old('name') }}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label>Password</label>
                        <input type="password" placeholder="Password" name="password" class="form-control">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" name="remember"> Remember Me
                        </label>
                    </div>
                </div>
                <div class="footer text-center">
                    <button type="submit" class="btn btn-fill btn-warning btn-wd">Login</button>
                    <a href="redirect" class="btn btn-info btn-fill btn-facebook"><i class="fa fa-facebook"></i>Login with Facebook</a>
                </div>
            </div>
        </form>

@endpush
