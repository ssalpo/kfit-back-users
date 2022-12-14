<?php

namespace App\Services;

use App\Constants\TempFile;
use App\Models\Client;
use Illuminate\Support\Arr;

class ClientService
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
     * Update client data by ID
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $client = Client::findOrFail($id);

        $oldAvatar = $client->avatar;

        $isAvatarChanged = $client->avatar !== Arr::get($data, 'avatar');

        $client->update($data);

        $client->assignRole(Arr::get($data, 'role'));

        if ($isAvatarChanged) {
            $this->tempFileService->moveFromTmpFolder(TempFile::FOLDER_CLIENT_AVATAR, $client->avatar);

            if($oldAvatar) $this->tempFileService->removeFileFromFolder(TempFile::FOLDER_CLIENT_AVATAR, $oldAvatar);
        }

        return $client->refresh();
    }
}
