<?php

namespace App\Services\ExternalCrmImport\Contracts;

interface ImportPaginationContract
{
    public function apiClient();

    /**
     * Returns pagination last pagination number
     *
     * @return int
     */
    public function products(): int;

    /**
     * Returns pagination last pagination number
     *
     * @return int
     */
    public function orders(): int;

    /**
     * Returns pagination last pagination number
     *
     * @return int
     */
    public function clients(): int;
}
