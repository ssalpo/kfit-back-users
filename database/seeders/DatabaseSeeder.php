<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);

        \App\Models\User::factory(10)->create()->each(function (User $user) {
            $user->assignRole(Role::inRandomOrder()->first());
        });
    }
}
