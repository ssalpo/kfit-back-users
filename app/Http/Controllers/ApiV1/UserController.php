<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Requests\Admin\UserEditRequest;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        $this->middleware('role:admin')->only(['store', 'update', 'resetPassword']);
    }

    /**
     * Returns a list of users
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
     * Returns the data of the currently logged in user
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
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Reset user password
     *
     * @param int $user
     * @return void
     */
    public function resetPassword(int $user)
    {
        $this->userService->resetPassword($user);
    }
}
