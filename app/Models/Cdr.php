<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Cdr extends Model
{
    protected $fillable = ['dialednumber', 'callerid', 'calltype','message','successful', 'user_id', 'failurereason'];

    /**
     *  A CDR Belongs To a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *  A CDR Belongs To an Auto Dialer Bulk File
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function autoDialerBulkFile()
    {
        return $this->belongsTo(BulkFile::class, 'bulk_file_id');
    }
}
