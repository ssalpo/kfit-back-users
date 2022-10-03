<?php

namespace Tests\Helpers;

use App\Models\Order;

class OrderHelper
{
    public static function getRandomOrder()
    {
        Order::factory(5)->create();

        return Order::inRandomOrder()->first();
    }

    public static function getRandomClientId()
    {
        return Order::inRandomOrder()->first()->client_id;
    }
}
