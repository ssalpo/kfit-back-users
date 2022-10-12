<?php

namespace App\Virtual\Client;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Client resource"
 * )
 */
class ClientResource
{
    /**
     * @OA\Property(
     *     title="name"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     title="phone"
     * )
     *
     * @var string
     */
    private $phone;

    /**
     * @OA\Property(
     *     title="email"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     title="avatar"
     * )
     *
     * @var string
     */
    private $avatar;
}
