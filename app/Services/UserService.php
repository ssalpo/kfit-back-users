<?php

namespace App\Services;

use App\Constants\TempFile;
use App\Mail\UserPasswordReset;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        if ($user->avatar) $this->tempFileService->moveFromTmpFolder(TempFile::FOLDER_USER_AVATAR, $user->avatar);

        $user->assignRole($data['role']);

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

        $oldAvatar = $user->avatar;

        $isAvatarChanged = $user->avatar !== Arr::get($data, 'avatar');

        $user->update($data);

        $user->syncRoles(Arr::get($data, 'role'));

        if ($isAvatarChanged) {
            $this->tempFileService->moveFromTmpFolder(TempFile::FOLDER_USER_AVATAR, $user->avatar);

            $this->tempFileService->removeFileFromFolder(TempFile::FOLDER_USER_AVATAR, $oldAvatar);
        }

        return $user->refresh();
    }

    /**
     * Reset user password
     *
     * @param int $id
     * @return bool
     */
    public function resetPassword(int $id): bool
    {
        $user = User::findOrFail($id);

        $newPassword = Str::random(8);

        $isUpdated = $user->update(['password' => Hash::make($newPassword)]);

        if ($isUpdated) Mail::to($user)->queue(
            (new UserPasswordReset($user, $newPassword))->onQueue('emails')
        );

        return $isUpdated;
    }
}
