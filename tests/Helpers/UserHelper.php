<?php

namespace Tests\Helpers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;

class UserHelper
{
    public static function makeUserWithRole(array $roles)
    {
        self::makeRoles();

        $user = User::factory()->create();

        $user->assignRole($roles);

        return $user;
    }

    public static function makeAdmin()
    {
        return self::makeUserWithRole(['admin']);
    }

    public static function makeAdminWithGuestRole()
    {
        return self::makeUserWithRole(['guest']);
    }

    public static function actAsAdmin()
    {
        Passport::actingAs(self::makeAdmin());
    }

    public static function getAdminRole()
    {
        return Role::whereName('admin')->firstOrFail();
    }

    public static function actAsAdminWithGuestRole()
    {
        Passport::actingAs(self::makeAdminWithGuestRole());
    }

    public static function makeClient()
    {
        return Client::factory()->create();
    }

    public static function getRandomUser()
    {
        User::factory(5)->create();

        return User::inRandomOrder()->first();
    }

    public static function makeRoles()
    {
        Artisan::call('db:seed --class=RolesTableSeeder');
    }
}
