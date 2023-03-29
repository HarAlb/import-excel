<?php

namespace App\Http\Api\Base\Constants;

final class FileUploadConstants
{
    // Size by mb
    const FILE_MAX_SIZE = 10;

    const DISK_NAME = 'files';

    const MIMES = [
        'xlsx',
        'docx',
        'doc',
        'csv'
    ];

    public static function getMimes(): string
    {
        return implode(',', self::MIMES);
    }
}
