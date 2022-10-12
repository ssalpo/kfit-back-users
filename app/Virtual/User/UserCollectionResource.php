<?php

namespace App\Virtual\User;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(title="User collection resource")
 */
class UserCollectionResource
{
    /**
     * @OA\Property(
     *     title="array",
     *     @OA\Items(ref="#/components/schemas/UserResource")
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
