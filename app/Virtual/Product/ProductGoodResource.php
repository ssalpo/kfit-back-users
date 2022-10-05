<?php

namespace App\Virtual\Product;

/**
 * @OA\Schema(
 *     title="Product goods resource",
 *     @OA\Xml(
 *         name="ProductGoodResource"
 *     )
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
