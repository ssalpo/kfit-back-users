<?php

namespace App\Virtual\Order;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Order change status request"
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
