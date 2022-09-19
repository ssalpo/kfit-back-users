<?php

namespace App\Virtual\Order;

/**
 * @OA\Schema(
 *     title="Order store request",
 *     @OA\Xml(
 *         name="OrderRequest"
 *     )
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
