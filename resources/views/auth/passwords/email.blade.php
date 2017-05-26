@extends('auth.layouts.auth')

<!-- Main Content -->
@push('auth_content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form class="form" role="form" method="POST" action="{{ url('/password/email') }}">
        {{ csrf_field() }}
        <div class="card card-hidden">
            <div class="header text-center">{{ config('app.name') }}</div>
            <div class="text-center">Password Reset</div>
            <div class="content">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label>E-Mail Address</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="footer text-center">
                    <button type="submit" class="btn btn-primary">
                        Send Password Reset Link
                    </button>
                </div>
            </div>
        </div>
    </form>

@endpush
