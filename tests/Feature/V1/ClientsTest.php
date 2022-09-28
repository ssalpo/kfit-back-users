<?php

namespace Tests\Feature\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\Helpers\ClientHelper;
use Tests\Helpers\UserHelper;
use Tests\TestCase;

class ClientsTest extends TestCase
{
    use RefreshDatabase;

    const RESOURCE_STRUCTURE = [
        'id', 'name', 'email', 'phone', 'avatar', 'active', 'orders'
    ];

    /**
     * @return void
     */
    public function test_clients_can_see_their_profile()
    {
        ClientHelper::actAsClient();

        $response = $this->getJson('/api/v1/clients/me');

        $resource = self::RESOURCE_STRUCTURE;

        array_pop($resource);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => $resource
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_can_see_list_of_clients()
    {
        UserHelper::actAsAdmin();

        $response = $this->getJson('/api/v1/clients');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonStructure([
                'data' => [
                    '*' => self::RESOURCE_STRUCTURE
                ]
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_can_add_new_client()
    {
        UserHelper::actAsAdmin();

        $form = [
            'name' => 'Test user name',
            'email' => 'some-user@gmail.com',
            'phone' => '+79521621026',
            'active' => true,
            'avatar' => null,
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/clients', $form);

        $response->assertStatus(201)
            ->assertJson(['data' => Arr::except($form, 'password')])
            ->assertJsonStructure([
                'data' => self::RESOURCE_STRUCTURE
            ]);
    }

    /**
     * @return void
     */
    public function test_can_see_client_info_by_id()
    {
        UserHelper::actAsAdmin();

        $client = ClientHelper::getRandomClient();

        $response = $this->getJson('/api/v1/clients/' . $client->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => self::RESOURCE_STRUCTURE
            ]);
    }

    /**
     * @return void
     */
    public function test_admin_can_edit_client_by_id()
    {
        UserHelper::actAsAdmin();

        $client = ClientHelper::getRandomClient();

        $form = [
            'name' => 'Test client name updated',
            'phone' => $client->phone,
            'email' => $client->email,
            'active' => $client->active,
        ];

        $response = $this->putJson('/api/v1/clients/' . $client->id, $form);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => self::RESOURCE_STRUCTURE
            ])
            ->assertJsonPath('data.name', $form['name'])
            ->assertJsonPath('data.id', $client->id);
    }

    /**
     * @return void
     */
    public function test_client_cannot_see_any_admin_pages()
    {
        ClientHelper::actAsClient();

        $client = ClientHelper::getRandomClient();

        // List
        $this->getJson('/api/v1/users/')
            ->assertStatus(403);

        // Show
        $this->getJson('/api/v1/clients/' . $client->id)
            ->assertStatus(403);

        // Add
        $this->postJson('/api/v1/clients')
            ->assertStatus(403);

        // Edit
        $this->putJson('/api/v1/clients/' . $client->id)
            ->assertStatus(403);
    }

    /**
     * @return void
     */
    public function test_user_with_role_guest_can_not_work_with_clients_data()
    {
        UserHelper::actAsAdminWithGuestRole();

        $client = ClientHelper::getRandomClient();

        // Show
        $this->getJson('/api/v1/clients/' . $client->id)
            ->assertStatus(403);

        // Add
        $this->postJson('/api/v1/clients')
            ->assertStatus(403);

        // Edit
        $this->putJson('/api/v1/clients/' . $client->id)
            ->assertStatus(403);
    }
}
