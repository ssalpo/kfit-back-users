<?php

namespace App\Virtual\Order;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Order store request"
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

    /**
     * @OA\Property(
     *     title="platform"
     * )
     *
     * @var int
     */
    private $platform;

    /**
     * @OA\Property(
     *     title="platform_id"
     * )
     *
     * @var string
     */
    private $platform_id;
}
