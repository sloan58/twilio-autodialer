<?php

namespace App;

use App\Models\Cdr;
use App\Models\Role;
use App\Models\BulkFile;
use App\Models\AudioMessage;
use App\Models\VerifiedPhoneNumber;
use Illuminate\Support\Facades\Session;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'twilio_sid', 'twilio_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     *  A User belongs to many Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     *  A User has many VerifiedPhoneNumbers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function verifiedPhoneNumbers()
    {
        return $this->belongsToMany(VerifiedPhoneNumber::class);
    }

    /**
     *  A User has many CDR's
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cdrs()
    {
        return $this->hasMany(Cdr::class);
    }

    /**
     *  A User Has Many Bulk Files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bulkFiles()
    {
        return $this->hasMany(BulkFile::class, 'created_by');
    }

    /**
     *  A User Has Many AudioMessages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function audioMessages()
    {
        return $this->hasMany(AudioMessage::class);
    }

    /**
     *  Decrypt the Twilio token when accessing
     *
     * @param $value
     * @return string
     */
    public function getTwilioTokenAttribute($value)
    {
        if ($value) {
            return decrypt($value);
        }
    }

    /**
     *  Encrypt the Twilio token when setting
     *
     * @param $value
     */
    public function setTwilioTokenAttribute($value)
    {
        $this->attributes['twilio_token'] =  encrypt($value);
    }

    /**
     *  Set the Impersonation User ID
     *
     * @param $id
     */
    public function setImpersonating($id)
    {
        Session::put('impersonate', $id);
    }

    /**
     * Stop impersonating the User
     */
    public function stopImpersonating()
    {
        Session::forget('impersonate');
    }

    /*
     *  Check if we are impersonating a User
     */
    public function isImpersonating()
    {
        return Session::has('impersonate');
    }
}
