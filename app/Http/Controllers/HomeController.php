<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        if(!$user->twilio_sid || !$user->twilio_token) {
            return redirect()->route('users.profile', ['user' => $user])->with('danger', 'You must enter your Twilio information before using the Autodialer!');
        }
        return view('home');
    }
}
