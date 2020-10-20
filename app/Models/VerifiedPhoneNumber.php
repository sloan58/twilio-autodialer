<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class VerifiedPhoneNumber extends Model
{
    protected $fillable = ['phone_number', 'friendly_name'];

    /**
     *  A Verified Caller ID belongs to a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
