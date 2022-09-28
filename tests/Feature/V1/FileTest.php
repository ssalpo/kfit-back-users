<?php

namespace Tests\Feature\V1;

use App\Services\TempFileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Helpers\UserHelper;
use Tests\TestCase;

class FileTest extends TestCase
{
    const RESOURCE_STRUCTURE = ['id', 'user_filename'];

    /**
     * @return void
     */
    public function test_user_can_upload_file()
    {
        Storage::fake('local');

        UserHelper::actAsAdmin();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson('/api/v1/files/upload', [
            'file' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => self::RESOURCE_STRUCTURE
            ]);

        $path = TempFileService::TMP_FOLDER_NAME . DIRECTORY_SEPARATOR . $response->json('data.id');

        Storage::disk('local')->assertExists($path);
    }

    public function test_user_can_view_their_avatar_and_resize_it()
    {
        Storage::fake('local');

        UserHelper::actAsAdmin();

        $role = UserHelper::getAdminRole();

        $user = UserHelper::getRandomUser();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson('/api/v1/files/upload', [
            'file' => $file,
        ]);

        $avatar = $response->json('data.id');

        $form = [
            'name' => $user->name,
            'email' => $user->email,
            'active' => $user->active,
            'role' => $role->id,
            'avatar' => $avatar
        ];

        // Update avatar
        $response = $this->putJson('/api/v1/users/' . $user->id, $form);

        $response->assertStatus(200)
            ->assertJsonPath('data.avatar', $avatar);

        $path = '/files/user/avatar/' . $avatar;

        Storage::disk('local')->assertExists($path);

        $response = $this->getJson('/api/v1/files/user/avatar/' . $avatar);

        $response->assertHeader('Content-type', $file->getMimeType());

        $response = $this->getJson("/api/v1/files/image/user/avatar/$avatar/200/200");

        $response->assertHeader('Content-type', $file->getMimeType());
    }
}
