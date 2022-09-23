<?php

namespace App\Services\ExternalCrmImport;

class ExternalServiceManager
{
    const SERVICE_LIST = [
        'autoweboffice',
        'gurucan'
    ];

    public static function make(string $service)
    {
        $service = sprintf('%Service', ucfirst($service));

        return new ${$service}();
    }
}
