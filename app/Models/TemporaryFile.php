<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{
    public $incrementing = false;

    const MAX_FILE_SIZE = 10240;

    const ALLOW_FILE_MIME_TYPES = [
        'jpg,jpeg,png,bmp,tiff',
        'doc,pdf,docx,zip'
    ];

    const FOLDER_AVATARS = 'avatars';

    const FOLDERS = [
        self::FOLDER_AVATARS
    ];
}
