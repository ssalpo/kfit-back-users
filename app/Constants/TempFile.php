<?php

namespace App\Constants;

interface TempFile
{
    const MAX_FILE_SIZE = 30240;

    const ALLOW_FILE_MIME_TYPES = [
        'jpg,jpeg,png,bmp,tiff',
        'doc,pdf,docx,zip'
    ];

    const FOLDER_USER_AVATAR = 'user/avatar';
    const FOLDER_CLIENT_AVATAR = 'client/avatar';

    const FOLDERS = [
        self::FOLDER_USER_AVATAR,
        self::FOLDER_CLIENT_AVATAR,
    ];
}
