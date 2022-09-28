<?php

namespace App\Services\ExternalCrmImport\Paginations;

use App\Services\ExternalCrmImport\Contracts\ImportPaginationContract;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GurucanPaginationService extends BasePagination implements ImportPaginationContract
{
    const BASE_COUNTS = ['products' => 1, 'orders' => 1080, 'clients' => 1080];

    const CACHE_KEY = 'gurucan';

    public function apiClient(): PendingRequest
    {
        return Http::withHeaders(['Gc-api-key' => config('services.gurucan.key')])
            ->baseUrl(sprintf("https://%s.gurucan.com/api/admin/", config('services.gurucan.subdomain')));
    }

    public function products(): int
    {
        return self::BASE_COUNTS['products'];
    }

    public function orders(): int
    {
        return $this->getLastPage(
            function (int $page) {
                return $this->apiClient()
                    ->get('users', ['page' => $page])->json('data', '');
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
                    ->get('users', ['page' => $page])->json('data', '');
            },
            'clients',
            self::BASE_COUNTS['clients']
        );
    }
}
