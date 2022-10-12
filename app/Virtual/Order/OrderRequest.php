<?php

namespace App\Virtual\Order;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Order store request"
 * )
 */
class OrderRequest
{
    /**
     * @OA\Property(
     *     title="client_id"
     * )
     *
     * @var int
     */
    private $client_id;

    /**
     * @OA\Property(
     *     title="product_id"
     * )
     *
     * @var int
     */
    private $product_id;

    /**
     * @OA\Property(
     *     title="price"
     * )
     *
     * @var float
     */
    private $price;

    /**
     * @OA\Property(
     *     title="status"
     * )
     *
     * @var int
     */
    private $status;
}
