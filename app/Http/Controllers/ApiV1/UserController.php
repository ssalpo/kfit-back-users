<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        $this->middleware('role:admin')->except(['me']);
    }

    /**
     * Returns a list of users
     *
     * @OA\Get(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Returns a list of users",
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      )
     * )
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(
            User::paginate()
        );
    }

    /**
     * Returns the data of the current login user
     *
     * @OA\Get(
     *     path="/users/me",
     *     tags={"Users"},
     *     summary="Returns the data of the currently logged in user",
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      )
     * )
     *
     * @param Request $request
     * @return UserResource
     */
    public function me(Request $request): UserResource
    {
        return new UserResource(
            $request->user()
        );
    }

    /**
     * Adds a new user
     *
     * @OA\Post(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Adds a new user",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/UserStoreRequest")
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     )
     * )
     *
     * @param UserStoreRequest $request
     * @return UserResource
     */
    public function store(UserStoreRequest $request): UserResource
    {
        return new UserResource(
            $this->userService->create($request->validated())
        );
    }

    /**
     * Updates user data
     *
     * @OA\Put(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Updates a user data",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/UserEditRequest")
     *         )
     *      ),
     *     @OA\Response(
     *         response=202,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     )
     * )
     *
     * @param int $user
     * @param UserEditRequest $request
     * @return UserResource
     */
    public function update(int $user, UserEditRequest $request): UserResource
    {
        return new UserResource(
            $this->userService->update($user, $request->validated())
        );
    }

    /**
     * View user data by ID
     *
     * @OA\Get(
     *     path="/users/{user}",
     *     tags={"Users"},
     *     summary="Returns a file to view",
     *     @OA\Parameter(
     *         in="path",
     *         name="user",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      )
     * )
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Remove the specified user from storage.
     *
     * @OA\Delete(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Remove the specified user from storage.",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="int"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     )
     * )
     *
     * @param User $user
     * @return UserResource
     */
    public function destroy(User $user): UserResource
    {
        $user->delete();

        return new UserResource($user);
    }

    /**
     * Reset user password
     *
     * @OA\Post(
     *      path="/users/{user}/reset-password",
     *      tags={"Users"},
     *      summary="Reset user password",
     *      @OA\Parameter(
     *          in="path",
     *          description="User id",
     *          name="user",
     *          required=true,
     *          @OA\Schema(type="int")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     * )
     *
     * @param int $user
     * @return void
     */
    public function resetPassword(int $user)
    {
        $this->userService->resetPassword($user);
    }
}
