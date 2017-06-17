<div class="logo">
    <a href="/" class="logo-text">
        {{ config('app.name') }}
    </a>
</div>

<div class="sidebar-wrapper">
    <div class="user">
        <div class="photo">
            <img src="{{ Gravatar::src( Auth::user()->email ) }}" />
        </div>
        <div class="info">
            <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                {{ Auth::user()->name }}
                <b class="caret"></b>
            </a>
            <div class="collapse" id="collapseExample">
                <ul class="nav">
                    <li><a href="{{ route('users.edit', Auth::user()->id) }}">My Profile</a></li>
                </ul>
            </div>
        </div>
    </div>
    <ul class="nav">
        <li class="{{ in_array(\Route::current()->getName(), ['autodialer.index', 'autodialer.bulk.index', 'autodialer.bulk.show', 'autodialer.bulk.logfile', 'cdrs.index']) ? "active" : '' }}" >
            <a data-toggle="collapse" href="#autoDialerMenu" area-expanded="true" aria-expanded="false" class="collapsed">
                <i class="fa fa-phone-o"></i>
                <p>Auto Dialer
                    <b class="caret"></b>
                </p>
            </a>
            <div id="autoDialerMenu" class="{{ in_array(\Route::current()->getName(), ['autodialer.index', 'autodialer.bulk.index', 'autodialer.bulk.show', 'autodialer.bulk.logfile', 'cdrs.index']) ? "collapse in" : "collapse" }}">
                <ul class="nav">
                    <li><a href="/autodialer">Single Call</a></li>
                    <li><a href="/autodialer/bulk">Bulk Calls</a></li>
                    <li><a href="/autodialer/cdrs">Call Detail Records</a></li>
                </ul>
            </div>
        </li>
        <li class="">
            <a href="{{ route('audio-messages.index') }}">
                <i class="fa fa-volume-up-o"></i>
                <p>Audio Messages
                </p>
            </a>
        </li>
        @if(Auth::user()->hasRole('admin'))
        <li @if(in_array(Route::current()->getName(), ['users.index', 'users.edit'])) class="active" @endif>
            <a href="{{ route('users.index') }}">
                <i class="pe-7s-id"></i>
                <p>Users</p>
            </a>
        </li>
        @endif
        @if(Auth::user()->isImpersonating())
            <li class="fa-blink">
                <a href="{{ url('users/stop') }}">
                    <i class="fa fa-user-secret text-warning" aria-hidden="true"></i>
                    <p>Stop Impersonating</p>
                </a>

            </li>
        @endif
    </ul>
</div>