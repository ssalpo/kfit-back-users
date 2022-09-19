<?php

namespace App\Services\ExternalPlatform;

interface ImportContract
{
    public function apiClient();

    /**
     * List of products prepared from external service
     *
     * @return array
     */
    public function products(): array;

    /**
     * List of orders prepared from external service
     *
     * @return array
     */
    public function orders(): array;

    /**
     * List of clients prepared from external service
     *
     * @return array
     */
    public function clients(): array;
}
