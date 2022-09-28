<?php

namespace App\Virtual\Order;

/**
 * @OA\Schema(
 *     title="Order store request",
 *     @OA\Xml(
 *         name="OrderResource"
 *     )
 * )
 */
class OrderResource
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

    /**
     * @OA\Property(
     *     title="paid_at"
     * )
     *
     * @var date
     */
    private $paid_at;

    /**
     * @OA\Property(
     *     title="expired_at"
     * )
     *
     * @var date
     */
    private $expired_at;
}
