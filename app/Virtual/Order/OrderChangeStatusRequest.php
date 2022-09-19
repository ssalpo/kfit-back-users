<?php

namespace App\Virtual\Order;

/**
 * @OA\Schema(
 *     title="Order change status request",
 *     @OA\Xml(
 *         name="OrderChangeStatusRequest"
 *     )
 * )
 */
class OrderChangeStatusRequest
{
    /**
     * @OA\Property(
     *     title="status"
     * )
     *
     * @var int
     */
    private $status;
}
