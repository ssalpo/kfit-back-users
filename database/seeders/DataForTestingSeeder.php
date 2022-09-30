<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DataForTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);

        User::factory(10)->create()->each(function (User $user) {
            $user->assignRole(Role::inRandomOrder()->first());
        });

        \App\Models\Client::factory(10)->create();

        Product::factory(10)->create();

        Order::factory(30)->create();
    }
}
