<?php

namespace App\Services;

use App\User;
use App\Models\Cdr;
use Twilio\Rest\Client as Twilio;
use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\RestException;
use App\Models\VerifiedPhoneNumber;

class PlaceTwilioCallService
{
    /**
     * @var $call
     */
    protected $call;
    /**
     * @var null
     */
    private $bulkFileId;
    /**
     * @var
     */
    private $userId;

    /**
     * Create a new job instance.
     *
     * @param $call
     * @param $userId
     * @param null $bulkFileId
     */
    public function __construct($call, $userId, $bulkFileId = null)
    {
        $this->call = $call;
        $this->userId = $userId;
        $this->bulkFileId = $bulkFileId;
    }

    /**
     * Execute the job.
     *
     * @return boolean
     */
    public function call()
    {
        $cdr = new Cdr();
        $cdr->user_id = $this->userId;

        // If the record is not formatted correctly, bail.
        if(count($this->call) != 4) {
            Log::error("Incorrect format for call request.  Expected 5 parameters and got " . count($this->call), [$this->call]);
            if(is_numeric($this->call[0])) {
                $cdr->dialednumber = '+1' .  substr($this->call[0], -10);
            }
            $cdr->successful = false;
            $cdr->failurereason = "Incorrect format for call request.  Expected 5 parameters and got " . count($this->call);
            $cdr->save();
            return false;
        }

        $number = substr($this->call[0], -10);
        $e164 = '+1' . $number;
        $say = $this->call[1];
        $type = $this->call[2];
        $callerId = $this->call[3];


        $cdr->dialednumber = $e164;
        $cdr->callerid = $callerId;
        $cdr->message = $say;
        $cdr->bulk_file_id = $this->bulkFileId;

        /*
         * Check that the dialed number is actually a number
         */
        if(!is_numeric($number))
        {
            if(is_numeric($this->call[0])) {
                $cdr->dialednumber = $e164;
            }
            $cdr->successful = false;
            $cdr->failurereason = "The Dialed Number " . $number . " is not a number";
            $cdr->save();
            return false;
        }

        /*
         *  Check that the ANI is owned by the User (Twilio verified phone number)
         */
        $user = User::find($this->userId);
        if(! $user->verifiedPhoneNumbers->contains(VerifiedPhoneNumber::where('phone_number', $callerId)->pluck('id')->first())) {
            if(is_numeric($this->call[0])) {
                $cdr->dialednumber = $e164;
            }
            $cdr->successful = false;
            $cdr->failurereason = "The caller ID $callerId is not verified for the user $user->name";
            $cdr->save();
            return false;
        }

        /*
         * Check the call type and place the call
         */

        $twilio = new Twilio(
            User::find($this->userId)->twilio_sid,
            User::find($this->userId)->twilio_token
        );

        if(strtolower($type) == 'voice' || strtolower($type) == 'audio')
        {

            $say = 'http://twimlets.com/message?Message%5B0%5D=' . urlencode($say);
            $cdr->calltype = 'Phone Call';

            try {

                $twilio->account->calls->create($e164, $callerId, ['url' => $say]);

            } catch(RestException $e) {
                $cdr->successful = false;
                $cdr->failurereason = $e->getMessage();
                $cdr->save();
                return false;

            }

            $cdr->successful = true;
            $cdr->save();

            return true;


        } elseif(strtolower($type) == 'text') {

            $cdr->calltype = 'Text Message';

            try {

                $twilio->account->messages->create($e164, ['from' => $callerId, 'body' => $say]);

            } catch(RestException $e) {
                $cdr->successful = false;
                $cdr->failurereason = $e->getMessage();
                $cdr->save();
                return false;

            }

            $cdr->successful = true;
            $cdr->save();

            return true;

        } else {
            if(is_numeric($this->call[0])) {
                $cdr->dialednumber = $e164;
            }
            $cdr->successful = false;
            $cdr->failurereason = "The call type " . $type . " is invalid";
            $cdr->save();
            return false;

        }

    }
}
