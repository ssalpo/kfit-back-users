<?php

namespace App\Virtual\User;

/**
 * @OA\Schema(
 *     title="User resource",
 *     @OA\Xml(
 *         name="UserResource"
 *     )
 * )
 */
class UserResource
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
