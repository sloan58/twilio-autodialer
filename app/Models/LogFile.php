<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogFile extends Model
{
    protected $fillable = ['path', 'bulk_file_id'];

    /**
     *  A Log File belongs to a Bulk File
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bulkFile()
    {
        return $this->belongsTo(BulkFile::class);
    }

    public function getPathAttribute($value)
    {
        $segments = explode('/', $value);
        return end($segments);
    }
}
