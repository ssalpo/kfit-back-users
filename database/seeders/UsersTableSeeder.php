<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Test Admin',
                'email' => 'admin@admin.admin',
                'active' => 1,
                'role' => 1,
                'password' => 'admin',
            ],
            [
                'name' => 'Test Admin Guest',
                'email' => 'guest@guest.guest',
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
}
