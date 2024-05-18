<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class UserFactory extends Factory
{

    public function definition()
    {
        return [
          'name' => $this->faker->name(),
          'phone' => $this->faker->phoneNumber(),
          'email' => $this->faker->unique()->safeEmail(),
          'email_verified_at' => now(),
          'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
          'remember_token' => Str::random(10),
          'roles_name' => $this->faker->randomElement([["owner", "admin"]]),
          'status' => $this->faker->randomElement([0,1]),
        ];
    }


    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
