<?php

namespace App\Virtual\Product;

/**
 * @OA\Schema(
 *     title="Product store request",
 *     @OA\Xml(
 *         name="ProductRequest"
 *     )
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
     *     title="expired_at"
     * )
     *
     * @var string
     */
    private $expired_at;
}
