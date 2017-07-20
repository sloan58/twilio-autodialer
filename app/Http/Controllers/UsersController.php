<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Twilio\Rest\Client as Twilio;
use Twilio\Exceptions\RestException;
use App\Models\VerifiedPhoneNumber;

class UsersController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['role:admin'])->except([
            'edit', 'update', 'stopImpersonate'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Users vuetable-2 AJAX Request
        if (request()->ajax()) {

            $query = User::orderByVueTable();

            if ($request->exists('filter')) {
                $query->where(function ($q) use ($request) {
                    $value = "%{$request->filter}%";
                    $q->where('name', 'like', $value)
                        ->orWhere('email', 'like', $value);
                });
            }
            return response()->json(
                $query->paginate(10)
            );
        }

        return view('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if(\Auth::user()->id == $user->id || \Auth::user()->hasRole('admin')) {
            return view('users.edit', compact('user'));
        } else {
            abort(403);
        }
    }

    public function update(Request $request, User $user)
    {

        // Validate the form input
        $this->validate($request, [
            'name' => 'required|max:255',
            'twilio_sid' => 'required|max:255',
            'twilio_token' => 'required|max:255',
        ]);

        // Get the submitted Twilio credentials
        $twilio_sid = $request->input('twilio_sid');
        $twilio_token = $request->input('twilio_token');

        // Confirm the credentials are good.
        $client = new Twilio($twilio_sid, $twilio_token);

        try {
            $client->getAccount()->fetch();
        } catch (RestException $e) {
            return redirect()->back()->with('danger', 'Sorry, your Twilio account details appear to be invalid.  Please try again.');
        }

        // Update the User
        $user->update($request->all());

        // Update the Users verified Twilio Phone Numbers
        $verifiedPhoneNumbers = [];
        foreach ($client->incomingPhoneNumbers->read() as $number) {
            $verifiedPhoneNumber = VerifiedPhoneNumber::firstOrCreate([
                'phone_number' => $number->phoneNumber
            ]);
            $verifiedPhoneNumbers[] = $verifiedPhoneNumber->id;
        }
        $user->verifiedPhoneNumbers()->sync($verifiedPhoneNumbers);

        return redirect()->back()->with('success', 'Your profile has been updated!');
    }

    /**
     *  Impersonate a User
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function impersonate(User $user)
    {
        // Guard against administrator impersonate
        if(\Auth::user()->hasRole('admin'))
        {
            \Auth::user()->setImpersonating($user->id);
            return redirect('/')->with('success', 'You are now impersonating ' . $user->name . '!');

        } else {
            return redirect()->back()->with('danger', 'You are not authorized to impersonate other users!');
        }
    }

    /**
     *  Stop impersonating a User
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stopImpersonate()
    {
        \Auth::user()->stopImpersonating();

        return redirect('/')->with('success', 'You are no longer impersonating, ' . \Auth::user()->name . '!');
    }

    public function show()
    {
        
    }
}
