<?php

namespace App\Virtual\Product;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Product goods resource"
 * )
 */
class ProductGoodResource
{
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
     *     title="related_id"
     * )
     *
     * @var int
     */
    private $related_id;

    /**
     * @OA\Property(
     *     title="related_type"
     * )
     *
     * @var int
     */
    private $related_type;
}
