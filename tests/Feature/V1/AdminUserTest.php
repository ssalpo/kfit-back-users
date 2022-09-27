<?php

namespace Tests\Feature\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\Helpers\UserHelper;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_can_see_profile()
    {
        UserHelper::actAsAdmin();

        $response = $this->getJson('/api/v1/users/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'avatar', 'active', 'roles']
            ]);
    }

    /**
     * @return void
     */
    public function test_can_see_users_list()
    {
        UserHelper::actAsAdmin();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'avatar', 'active', 'roles']
                ]
            ]);
    }

    /**
     * @return void
     */
    public function test_can_see_users_info_by_id()
    {
        UserHelper::actAsAdmin();

        $user = UserHelper::getRandomUser();

        $response = $this->getJson('/api/v1/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'avatar', 'active', 'roles']
            ]);
    }

    /**
     * @return void
     */
    public function test_user_with_role_guest_can_not_work_with_users_data()
    {
        UserHelper::actAsAdminWithGuestRole();

        $user = UserHelper::getRandomUser();

        $this->getJson('/api/v1/users/')
            ->assertStatus(403);

        // List
        $this->getJson('/api/v1/users/' . $user->id)
            ->assertStatus(403);

        // Show
        $this->getJson('/api/v1/users/' . $user->id)
            ->assertStatus(403);

        // Add
        $this->postJson('/api/v1/users')
            ->assertStatus(403);

        // Edit
        $this->putJson('/api/v1/users/' . $user->id)
            ->assertStatus(403);

        // Reset password
        $this->postJson(sprintf('/api/v1/users/%s/reset-password', $user->id))
            ->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_add_new_user()
    {
        UserHelper::actAsAdmin();

        $role = UserHelper::getAdminRole();

        $form = [
            'name' => 'Test user name',
            'email' => 'some-user@gmail.com',
            'password' => 'password',
            'role' => $role->id,
            'active' => 1,
        ];

        $response = $this->postJson('/api/v1/users/', $form);

        $response->assertStatus(201)
            ->assertJson(['data' => array_merge(
                Arr::except($form, ['role', 'password']),
                [
                    'avatar' => null,
                    'roles' => [['id' => $role->id, 'title' => $role->readable_name]],
                ]
            )])
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'avatar', 'active', 'roles']
            ])
            ->assertJsonPath('data.roles.0.id', $role->id);
    }

    /**
     * @return void
     */
    public function test_edit_user_by_id()
    {
        UserHelper::actAsAdmin();

        $role = UserHelper::getAdminRole();

        $user = UserHelper::getRandomUser();

        $form = [
            'name' => 'Test user name updated',
            'email' => $user->email,
            'active' => $user->active,
            'role' => $role->id,
        ];

        $response = $this->putJson('/api/v1/users/' . $user->id, $form);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'avatar', 'active', 'roles']
            ])
            ->assertJsonPath('data.name', $form['name'])
            ->assertJsonPath('data.id', $user->id);
    }

    /**
     * @return void
     */
    public function test_reset_user_password_by_id()
    {
        UserHelper::actAsAdmin();

        $user = UserHelper::getRandomUser();

        $response = $this->postJson('/api/v1/users/' . $user->id . '/reset-password');

        $changedUser = User::findOrFail($user->id);

        $response->assertStatus(200);

        $this->assertNotEquals(
            $user->password,
            $changedUser->password
        );
    }
}
