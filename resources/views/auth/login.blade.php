@extends('auth.layouts.auth')

@push('auth_content')

            <div class="card card-hidden">
                <div class="header text-center">{{ config('app.name') }}</div>
                <div class="text-center">Login</div>
                <div class="content">
                    <form role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
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
                        <div class="footer text-center">
                            <button type="submit" class="btn btn-fill btn-warning btn-wd">Login</button>
                        </div>
                    </form>
                    <hr>
                    <div class="text-center">
                        <h5>- Social Logins -</h5>
                        <a href="redirect/facebook" class="btn btn-fill btn-facebook">
                            <i class="fa fa-facebook"></i>Facebook
                        </a>
                        <a href="redirect/google" class="btn btn-fill btn-google">
                            <i class="fa fa-google"></i>Google
                        </a>
                        <a href="redirect/twitter" class="btn btn-fill btn-twitter">
                            <i class="fa fa-twitter"></i>Twitter
                        </a>
                        {{--<a href="redirect" class="btn btn-fill btn-linkedin">--}}
                            {{--<i class="fa fa-linkedin"></i>LinkedIn--}}
                        {{--</a>--}}
                    </div>
                </div>
            </div>
@endpush
