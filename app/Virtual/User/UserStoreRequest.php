<?php

namespace App\Virtual\User;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="User store request"
 * )
 */
class UserStoreRequest
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

    /**
     * @OA\Property(
     *     title="active"
     * )
     *
     * @var boolean
     */
    private $active;

    /**
     * @OA\Property(
     *     title="role"
     * )
     *
     * @var string
     */
    private $role;

    /**
     * @OA\Property(
     *     title="password"
     * )
     *
     * @var string
     */
    private $password;
}
