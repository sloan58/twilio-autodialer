<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserTwilioAccountIsSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::user()->hasRole('admin')) {
            return $next($request);
        }

        if (!\Auth::user()->twilio_sid || !\Auth::user()->twilio_token) {
            return redirect()->route('users.edit', ['user' => \Auth::user()])->with('danger', 'You must enter your Twilio information before you can use the Auto Dialer!');
        }
        return $next($request);
    }
}
