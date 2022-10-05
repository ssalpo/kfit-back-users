<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TestingUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // @TODO use only for testing needs

        $this->adminUsers();
        $this->clients();
    }

    private function adminUsers()
    {
        $users = [
            [
                'name' => 'Test Admin',
                'email' => 'test@test.admin',
                'active' => 1,
                'role' => 1,
                'password' => 'admin',
            ],
            [
                'name' => 'Test Admin Guest',
                'email' => 'test@test.guest',
                'active' => 1,
                'role' => 2,
                'password' => 'guest',
            ]
        ];

        foreach ($users as $data) {
            $user = User::create(Arr::except($data, ['role']));

            $user->assignRole($data['role']);
        }
    }

    private function clients()
    {
        $clients = [
            [
                'name' => 'Test client 1',
                'phone' => '79522221133',
                'email' => 'test1client@client.client',
                'password' => '79522221133',
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Test client 2',
                'phone' => '79524443355',
                'email' => 'test2client@client.client',
                'password' => '79524443355',
                'remember_token' => Str::random(10),
            ],
        ];

        foreach ($clients as $client) {
            Client::create(Arr::except($client, ['role']));
        }
    }
}
