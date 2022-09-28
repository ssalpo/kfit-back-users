<?php

namespace App\Services\ExternalCrmImport;

use App\Services\ExternalCrmImport\Contracts\ImportContract;
use App\Services\ExternalCrmImport\Contracts\ImportPaginationContract;

class ExternalServiceManager
{
    const SERVICE_LIST = [
        'autoweboffice',
        'gurucan'
    ];

    public static function make(string $service): ImportContract
    {
        $service = ucfirst($service);
        $service = "App\Services\ExternalCrmImport\Importers\\{$service}Service";

        return new $service();
    }

    public static function makePagination(string $service): ImportPaginationContract
    {
        $service = ucfirst($service);
        $service = "App\Services\ExternalCrmImport\Paginations\\{$service}PaginationService";

        return new $service();
    }
}
