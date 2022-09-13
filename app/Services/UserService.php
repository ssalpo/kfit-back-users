<?php

namespace App\Services;

use App\Constants\TempFile;
use App\Models\User;
use Illuminate\Support\Arr;

class UserService
{
    /**
     * @var TempFileService
     */
    private $tempFileService;

    public function __construct(TempFileService $tempFileService)
    {
        $this->tempFileService = $tempFileService;
    }

    /**
     * Adds a new user
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $user = User::create($data);

        if ($user->avatar) $this->tempFileService->moveFromTmpFolder(TempFile::FOLDER_AVATAR, $user->avatar);

        return $user;
    }

    /**
     * Update user data by ID
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $user = User::findOrFail($id);

        $isAvatarChanged = $user->avatar !== Arr::get($data, 'avatar');

        $user->update($data);

        if ($isAvatarChanged) $this->tempFileService->moveFromTmpFolder(TempFile::FOLDER_AVATAR, $user->avatar);

        return $user->refresh();
    }
}
