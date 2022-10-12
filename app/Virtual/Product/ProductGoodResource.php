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
     *     title="product_id",
     *     description="Related product id"
     * )
     *
     * @var int
     */
    private $product_id;

    /**
     * @OA\Property(
     *     title="related_id",
     *     description="Related (courses, workouts) id from video service."
     * )
     *
     * @var int
     */
    private $related_id;

    /**
     * @OA\Property(
     *     title="related_type",
     *     description="Related model types. Marathon=1, COURSE=2, WORKOUT=3",
     *     example="2"
     * )
     *
     * @var int
     */
    private $related_type;
}
