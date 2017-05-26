<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AudioMessage extends Model
{
    protected $fillable = ['file_name', 'user_id'];

    /**
     *  An AudioMessage Belongs To a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
