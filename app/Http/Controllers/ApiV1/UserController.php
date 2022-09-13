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
    }

    /**
     * Возвращает список пользователей
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
     * Возвращает данные текущего авторизованного пользователя
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
     * Добавляет нового пользователя
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
     * Обновляет данные пользователя
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
     * Просмотр данных пользователя по ID
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }
}
