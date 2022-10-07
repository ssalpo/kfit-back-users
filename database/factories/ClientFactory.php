<?php

namespace Database\Factories;

use App\Utils\Formatters\PhoneFormatter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $phone = $this->faker->e164PhoneNumber();

        return [
            'name' => $this->faker->name(),
            'phone' => $phone,
            'phone_code' => '1234',
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ];
    }
}
