<?php

namespace Tests\Helpers;

use App\Models\Product;

class ProductHelper
{
    public static function getRandomProduct()
    {
        Product::factory(5)->create();

        return Product::inRandomOrder()->first();
    }

    public static function makeProduct()
    {
        return Product::factory()->create();
    }
}
