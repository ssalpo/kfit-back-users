<?php

namespace App\Services\ExternalCrmImport\Contracts;

interface ImportContract
{
    public function apiClient();

    /**
     * List of products prepared from external service
     *
     * @param int $page
     * @return array
     */
    public function products(int $page): array;

    /**
     * List of orders prepared from external service
     *
     * @param int $page
     * @return array
     */
    public function orders(int $page): array;

    /**
     * List of clients prepared from external service
     *
     * @param int $page
     * @return array
     */
    public function clients(int $page): array;
}
