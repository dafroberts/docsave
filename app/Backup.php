<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $fillable = [
        'filename',
        'file_count',
        'file_size',
    ];

    protected $appends = [
        'filesize_mb',
    ];

    public function getFilesizeMbAttribute()
    {
        return number_format($this->file_size / 1048576);
    }
}