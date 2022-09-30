<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = Product::inRandomOrder()->first();

        return [
            'client_id' => Client::inRandomOrder()->first()->id,
            'product_id' => $product->id,
            'price' => $product->price,
            'status' => \App\Constants\Order::STATUS_PAID,
        ];
    }
}
