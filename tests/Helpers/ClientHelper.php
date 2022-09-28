<?php

namespace Tests\Helpers;

use App\Models\Client;
use Laravel\Passport\Passport;

class ClientHelper
{
    public static function getRandomUser()
    {
        Client::factory(5)->create();

        return Client::inRandomOrder()->first();
    }

    public static function makeClient()
    {
        return Client::factory()->create();
    }

    public static function actAsClient()
    {
        Passport::actingAs(self::makeClient());
    }
}
