<?php

namespace Tests\Feature\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\OAuthHelper;
use Tests\Helpers\UserHelper;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_it_can_login_as_admin()
    {
        $admin = UserHelper::makeAdmin();

        $client = OAuthHelper::getAdminOAuthClient();

        $body = [
            'username' => $admin->email,
            'password' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'grant_type' => 'password',
            'scope' => '*'
        ];

        $this->postJson('/oauth/token', $body)
            ->assertStatus(200)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
    }

    /**
     * @return void
     */
    public function test_it_can_login_as_client_by_phone_number()
    {
        $user = UserHelper::makeClient();

        $client = OAuthHelper::getClientsOAuthClient();

        $body = [
            'username' => $user->phone,
            'password' => '1234',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'grant_type' => 'password',
            'scope' => '*'
        ];

        $this->postJson('/oauth/token', $body)
            ->assertStatus(200)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
    }

    /**
     * @return void
     */
    public function test_it_can_login_as_client_by_email_and_password()
    {
        $user = UserHelper::makeClient();

        $client = OAuthHelper::getClientsOAuthClient();

        $body = [
            'username' => $user->email,
            'password' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'grant_type' => 'password',
            'scope' => '*'
        ];

        $this->postJson('/oauth/token', $body)
            ->assertStatus(200)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token']);
    }
}
