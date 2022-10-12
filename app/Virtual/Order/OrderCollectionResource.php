<?php

namespace App\Virtual\Order;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(title="Order collection resource")
 */
class OrderCollectionResource
{
    /**
     * @OA\Property(
     *     title="array",
     *     @OA\Items(ref="#/components/schemas/OrderResource")
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
