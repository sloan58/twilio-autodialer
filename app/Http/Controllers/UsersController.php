<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Twilio\Rest\Client as Twilio;
use Twilio\Exceptions\RestException;
use App\Models\VerifiedPhoneNumber;

class UsersController extends Controller
{
    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(User $user)
    {
        return view('users.profile', compact('user'));
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
}
