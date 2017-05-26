<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BulkFile extends Model
{
    protected $fillable = ['file_name', 'status', 'processed_file_name', 'created_by'];

    /**
     *  A BulkFile Belongs To a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    /*
     *  An Auto Dialer Bulk File Has Many Cdr's
     */
    public function cdrs()
    {
        return $this->hasMany(Cdr::class);
    }

    /**
     *  A Bulk File Has Many Log Files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logFiles()
    {
        return $this->hasMany(LogFile::class);
    }
}
