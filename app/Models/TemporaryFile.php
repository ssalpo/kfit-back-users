<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id', 'user_filename'
    ];
}
