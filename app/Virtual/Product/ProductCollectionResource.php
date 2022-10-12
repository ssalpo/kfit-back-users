<?php

namespace App\Virtual\Product;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(title="Product collection resource")
 */
class ProductCollectionResource
{
    /**
     * @OA\Property(
     *     title="array",
     *     @OA\Items(ref="#/components/schemas/ProductResource")
     * )
     *
     * @var array
     */
    private $data;

    /**
     * @OA\Property(
     *     title="meta"
     * )
     *
     * @var object
     */
    private $meta;

    /**
     * @OA\Property(
     *     title="links"
     * )
     *
     * @var object
     */
    private $links;
}
