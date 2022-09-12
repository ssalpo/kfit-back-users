<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Requests\Admin\UserEditRequest;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Jobs\MoveUserAvatarJob;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
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
        $user = User::create($request->validated());

        MoveUserAvatarJob::dispatch($user)->onQueue('avatar');

        return new UserResource($user);
    }

    /**
     * Обновляет данные пользователя
     *
     * @param User $user
     * @param UserEditRequest $request
     * @return UserResource
     */
    public function update(User $user, UserEditRequest $request): UserResource
    {
        $isAvatarChanged = $user->avatar !== $request->avatar;

        $user->update($request->validated());

        if ($isAvatarChanged) MoveUserAvatarJob::dispatch($user)->onQueue('avatar');

        return new UserResource($user->refresh());
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
