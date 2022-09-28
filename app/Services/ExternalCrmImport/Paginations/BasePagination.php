<?php

namespace App\Services\ExternalCrmImport\Paginations;

use Illuminate\Support\Facades\Cache;

abstract class BasePagination
{
    /**
     * Gets the last page number from the service
     *
     * @param callable $apiRequest
     * @param string $method
     * @param int $basePage
     * @return int
     */
    protected function getLastPage(callable $apiRequest, string $method, int $basePage): int
    {
        $basePage = Cache::get(sprintf('lastCount:%s:%s', static::CACHE_KEY, $method), $basePage);

        $lastCount = 0;

        while (true) {
            $response = $apiRequest($basePage);

            if (is_array($response) && count($response)) {
                $basePage++;
                continue;
            }

            if (!is_array($response) || is_array($response) && !count($response)) {
                $lastCount = $basePage;
            }

            if($lastCount) {
                Cache::forever(sprintf('lastCount:%s:%s', static::CACHE_KEY, $method), $lastCount);
            }

            return $lastCount;
        }
    }
}
