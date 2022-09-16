<?php

namespace App\Virtual\Product;

/**
 * @OA\Schema(
 *     title="Product resource",
 *     @OA\Xml(
 *         name="ProductResource"
 *     )
 * )
 */
class ProductResource
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
     *     title="expired_at"
     * )
     *
     * @var string
     */
    private $expired_at;
}
