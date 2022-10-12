<?php

namespace App\Virtual\Product;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Product store request"
 * )
 */
class ProductRequest
{
    /**
     * @OA\Property(
     *     title="title"
     * )
     *
     * @var string
     */
    private $title;

    /**
     * @OA\Property(
     *     title="description"
     * )
     *
     * @var string
     */
    private $description;

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
     *     title="expired_at",
     * )
     *
     * @var date
     */
    private $expired_at;
}
