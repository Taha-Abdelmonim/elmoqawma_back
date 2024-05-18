<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class BranchFactory extends Factory
{

    public function definition()
    {
        return [
          'name_ar' => $this->faker->name(),
          'name_en' => $this->faker->name(),
          'address' => $this->faker->address(),
          'phone' => $this->faker->phoneNumber(),
         
        ];
    }


    /*public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }*/
}
