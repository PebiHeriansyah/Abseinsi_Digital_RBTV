<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAbsensi extends Model
{
    protected $fillable = [
        'nik',
        'status',
        'keterangan'
    ];
}