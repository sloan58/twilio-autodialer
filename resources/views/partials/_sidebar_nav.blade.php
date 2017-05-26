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
                    <li><a href="/profile/{{ Auth::user()->id }}">My Profile</a></li>
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
    </ul>
</div>