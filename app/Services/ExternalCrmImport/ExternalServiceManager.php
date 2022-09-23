<?php

namespace App\Services\ExternalCrmImport;

class ExternalServiceManager
{
    const SERVICE_LIST = [
//        'autoweboffice',
        'gurucan'
    ];

    public static function make(string $service)
    {
        $service = ucfirst($service);
        $service = "App\Services\ExternalCrmImport\Importers\\{$service}Service";

        return new $service();
    }
}
