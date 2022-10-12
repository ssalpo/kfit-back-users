<?php

namespace App\Virtual\Product;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Product resource"
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

    /**
     * @OA\Property(
     *     title="goods",
     *     @OA\Items(ref="#/components/schemas/ProductGoodResource")
     * )
     *
     * @var array
     */
    private $goods;
}
