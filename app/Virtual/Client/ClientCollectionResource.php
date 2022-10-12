<?php

namespace App\Virtual\Client;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(title="Client collection resource")
 */
class ClientCollectionResource
{
    /**
     * @OA\Property(
     *     title="array",
     *     @OA\Items(ref="#/components/schemas/ClientResource")
     * )
     *
     * @var array
     */
    private $data;

    /**
     * @OA\Property(
     *     title="meta"
     * )
     *
     * @var object
     */
    private $meta;

    /**
     * @OA\Property(
     *     title="links"
     * )
     *
     * @var object
     */
    private $links;
}
