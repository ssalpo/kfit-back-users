<?php

namespace App\Services\ExternalCrmImport\Paginations;

use App\Services\ExternalCrmImport\Contracts\ImportPaginationContract;
use App\Vendor\Autoweboffice\AwoApi;

class AutowebofficePaginationService extends BasePagination implements ImportPaginationContract
{
    const PAGE_SIZE = 1000;

    const BASE_COUNTS = ['products' => 2, 'orders' => 273, 'clients' => 110];

    const CACHE_KEY = 'autoweboffice';

    public function apiClient(): AwoApi
    {
        return new AwoApi(config('services.autoweboffice'));
    }

    public function products(): int
    {
        return $this->getLastPage(
            function (int $page) {
                return $this->apiClient()
                    ->product()
                    ->page($page)
                    ->pageSize(self::PAGE_SIZE)
                    ->getAll();
            },
            'products',
            self::BASE_COUNTS['products']
        );
    }

    public function orders(): int
    {
        return $this->getLastPage(
            function (int $page) {
                return $this->apiClient()
                    ->invoice()
                    ->page($page)
                    ->pageSize(self::PAGE_SIZE)
                    ->getAll();
            },
            'orders',
            self::BASE_COUNTS['orders']
        );
    }

    public function clients(): int
    {
        return $this->getLastPage(
            function (int $page) {
                return $this->apiClient()
                    ->contact()
                    ->page($page)
                    ->pageSize(self::PAGE_SIZE)
                    ->getAll();
            },
            'clients',
            self::BASE_COUNTS['clients']
        );
    }
}
